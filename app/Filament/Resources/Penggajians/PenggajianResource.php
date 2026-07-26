<?php

namespace App\Filament\Resources\Penggajians;

use App\Filament\Resources\Penggajians\Pages;
use App\Models\Penggajian;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Potongan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf; // <--- PENTING: Library PDF Dipanggil Di Sini
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Filament\Support\RawJs;


class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;
    protected static ?string $navigationGroup = 'Proses Akhir';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Data Penggajian';
    protected static ?string $modelLabel = 'Data Penggajian';
    protected static ?string $pluralModelLabel = 'Penggajian';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Input Data')
                    ->schema([
                        Forms\Components\Select::make('karyawan_id')
                            ->relationship('karyawan', 'nama_lengkap')
                            ->label('Nama Karyawan')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live() // PENTING: Agar validasi jalan saat user pilih nama
                            ->rule(function (Forms\Get $get, $record) {
                                return \Illuminate\Validation\Rule::unique('penggajians', 'karyawan_id')
                                    ->where(function ($query) use ($get) {
                                        // Ambil tanggal dari form
                                        $tanggal = $get('tanggal_gajian'); 
                                        
                                        // Jika tanggal diisi, kita cek Bulan & Tahun-nya
                                        if ($tanggal) {
                                            $bulan = \Carbon\Carbon::parse($tanggal)->month;
                                            $tahun = \Carbon\Carbon::parse($tanggal)->year;
                                            
                                            // Cari di database: Apakah ada gaji di bulan & tahun ini?
                                            return $query->whereMonth('tanggal_gajian', $bulan)
                                                        ->whereYear('tanggal_gajian', $tahun);
                                        }
                                    })
                                    ->ignore($record); // Abaikan data diri sendiri saat Edit
                            })
                            ->validationMessages([
                                'unique' => 'Karyawan ini sudah digaji pada periode (Bulan & Tahun) tersebut.',
                            ])
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (! $state) return;
                                
                                $karyawan = Karyawan::with('jabatan')->find($state);
                                if (! $karyawan || ! $karyawan->jabatan) return;

                                $gajiPokok = $karyawan->jabatan->gaji_pokok;
                                $tunjangan = $karyawan->jabatan->tunjangan_makan + $karyawan->jabatan->tunjangan_transport;

                                $absensi = Absensi::where('karyawan_id', $state)
                                    ->latest('created_at') 
                                    ->first();

                                $tarifLemburPerJam = $karyawan->jabatan->uang_lembur; // Mengambil langsung dari tabel jabatan
                                $jamLembur = $absensi ? $absensi->jumlah_lembur_jam : 0;
                                $uangLembur = $jamLembur * $tarifLemburPerJam;

                                $totalPotongan = Potongan::where('karyawan_id', $state)
                                    ->sum('jumlah_potongan');

                                $gajiBersih = ($gajiPokok + $tunjangan + $uangLembur) - $totalPotongan;

                                $set('gaji_pokok', number_format($gajiPokok, 0, ',', '.'));
                                $set('total_tunjangan', number_format($tunjangan, 0, ',', '.'));
                                $set('total_lembur', number_format($uangLembur, 0, ',', '.'));
                                $set('total_potongan', number_format($totalPotongan, 0, ',', '.'));
                                $set('gaji_bersih', number_format($gajiBersih, 0, ',', '.'));
                            }),

                        Forms\Components\DatePicker::make('tanggal_gajian')
                            ->required()
                            ->default(now()),
                    ])->columns(2),

                Forms\Components\Section::make('Rincian & Kalkulator Gaji')
                    ->schema([
                        Forms\Components\TextInput::make('gaji_pokok')
                            ->label('Gaji Pokok')
                            ->prefix('Rp')
                            ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 0, ',', '.') : '0')
                            ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace('.', '', $state) : 0)
                            ->readOnly()
                            ->required(),

                        Forms\Components\TextInput::make('total_tunjangan')
                            ->label('Total Tunjangan')
                            ->prefix('Rp')
                            ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 0, ',', '.') : '0')
                            ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace('.', '', $state) : 0)
                            ->default(0)
                            ->readOnly(),

                        Forms\Components\TextInput::make('total_lembur')
                            ->label('Total Uang Lembur')
                            ->prefix('Rp')
                            ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 0, ',', '.') : '0')
                            ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace('.', '', $state) : 0)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::hitungGajiBersih($get, $set);
                            }),

                        Forms\Components\TextInput::make('total_potongan')
                            ->label('Total Potongan')
                            ->prefix('Rp')
                            ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 0, ',', '.') : '0')
                            ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace('.', '', $state) : 0)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::hitungGajiBersih($get, $set);
                            }),

                        Forms\Components\TextInput::make('gaji_bersih')
                            ->label('Total Gaji Bersih')
                            ->prefix('Rp')
                            ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 0, ',', '.') : '0')
                            ->dehydrateStateUsing(fn ($state) => $state ? (float) str_replace('.', '', $state) : 0)
                            ->readOnly()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function hitungGajiBersih(Get $get, Set $set)
    {
        $gaji = (int) $get('gaji_pokok');
        $tunjangan = (int) $get('total_tunjangan');
        $lembur = (int) $get('total_lembur');
        $potongan = (int) $get('total_potongan');

        $hasil = ($gaji + $tunjangan + $lembur) - $potongan;
        $set('gaji_bersih', $hasil);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama_lengkap')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_gajian')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gaji_bersih')
                    ->label('Gaji Bersih')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('status_validasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu Validasi' => 'warning',
                        'Disetujui' => 'success',
                            default => 'gray',
            }),
            ])
            ->recordUrl(
                fn ($record): string => static::getUrl('view', ['record' => $record])
            )
            ->filters([
                // FILTER PERIODE (BULAN & TAHUN)
                Tables\Filters\Filter::make('periode')
                    ->form([
                        Forms\Components\Select::make('bulan')
                            ->label('Bulan')
                            ->options([
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ]),
                        Forms\Components\Select::make('tahun')
                            ->label('Tahun')
                            ->options([
                                '2025' => '2025',
                                '2026' => '2026',
                                '2027' => '2027',
                                '2028' => '2028',
                            ])
                            ->default(now()->year),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['bulan'],
                                fn (Builder $query, $date): Builder => $query->whereMonth('tanggal_gajian', $date),
                            )
                            ->when(
                                $data['tahun'],
                                fn (Builder $query, $date): Builder => $query->whereYear('tanggal_gajian', $date),
                            );
                    })
            ])

            ->actions([

                // TOMBOL KIRIM EMAIL
                Tables\Actions\Action::make('email_slip')
                    ->label('Kirim Email')
                    ->icon('heroicon-o-envelope')
                    ->color('info') // Warna Biru
                    ->requiresConfirmation() // Biar gak kepencet
                    ->hidden(fn (Penggajian $record) => 
                        $record->status_validasi === 'Menunggu Validasi' && 
                        auth()->user()->hasRole('Karyawan')
                    )
                    ->action(function (Penggajian $record) {
                        // Cek ada emailnya gak?
                        if (!$record->karyawan->email) {
                            // Kalau gak ada, kasih peringatan (Notifikasi Filament)
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal')
                                ->body('Email karyawan belum diisi!')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Kirim Email
                        \Illuminate\Support\Facades\Mail::to($record->karyawan->email)
                            ->send(new \App\Mail\KirimSlipGaji($record));

                        // Kasih Notifikasi Sukses
                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil')
                            ->body('Slip gaji berhasil dikirim ke email karyawan.')
                            ->success()
                            ->send();
                    }),

                // TOMBOL 1: EDIT
                Tables\Actions\EditAction::make(),

                // TOMBOL 2: DOWNLOAD PDF (INI KODINGAN BARUNYA)
                Tables\Actions\Action::make('cetak_slip')
                    ->label('Cetak Slip')
                    ->icon('heroicon-o-printer')
                    ->color('success') // Warna Hijau
                    ->hidden(fn (Penggajian $record) => 
                        $record->status_validasi === 'Menunggu Validasi' && 
                        auth()->user()->hasRole('Karyawan')
                    )
                    ->action(function (Penggajian $record) {
                        // Load View yang tadi kita buat
                        $pdf = Pdf::loadView('pdf.slip_gaji', ['record' => $record]);
                        
                        // Download file PDF-nya
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'Slip-Gaji-' . $record->karyawan->nama_lengkap . '.pdf');
                    }),

                // TOMBOL 3: DELETE
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('setujui')
                    ->label('Setujui Gaji')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation() // Memunculkan popup "Apakah Anda yakin?"
                    ->action(function ($record) {
                        $record->update(['status_validasi' => 'Disetujui']);
        })
        // KUNCI UTAMA: Tombol ini HANYA muncul untuk Owner dan jika statusnya masih Menunggu
        ->visible(fn ($record) => auth()->user()->hasRole('Owner') && $record->status_validasi === 'Menunggu Validasi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'view' => Pages\ViewPenggajian::route('/{record}'),
            'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Cek apakah yang sedang login jabatannya adalah 'Karyawan'
        if (auth()->user()->hasRole('Karyawan')) {
            // Jika ya, cocokkan 'nama_lengkap' di tabel karyawan dengan 'name' di akun user yang sedang login
            return $query->whereHas('karyawan', function (Builder $q) {
                $q->where('nama_lengkap', auth()->user()->name); 
            });
        }

        // Kalau yang login Admin atau Owner, biarkan semua data tampil
        return $query;
    }
    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): string
    {
        return 'Slip Gaji - ' . ($record->karyawan->nama_lengkap ?? 'Karyawan');
    }
}
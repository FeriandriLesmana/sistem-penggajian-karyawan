<?php

namespace App\Filament\Resources\Absensis;

use App\Filament\Resources\Absensis\Pages;
use App\Models\Absensi;
use Filament\Forms;
use Filament\Forms\Form; // PENTING: Pakai Form, bukan Schema
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Closure;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;
    protected static ?string $navigationGroup = 'Transaksi Bulanan';
    protected static ?int $navigationSort = 3;
    // Ikon standar (Text string, bukan class)
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $modelLabel = 'Absensi';
    protected static ?string $pluralModelLabel = 'Absensi';
    
    protected static ?string $navigationLabel = 'Absensi';

    // BAGIAN FORMULIR (Sudah diganti jadi Form $form)
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input Karyawan (Dropdown)
                Forms\Components\Select::make('karyawan_id')
                    ->relationship('karyawan', 'nama_lengkap')
                    ->required()
                    ->searchable()
                    ->preload(),

                // Input Bulan & Tahun
                Forms\Components\Select::make('bulan')
                    ->options([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ])
                    ->required(),
                
                Forms\Components\TextInput::make('tahun')
                    ->numeric()
                    ->default(date('Y'))
                    ->required(),

                // Input Kehadiran
                Forms\Components\Section::make('Data Kehadiran')
                    ->schema([
                        Forms\Components\TextInput::make('jumlah_hadir')
                            ->numeric()->default(0)->required()
                            ->maxValue(31) // Cegah input manual lebih dari 31
                            ->rules([
                                fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                    $hadir = (int) $value;
                                    $sakit = (int) $get('jumlah_sakit');
                                    $izin = (int) $get('jumlah_izin');
                                    
                                    $totalHari = $hadir + $sakit + $izin;
                                    
                                    // Cegah total hari melebihi batas maksimal kalender
                                    if ($totalHari > 31) {
                                        $fail("Total hari (Hadir + Sakit + Izin) tidak logis karena mencapai {$totalHari} hari. Maksimal kalender adalah 31 hari!");
                                    }
                                },
                            ]),
                        Forms\Components\TextInput::make('jumlah_sakit')
                            ->numeric()->default(0),
                        Forms\Components\TextInput::make('jumlah_izin')
                            ->numeric()->default(0),
                        Forms\Components\TextInput::make('jumlah_lembur_jam')
                            ->label('Lembur (Jam)')
                            ->numeric()->default(0)->maxValue(72),
                    ])->columns(2),
            ]);
    }

    // BAGIAN TABEL
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama_lengkap')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('bulan')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
                        '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
                        '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_hadir')
                    ->label('Hadir')
                    ->alignCenter(),
            ])
            ->recordUrl(
                fn ($record): string => static::getUrl('view', ['record' => $record])
            )
            ->filters([
                // FILTER KHUSUS KOLOM BULAN & TAHUN
                Tables\Filters\Filter::make('periode_absensi')
                    ->form([
                        Forms\Components\Select::make('f_bulan') // Saya kasih awalan 'f_' biar beda
                            ->label('Filter Bulan')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ]),
                        Forms\Components\Select::make('f_tahun')
                            ->label('Filter Tahun')
                            ->options([
                                '2025' => '2025',
                                '2026' => '2026',
                                '2027' => '2027',
                                '2028' => '2028',
                            ])
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['f_bulan'],
                                // Logika: Cari kolom 'bulan' yang angkanya sama dengan pilihan
                                fn (\Illuminate\Database\Eloquent\Builder $query, $val): \Illuminate\Database\Eloquent\Builder => $query->where('bulan', $val),
                            )
                            ->when(
                                $data['f_tahun'],
                                // Logika: Cari kolom 'tahun' yang angkanya sama dengan pilihan
                                fn (\Illuminate\Database\Eloquent\Builder $query, $val): \Illuminate\Database\Eloquent\Builder => $query->where('tahun', $val),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'view' => Pages\ViewAbsensi::route('/{record}'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
 {
     $query = parent::getEloquentQuery();

     if (auth()->user()->hasRole('Karyawan')) {
         return $query->whereHas('karyawan', function (Builder $q) {
             $q->where('nama_lengkap', auth()->user()->name); 
         });
     }

     return $query;
 }
}
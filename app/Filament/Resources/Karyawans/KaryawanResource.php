<?php

namespace App\Filament\Resources\Karyawans;

use App\Filament\Resources\Karyawans\Pages;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Karyawan';
    protected static ?string $pluralModelLabel = 'Karyawan';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- TAMBAHAN BARU: INPUT NIK ---
                Forms\Components\TextInput::make('nik')
                    ->label('NIK (Nomor Induk Karyawan)')
                    ->required() // Wajib diisi agar tidak error lagi
                    ->unique(ignoreRecord: true) // Biar tidak ada NIK kembar
                    ->numeric() // Pastikan isinya angka
                    ->maxLength(20),

                Forms\Components\TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('jabatan_id')
                    ->relationship('jabatan', 'nama_jabatan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Jabatan'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('Email')
                    ->maxLength(255),

                Forms\Components\TextInput::make('nomor_telepon')
                    ->tel()
                    ->label('No. Telepon')
                    ->maxLength(20),
                
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->required(),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat Lengkap')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tampilkan NIK di Tabel juga biar rapi
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('jabatan.nama_jabatan')
                    ->label('Jabatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('alamat')
                    ->limit(30) // Batasi huruf biar tabel gak kepanjangan
                    ->tooltip(fn ($state) => $state) // Kalau di-hover muncul lengkap
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyi dulu biar rapi (bisa diaktifkan di pojok tabel)
            ])
            ->recordUrl(
                fn ($record): string => static::getUrl('view', ['record' => $record])
            )
            ->filters([
                //
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

        public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        // Cek apakah yang sedang login jabatannya adalah 'Karyawan'
        if (auth()->user()->hasRole('Karyawan')) {
            // Jika ya, langsung filter kolom 'nama_lengkap' agar sama dengan 'name' yang login
            return $query->where('nama_lengkap', auth()->user()->name);
        }

        // Kalau yang login Admin atau Owner, biarkan semua data tampil
        return $query;
}
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'view' => Pages\ViewKaryawan::route('/{record}'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
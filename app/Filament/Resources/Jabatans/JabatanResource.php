<?php

namespace App\Filament\Resources\Jabatans;

use App\Filament\Resources\Jabatans\Pages;
use App\Models\Jabatan;
use Filament\Forms;
use Filament\Forms\Form; // PENTING: Pakai Form, bukan Schema
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 1;
    // Ikon standar (Text string simple)
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Jabatan';
    protected static ?string $pluralModelLabel = 'Jabatan';

    protected static ?string $recordTitleAttribute = 'nama_jabatan';

    // BAGIAN FORMULIR (Langsung didefinisikan di sini agar tidak eror file terpisah)
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_jabatan')
                    ->label('Nama Jabatan')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('gaji_pokok')
                    ->label('Gaji Pokok')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                Forms\Components\TextInput::make('tunjangan_makan')
                    ->label('Tunjangan Makan')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp'),

                Forms\Components\TextInput::make('tunjangan_transport')
                    ->label('Tunjangan Transport')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('uang_lembur')
                    ->label('Uang Lembur (Per Jam)')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
            ]);
    }

    // BAGIAN TABEL (Langsung didefinisikan di sini)
    public static function table(Table $table): Table
    {
        return $table
->columns([
                Tables\Columns\TextColumn::make('nama_jabatan')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('gaji_pokok')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('tunjangan_makan')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                
                Tables\Columns\TextColumn::make('tunjangan_transport')
                    ->label('Tunjangan Transport')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('uang_lembur')
                    ->label('Uang Lembur / Jam')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJabatans::route('/'),
            'create' => Pages\CreateJabatan::route('/create'),
            'view' => Pages\ViewJabatan::route('/{record}'),
            'edit' => Pages\EditJabatan::route('/{record}/edit'),
        ];
    }
}
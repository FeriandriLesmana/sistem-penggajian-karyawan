<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Spatie\Activitylog\Models\Activity; // Memanggil model CCTV dari Spatie
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;

class ActivityLogResource extends Resource
{
    // 1. Hubungkan ke tabel activity_log
    protected static ?string $model = Activity::class;
    
    // 2. Ganti Icon dan Nama Menu
    protected static ?string $navigationIcon = 'heroicon-o-video-camera'; // Icon Kamera
    protected static ?string $navigationLabel = 'Log Aktivitas';
    protected static ?string $navigationGroup = 'Sistem Keamanan';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu Kejadian')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
                    
                TextColumn::make('causer.name')
                    ->label('Pelaku (User)'),
                    
                TextColumn::make('event')
                    ->label('Aktivitas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                    
                TextColumn::make('description')
                    ->label('Keterangan'),
                    
                TextColumn::make('subject_type')
                    ->label('Tabel Target')
                    ->formatStateUsing(fn ($state) => class_basename($state)),
            ])
            ->defaultSort('created_at', 'desc') // Mengurutkan dari yang terbaru
            ->actions([
                Tables\Actions\ViewAction::make(), // Cuma bisa DILIHAT, tidak bisa diedit
            ])
            ->bulkActions([]);
    }

    public static function form(Form $form): Form
 {
     return $form
         ->schema([
             TextInput::make('causer_id')
                ->label('Pelaku (User)')
                ->formatStateUsing(fn ($record) => $record->causer ? $record->causer->name : 'Sistem Otomatis')
                ->disabled(),

             TextInput::make('event')
                 ->label('Aktivitas')
                 ->disabled(),

             TextInput::make('description')
                 ->label('Keterangan Lengkap')
                 ->columnSpanFull() // Biar kotaknya panjang
                 ->disabled(),

             KeyValue::make('properties.old')
                 ->label('Data Lama (Sebelum Diedit)')
                 ->disabled(),

             KeyValue::make('properties.attributes')
                 ->label('Data Baru (Sesudah Diedit)')
                 ->disabled(),
         ]);
 }
    // 3. MATIKAN FITUR TAMBAH DATA (CCTV tidak boleh ditambah manual)
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
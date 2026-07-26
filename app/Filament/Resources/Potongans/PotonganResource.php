<?php

namespace App\Filament\Resources\Potongans;

use App\Filament\Resources\Potongans\Pages;
use App\Models\Potongan;
use Filament\Forms;
use Filament\Forms\Form; // PENTING: Pakai Form
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PotonganResource extends Resource
{
    protected static ?string $model = Potongan::class;
    protected static ?string $navigationGroup = 'Transaksi Bulanan';
    protected static ?int $navigationSort = 4;
    // Ikon Gunting (Scissors) untuk Potongan
    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    protected static ?string $navigationLabel = 'Data Potongan';
    protected static ?string $pluralModelLabel = 'Potongan';

    protected static ?string $recordTitleAttribute = 'keterangan';

    // --- BAGIAN FORMULIR ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Pilih Karyawan yang terkena potongan
                Forms\Components\Select::make('karyawan_id')
                    ->relationship('karyawan', 'nama_lengkap')
                    ->label('Nama Karyawan')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan Potongan')
                    ->placeholder('Contoh: Terlambat, Alpha, Kasbon')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('jumlah_potongan')
                    ->label('Nominal Potongan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                
                Forms\Components\DatePicker::make('tanggal_potongan')
                    ->label('Tanggal')
                    ->default(now())
                    ->required(),
            ]);
    }

    // --- BAGIAN TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama_lengkap')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jumlah_potongan')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                ->sortable()
                ->color('danger'), // Warna merah biar kelihatan kalau ini minus

                Tables\Columns\TextColumn::make('tanggal_potongan')
                    ->date('d M Y')
                    ->sortable(),
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
            'index' => Pages\ListPotongans::route('/'),
            'create' => Pages\CreatePotongan::route('/create'),
            'view' => Pages\ViewPotongan::route('/{record}'),
            'edit' => Pages\EditPotongan::route('/{record}/edit'),
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
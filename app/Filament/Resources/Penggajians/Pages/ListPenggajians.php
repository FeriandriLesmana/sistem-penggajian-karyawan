<?php

namespace App\Filament\Resources\Penggajians\Pages;

use App\Filament\Resources\Penggajians\PenggajianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListPenggajians extends ListRecords
{
    protected static string $resource = PenggajianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        
            // TOMBOL EXPORT EXCEL (MODIFIKASI LENGKAP)
            ExportAction::make() 
                ->label('Download Laporan Lengkap')
                ->color('success')
                // 👇 INI BARIS KODE YANG DITAMBAHKAN UNTUK KEAMANAN 👇
                ->visible(fn () => auth()->user()->hasRole(['super_admin', 'Owner']))
                // 👆 ================================================ 👆
                ->exports([
                    \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                        ->withFilename('Laporan_Gaji_Detail_' . date('Y-m-d'))
                        ->withColumns([
                            \pxlrbt\FilamentExcel\Columns\Column::make('karyawan.nama_lengkap')->heading('Nama Karyawan'),
                            \pxlrbt\FilamentExcel\Columns\Column::make('tanggal_gajian')->heading('Tanggal'),
                            
                            
                            \pxlrbt\FilamentExcel\Columns\Column::make('gaji_pokok')
                                ->heading('Gaji Pokok')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                            
                            
                            
                            // Coba pakai 'total_tunjangan' 
                            \pxlrbt\FilamentExcel\Columns\Column::make('total_tunjangan') 
                                ->heading('Total Tunjangan')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                                
                            // Coba pakai 'total_uang_lembur' (kalau masih kosong, coba ganti jadi 'uang_lembur')
                            \pxlrbt\FilamentExcel\Columns\Column::make('total_lembur') 
                                ->heading('Uang Lembur')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                                
                            // Karena di form cuma ada "Total Potongan", kita panggil totalnya saja
                            \pxlrbt\FilamentExcel\Columns\Column::make('total_potongan')
                                ->heading('Total Potongan')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                            
                            // ---------------------------------------------------------

                            \pxlrbt\FilamentExcel\Columns\Column::make('gaji_bersih')
                                ->heading('Gaji Bersih (Take Home Pay)')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                        ])
                ]),
        ];
    }
}
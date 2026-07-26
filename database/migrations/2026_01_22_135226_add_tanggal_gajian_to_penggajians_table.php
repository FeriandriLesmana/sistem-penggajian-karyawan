<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penggajians', function (Blueprint $table) {
            // Menambahkan kolom tanggal_gajian
            $table->date('tanggal_gajian')->after('karyawan_id')->default(now());
            
            // Jaga-jaga kalau kolom angka ini juga belum ada, kita tambahkan sekalian
            // (Menggunakan ->nullable() agar tidak error jika sudah ada)
            if (!Schema::hasColumn('penggajians', 'gaji_pokok')) {
                $table->decimal('gaji_pokok', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('penggajians', 'total_tunjangan')) {
                $table->decimal('total_tunjangan', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('penggajians', 'total_potongan')) {
                $table->decimal('total_potongan', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('penggajians', 'gaji_bersih')) {
                $table->decimal('gaji_bersih', 15, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('penggajians', function (Blueprint $table) {
            $table->dropColumn('tanggal_gajian');
        });
    }
};
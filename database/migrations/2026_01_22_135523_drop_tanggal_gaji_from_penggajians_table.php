<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penggajians', function (Blueprint $table) {
            // Kita hapus kolom "Zombie" yang salah eja ini
            // Cek dulu apakah kolomnya ada, kalau ada baru hapus
            if (Schema::hasColumn('penggajians', 'tanggal_gaji')) {
                $table->dropColumn('tanggal_gaji');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penggajians', function (Blueprint $table) {
            // (Opsional) Mengembalikan kolom jika di-rollback
            $table->date('tanggal_gaji')->nullable();
        });
    }
};
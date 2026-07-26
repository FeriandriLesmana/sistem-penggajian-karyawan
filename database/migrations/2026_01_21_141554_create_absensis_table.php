<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
        $table->integer('bulan');
        $table->integer('tahun');
        $table->integer('jumlah_hadir')->default(0);
        $table->integer('jumlah_sakit')->default(0);
        $table->integer('jumlah_izin')->default(0);
        $table->integer('jumlah_lembur_jam')->default(0);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};

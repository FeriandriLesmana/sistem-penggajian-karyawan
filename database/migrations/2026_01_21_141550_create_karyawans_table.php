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
    Schema::create('karyawans', function (Blueprint $table) {
        $table->id();
        $table->string('nik')->unique();
        $table->string('nama_lengkap');
        $table->foreignId('jabatan_id')->constrained('jabatans')->cascadeOnDelete();
        $table->date('tanggal_masuk');
        $table->string('nomor_telepon')->nullable();
        $table->string('status_aktif')->default('aktif');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};

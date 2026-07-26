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
    Schema::create('penggajians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
        $table->date('tanggal_gaji');
        $table->decimal('gaji_pokok', 12, 2);
        $table->decimal('total_tunjangan', 12, 2);
        $table->decimal('total_lembur', 12, 2);
        $table->decimal('total_potongan', 12, 2);
        $table->decimal('gaji_bersih', 12, 2);
        $table->enum('status', ['draft', 'disetujui', 'dibayar'])->default('draft');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajians');
    }
};

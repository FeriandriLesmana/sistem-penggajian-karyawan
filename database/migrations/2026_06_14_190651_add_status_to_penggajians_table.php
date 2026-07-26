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
        Schema::table('penggajians', function (Blueprint $table) {
            // Menambahkan kolom status dengan nilai bawaan 'Menunggu Validasi'
            $table->string('status_validasi')->default('Menunggu Validasi')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggajians', function (Blueprint $table) {
            // Menghapus kolom jika sewaktu-waktu database di-rollback
            $table->dropColumn('status_validasi');
        });
    }
};
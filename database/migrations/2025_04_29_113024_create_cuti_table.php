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
    Schema::create('cuti', function (Blueprint $table) {
        // Primary key
        $table->integer('id_cuti');

        // Foreign keys
        $table->integer('id_pegawai'); // ID karyawan
        $table->integer('id_jenis_cuti'); // Foreign key ke tabel jenis_cuti

        // Kolom tanggal cuti
        $table->dateTime('tanggal_mulai');
        $table->dateTime('tanggal_selesai');

        // Kolom alasan cuti
        $table->text('alasan');

        // Kolom status persetujuan
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

        // Kolom untuk admin/atasan yang menyetujui
        $table->integer('approved_by')->nullable();
        $table->timestamp('approved_at')->nullable();

        // Timestamps
        $table->timestamps();

        // Foreign keys
        $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai')->onDelete('cascade');
        $table->foreign('id_jenis_cuti')->references('id_jenis_cuti')->on('jenis_cuti')->onDelete('cascade');
        $table->foreign('approved_by')->references('id_pegawai')->on('pegawai')->onDelete('set null');
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('cuti');
}
};

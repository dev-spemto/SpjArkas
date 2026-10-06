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
        Schema::create('spjs', function (Blueprint $table) {
            $table->id();
            $table->string('no_bkp')->nullable();
            $table->date('tanggal_transaksi');
            $table->enum('jenis_bos', ['Reguler', 'Kinerja', 'Daerah', 'Afirmasi'])->default('Reguler');
            $table->string('kode_kegiatan')->nullable();
            $table->string('nama_kegiatan')->nullable();
            $table->string('kode_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->text('uraian');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->string('penerima_toko')->nullable();
            $table->string('file_pdf_path')->nullable();
            $table->enum('sumber_input', ['manual', 'pdf_upload'])->default('manual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spjs');
    }
};
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
        Schema::create('bosp_documents', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_bos', ['Reguler', 'Kinerja', 'Daerah', 'Afirmasi']);
            $table->year('tahun');
            $table->string('tahap')->nullable(); // contoh: Tahap 1, Tahap 2, atau Full Year
            $table->string('nama_dokumen');
            $table->string('file_rkas_path')->nullable();
            $table->string('file_spj_path')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bosp_documents');
    }
};
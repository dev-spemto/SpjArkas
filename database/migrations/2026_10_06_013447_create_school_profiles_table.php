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
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->string('npsn')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten_kota')->default('Brebes');
            $table->string('provinsi')->default('Jawa Tengah');
            $table->string('nama_kepala_sekolah');
            $table->string('nip_kepala_sekolah')->nullable();
            $table->string('nama_bendahara');
            $table->string('nip_bendahara')->nullable();
            $table->string('nama_komite')->nullable();
            $table->string('nip_komite')->nullable();
            $table->string('redaksi_diterima')->nullable()->default('Bendahara Bantuan Operasional Sekolah');
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
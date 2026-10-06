<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            if (!Schema::hasColumn('spjs', 'jenis_transaksi')) {
                $table->enum('jenis_transaksi', ['Pengeluaran', 'Penerimaan'])->default('Pengeluaran')->after('jenis_bos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            if (Schema::hasColumn('spjs', 'jenis_transaksi')) {
                $table->dropColumn('jenis_transaksi');
            }
        });
    }
};
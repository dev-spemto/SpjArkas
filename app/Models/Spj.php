<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_bkp',
        'tanggal_transaksi',
        'jenis_bos',
        'jenis_transaksi',
        'kode_kegiatan',
        'nama_kegiatan',
        'kode_rekening',
        'nama_rekening',
        'uraian',
        'nominal',
        'penerima_toko',
        'file_pdf_path',
        'sumber_input',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'nominal' => 'decimal:2',
    ];
}
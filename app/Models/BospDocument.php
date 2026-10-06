<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BospDocument extends Model
{
    protected $fillable = [
        'jenis_bos',
        'tahun',
        'tahap',
        'nama_dokumen',
        'file_rkas_path',
        'file_spj_path',
        'keterangan',
    ];
}
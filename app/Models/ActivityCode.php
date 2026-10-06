<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityCode extends Model
{
    protected $fillable = [
        'kode_kegiatan',
        'nama_kegiatan',
        'program',
        'keterangan',
    ];
}
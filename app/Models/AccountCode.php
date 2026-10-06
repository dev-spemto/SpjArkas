<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountCode extends Model
{
    protected $fillable = [
        'kode_rekening',
        'nama_rekening',
        'keterangan',
    ];
}
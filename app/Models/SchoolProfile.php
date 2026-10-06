<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'alamat',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        'nama_bendahara',
        'nip_bendahara',
        'nama_komite',
        'nip_komite',
        'redaksi_diterima',
        'logo',
    ];
}
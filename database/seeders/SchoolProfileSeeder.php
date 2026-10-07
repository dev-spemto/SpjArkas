<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use Illuminate\Database\Seeder;

class SchoolProfileSeeder extends Seeder
{
    public function run(): void
    {
        SchoolProfile::updateOrCreate(
            ['id' => 1],
            [
                'nama_sekolah'        => 'SMP Muhammadiyah Tonjong',
                'npsn'                => '20325432',
                'alamat'              => 'Jl. Raya Linggapura No.46',
                'kecamatan'           => 'Tonjong',
                'kabupaten_kota'      => 'Kab. Brebes',
                'provinsi'            => 'Jawa Tengah',
                'nama_kepala_sekolah' => 'IRFAN TUNZILA, S.Ag.',
                'nip_kepala_sekolah'  => '-',
                'nama_bendahara'      => 'YUNIYATI, S.Pd.',
                'nip_bendahara'       => '-',
                'nama_komite'         => 'AHMAD FURQON, S. Ag.',
                'nip_komite'          => '-',
                'redaksi_diterima'    => 'Bendahara BOSP SMP Muhammadiyah Tonjong',
                'logo'                => null,
            ]
        );
    }
}
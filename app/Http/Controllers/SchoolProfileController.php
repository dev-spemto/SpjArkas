<?php

namespace App\Http\Controllers;

use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
{
    public function index()
    {
        $profile = SchoolProfile::first();
        return view('school_profile.index', compact('profile'));
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah'        => 'required|string|max:255',
            'npsn'                => 'nullable|string|max:50',
            'alamat'              => 'nullable|string',
            'kecamatan'           => 'nullable|string|max:255',
            'kabupaten_kota'      => 'nullable|string|max:255',
            'provinsi'            => 'nullable|string|max:255',
            'nama_kepala_sekolah' => 'required|string|max:255',
            'nip_kepala_sekolah'  => 'nullable|string|max:100',
            'nama_bendahara'      => 'required|string|max:255',
            'nip_bendahara'       => 'nullable|string|max:100',
            'nama_komite'         => 'nullable|string|max:255',
            'nip_komite'          => 'nullable|string|max:100',
            'redaksi_diterima'    => 'nullable|string|max:255',
            'logo'                => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $profile = SchoolProfile::firstOrNew();

        if ($request->hasFile('logo')) {
            if ($profile->logo && Storage::disk('public')->exists($profile->logo)) {
                Storage::disk('public')->delete($profile->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $profile->fill($validated)->save();

        return redirect()->back()->with('success', 'Profil Sekolah berhasil diperbarui.');
    }
}
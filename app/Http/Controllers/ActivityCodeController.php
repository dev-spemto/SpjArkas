<?php

namespace App\Http\Controllers;

use App\Models\ActivityCode;
use Illuminate\Http\Request;

class ActivityCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $activityCodes = ActivityCode::when($search, function ($query, $search) {
            return $query->where('kode_kegiatan', 'like', "%{$search}%")
                         ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                         ->orWhere('program', 'like', "%{$search}%");
        })->latest()->paginate(15);

        return view('activity_codes.index', compact('activityCodes', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kegiatan' => 'required|string|max:100|unique:activity_codes,kode_kegiatan',
            'nama_kegiatan' => 'required|string|max:255',
            'program' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        ActivityCode::create($validated);

        return redirect()->back()->with('success', 'Kode Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, ActivityCode $activityCode)
    {
        $validated = $request->validate([
            'kode_kegiatan' => 'required|string|max:100|unique:activity_codes,kode_kegiatan,' . $activityCode->id,
            'nama_kegiatan' => 'required|string|max:255',
            'program' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $activityCode->update($validated);

        return redirect()->back()->with('success', 'Kode Kegiatan berhasil diperbarui.');
    }

    public function destroy(ActivityCode $activityCode)
    {
        $activityCode->delete();

        return redirect()->back()->with('success', 'Kode Kegiatan berhasil dihapus.');
    }
}
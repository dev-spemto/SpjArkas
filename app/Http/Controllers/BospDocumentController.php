<?php

namespace App\Http\Controllers;

use App\Models\BospDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BospDocumentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $jenisBos = $request->query('jenis_bos');
        $tahun = $request->query('tahun');

        $documents = BospDocument::when($search, function ($query, $search) {
            return $query->where('nama_dokumen', 'like', "%{$search}%")
                         ->orWhere('keterangan', 'like', "%{$search}%");
        })->when($jenisBos, function ($query, $jenisBos) {
            return $query->where('jenis_bos', $jenisBos);
        })->when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        })->latest()->paginate(15);

        return view('bosp_documents.index', compact('documents', 'search', 'jenisBos', 'tahun'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_bos' => 'required|in:Reguler,Kinerja,Daerah,Afirmasi',
            'tahun' => 'required|numeric|digits:4',
            'tahap' => 'nullable|string|max:100',
            'nama_dokumen' => 'required|string|max:255',
            'file_rkas' => 'nullable|file|mimes:pdf|max:10240',
            'file_spj' => 'nullable|file|mimes:pdf|max:20480',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('file_rkas')) {
            $validated['file_rkas_path'] = $request->file('file_rkas')->store('bosp/rkas', 'public');
        }

        if ($request->hasFile('file_spj')) {
            $validated['file_spj_path'] = $request->file('file_spj')->store('bosp/spj', 'public');
        }

        BospDocument::create($validated);

        return redirect()->back()->with('success', 'Dokumen BOSP berhasil ditambahkan.');
    }

    public function update(Request $request, BospDocument $bospDocument)
    {
        $validated = $request->validate([
            'jenis_bos' => 'required|in:Reguler,Kinerja,Daerah,Afirmasi',
            'tahun' => 'required|numeric|digits:4',
            'tahap' => 'nullable|string|max:100',
            'nama_dokumen' => 'required|string|max:255',
            'file_rkas' => 'nullable|file|mimes:pdf|max:10240',
            'file_spj' => 'nullable|file|mimes:pdf|max:20480',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('file_rkas')) {
            if ($bospDocument->file_rkas_path && Storage::disk('public')->exists($bospDocument->file_rkas_path)) {
                Storage::disk('public')->delete($bospDocument->file_rkas_path);
            }
            $validated['file_rkas_path'] = $request->file('file_rkas')->store('bosp/rkas', 'public');
        }

        if ($request->hasFile('file_spj')) {
            if ($bospDocument->file_spj_path && Storage::disk('public')->exists($bospDocument->file_spj_path)) {
                Storage::disk('public')->delete($bospDocument->file_spj_path);
            }
            $validated['file_spj_path'] = $request->file('file_spj')->store('bosp/spj', 'public');
        }

        $bospDocument->update($validated);

        return redirect()->back()->with('success', 'Dokumen BOSP berhasil diperbarui.');
    }

    public function destroy(BospDocument $bospDocument)
    {
        if ($bospDocument->file_rkas_path && Storage::disk('public')->exists($bospDocument->file_rkas_path)) {
            Storage::disk('public')->delete($bospDocument->file_rkas_path);
        }

        if ($bospDocument->file_spj_path && Storage::disk('public')->exists($bospDocument->file_spj_path)) {
            Storage::disk('public')->delete($bospDocument->file_spj_path);
        }

        $bospDocument->delete();

        return redirect()->back()->with('success', 'Dokumen BOSP berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::latest()->get();
        return view('templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:doc,docx,xls,xlsx,pdf,zip,rar|max:20480',
        ], [
            'title.required' => 'Judul template wajib diisi.',
            'file.required' => 'File template wajib dipilih.',
            'file.mimes' => 'Format file harus berupa Word (doc, docx), Excel (xls, xlsx), PDF, ZIP, atau RAR.',
            'file.max' => 'Ukuran file maksimal adalah 20 MB.',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = strtoupper($file->getClientOriginalExtension());
            $path = $file->store('templates', 'public');

            Template::create([
                'title' => $request->title,
                'category' => $request->category ?? 'Umum',
                'description' => $request->description,
                'file_path' => $path,
                'original_name' => $originalName,
                'file_extension' => $extension,
            ]);

            return back()->with('success', 'Template berkas berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah berkas.');
    }

    public function download(Template $template)
    {
        if (Storage::disk('public')->exists($template->file_path)) {
            return Storage::disk('public')->download($template->file_path, $template->original_name);
        }

        return back()->with('error', 'File fisik tidak ditemukan di penyimpanan server.');
    }

    public function destroy(Template $template)
    {
        if (Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }

        $template->delete();

        return back()->with('success', 'Template berhasil dihapus.');
    }
}
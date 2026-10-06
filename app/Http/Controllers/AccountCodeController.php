<?php

namespace App\Http\Controllers;

use App\Models\AccountCode;
use Illuminate\Http\Request;

class AccountCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $accountCodes = AccountCode::when($search, function ($query, $search) {
            return $query->where('kode_rekening', 'like', "%{$search}%")
                         ->orWhere('nama_rekening', 'like', "%{$search}%");
        })->latest()->paginate(15);

        return view('account_codes.index', compact('accountCodes', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_rekening' => 'required|string|max:100|unique:account_codes,kode_rekening',
            'nama_rekening' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        AccountCode::create($validated);

        return redirect()->back()->with('success', 'Kode Rekening berhasil ditambahkan.');
    }

    public function update(Request $request, AccountCode $accountCode)
    {
        $validated = $request->validate([
            'kode_rekening' => 'required|string|max:100|unique:account_codes,kode_rekening,' . $accountCode->id,
            'nama_rekening' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $accountCode->update($validated);

        return redirect()->back()->with('success', 'Kode Rekening berhasil diperbarui.');
    }

    public function destroy(AccountCode $accountCode)
    {
        $accountCode->delete();

        return redirect()->back()->with('success', 'Kode Rekening berhasil dihapus.');
    }
}
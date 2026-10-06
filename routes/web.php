<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolProfileController;
use App\Http\Controllers\AccountCodeController;
use App\Http\Controllers\ActivityCodeController;
use App\Http\Controllers\BospDocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpjController;
use App\Http\Controllers\TemplateController;

Route::get('/', function () {
    return redirect()->route('spjs.index');
});
// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Profil Sekolah
Route::get('/school-profile', [SchoolProfileController::class, 'index'])->name('school-profile.index');
Route::post('/school-profile', [SchoolProfileController::class, 'storeOrUpdate'])->name('school-profile.store');

// Master Kode Rekening & Kegiatan
Route::resource('account-codes', AccountCodeController::class)->except(['create', 'show', 'edit']);
Route::resource('activity-codes', ActivityCodeController::class)->except(['create', 'show', 'edit']);

// Arsip Dokumen BOSP
Route::resource('bosp-documents', BospDocumentController::class)->except(['create', 'show', 'edit']);

// Route Cetak Kuitansi SPJ
Route::get('/spjs/{spj}/kwitansi-a2', [SpjController::class, 'kwitansiA2'])->name('spjs.kwitansi-a2');
Route::get('/spjs/{spj}/kwitansi-a2-bpu', [SpjController::class, 'kwitansiA2Bpu'])->name('spjs.kwitansi-a2-bpu');
Route::get('/spjs/{spj}/kwitansi-umum', [SpjController::class, 'kwitansiUmum'])->name('spjs.kwitansi-umum');
Route::get('/spjs/{spj}/kwitansi-umum-bpu', [SpjController::class, 'kwitansiUmumBpu'])->name('spjs.kwitansi-umum-bpu');
Route::get('/spjs-print-bundle', [SpjController::class, 'printBundle'])->name('spjs.print-bundle');

// CRUD Transaksi SPJ
Route::resource('spjs', SpjController::class)->except(['show']);

// Download Template
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
Route::get('/templates/{template}/download', [TemplateController::class, 'download'])->name('templates.download');
Route::delete('/templates/{template}', [TemplateController::class, 'destroy'])->name('templates.destroy');
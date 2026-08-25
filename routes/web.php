<?php

use App\Http\Controllers\CekStatusController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KonfirmasiTransferController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/informasi/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/formulir-pendaftaran', [FormulirController::class, 'create'])->name('formulir-pendaftaran.create');
Route::post('/formulir-pendaftaran', [FormulirController::class, 'store'])->name('formulir-pendaftaran.store');
Route::get('/formulir-pendaftaran/sukses/{noPendaftaran}', [FormulirController::class, 'sukses'])->name('formulir-pendaftaran.sukses');

Route::get('/konfirmasi-transfer', [KonfirmasiTransferController::class, 'create'])->name('konfirmasi-transfer.create');
Route::post('/konfirmasi-transfer', [KonfirmasiTransferController::class, 'store'])->name('konfirmasi-transfer.store');
Route::get('/konfirmasi-transfer/sukses/{pembayaran}', [KonfirmasiTransferController::class, 'sukses'])->name('konfirmasi-transfer.sukses');

Route::get('/cek-status', [CekStatusController::class, 'index'])->name('cek-status.index');

Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');

Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');

Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');
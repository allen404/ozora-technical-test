<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/halaman_utama', function () {
    return view('main');
});

Route::resource('barang', BarangController::class);

Route::get('/', [BarangController::class, 'index'])->name('barang');
Route::get('/barangv2', [BarangController::class, 'indexv2'])->name('barangv2');

Route::post('/barang/update/',[BarangController::class, 'updateBarang'])->name('barang.updatebarang');
Route::post('/barang/search/',[BarangController::class, 'searchBarang'])->name('barang.search');

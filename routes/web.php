<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;


Route::get('/', function () {
    return view('welcome');
});

// Catgeories routes
Route::get('/category', [CategoriesController::class, 'index'])->name('category.index'); // Tampilkan semua kategori 
Route::get('/category/create', [CategoriesController::class, 'create'])->name('category.create'); // Form tambah kategori
Route::post('/category', [CategoriesController::class, 'store'])->name('category.store'); // Simpan kategori baru
Route::get('/category/{id}/edit', [CategoriesController::class, 'edit'])->name('category.edit'); // Form edit kategori
Route::put('/category/{id}', [CategoriesController::class, 'update'])->name('category.update'); // Update kategori
Route::delete('/category/{id}', [CategoriesController::class, 'destroy'])->name('category.destroy'); // Hapus kategori

// Products routes
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
Route::get('/products/{id}/edit', [ProductsController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');
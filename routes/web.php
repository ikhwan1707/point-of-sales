<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsersController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/midtrans-test', function () {
    \Midtrans\Config::$serverKey = config('midtrans.serverKey');
    \Midtrans\Config::$isProduction = config('midtrans.isProduction');

    try {
        return 'Koneksi ke Midtrans berhasil (sandbox)!';
    } catch (Exception $e) {
        return 'Koneksi gagal: ' . $e->getMessage();
    }
});


// Catgeories routes
Route::get('/category', [CategoriesController::class, 'index'])->name('category.index'); // Tampilkan semua kategori 
Route::get('/category/create', [CategoriesController::class, 'create'])->name('category.create'); // Form tambah kategori
Route::post('/category', [CategoriesController::class, 'store'])->name('category.store'); // Simpan kategori baru
Route::get('/category/{id}/edit', [CategoriesController::class, 'edit'])->name('category.edit'); // Form edit kategori
Route::put('/category/{id}', [CategoriesController::class, 'update'])->name('category.update'); // Update kategori
Route::delete('/category/{id}', [CategoriesController::class, 'destroy'])->name('category.destroy'); // Hapus kategori

//customers routes
Route::get('/customer', [CustomersController::class, 'index'])->name('customer.index');
Route::get('/customer/create', [CustomersController::class, 'create'])->name('customers.create');
Route::post('/customer', [CustomersController::class, 'store'])->name('customers.store');
Route::get('/customer/{id}/edit', [CustomersController::class, 'edit'])->name('customers.edit');
Route::put('/customer/{id}', [CustomersController::class, 'update'])->name('customers.update');
Route::delete('/customer/{id}', [CustomersController::class, 'destroy'])->name('customers.destroy');


//Products Route
Route::get('/products', [ProductsController::class, 'index'])->name('products.index'); //Untuk menampilkan data products
Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
Route::get('/products/{id}/edit', [ProductsController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');

//Users
Route::get('/users', [UsersController::class, 'index'])->name('user.index'); //Untuk menampilkan data 
Route::get('/users/create', [UsersController::class, 'create'])->name('user.create');
Route::post('/users', [UsersController::class, 'store'])->name('user.store');
Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('user.edit');
Route::put('/users/{id}', [UsersController::class, 'update'])->name('user.update');
Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('user.destroy');

//Transaksi
Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');
Route::get('/transactions/create', [TransactionsController::class, 'create'])->name('transactions.create');
Route::post('/transactions', [TransactionsController::class, 'store'])->name('transactions.store');
Route::get('/transactions/{id}/show', [TransactionsController::class, 'show'])->name('transactions.show');
Route::delete('/transactions/{id}', [TransactionsController::class, 'destroy'])->name('transactions.destroy');
Route::get('/transactions/{id}/print', [TransactionsController::class, 'print'])->name('transactions.print');
Route::get('/transactions/payment/{transaction_code}', [TransactionsController::class, 'payment'])->name('transactions.payment');
Route::post('/transactions/callback', [TransactionsController::class, 'callback'])->name('transactions.callback');
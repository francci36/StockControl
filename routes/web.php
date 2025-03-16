<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

use Illuminate\Support\Facades\DB;

Route::get('/list-tables', function () {
    $tables = DB::select('SHOW TABLES');
    return response()->json($tables);
});
// Dans routes/web.php
Route::get('/test-update-stock/{orderId}', function ($orderId) {
    $order = \App\Models\Order::findOrFail($orderId);
    $order->updateStock(true);
    return 'Stock updated';
});


// Routes pour les produits
Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create/{supplier_id}', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Routes pour le tableau de bord et les autres fonctionnalités
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/transactions-data', [DashboardController::class, 'transactionsData'])->name('dashboard.transactionsData');

    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/api/stock-data', [DashboardController::class, 'getStockData']);
    Route::get('/dashboard/stock-data', [DashboardController::class, 'getStockData']);

    // routes/web.php
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/dashboard/transactions-data', [DashboardController::class, 'transactionsData']);

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create/{supplier_id}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::patch('/orders/{id}/updateStatus', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::patch('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
});

// Routes d'authentification
Route::middleware(['guest'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Route de déconnexion
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

// Routes de profil
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

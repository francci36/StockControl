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
use App\Http\Controllers\SalesController;

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
    Route::get('/api/products/{supplier_id}', [OrderController::class, 'getProductsBySupplier']);
});

// Routes pour le tableau de bord et les autres fonctionnalités
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/transactions-data', [DashboardController::class, 'transactionsData'])->name('dashboard.transactionsData');

    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/{stock}/edit', [StockController::class, 'edit'])->name('stocks.edit');
    Route::put('/stocks/{stock}', [StockController::class, 'update'])->name('stocks.update');
    Route::delete('/stocks/{stock}', [StockController::class, 'destroy'])->name('stocks.destroy');
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
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // routes/suppliers.php
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::patch('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::post('/suppliers/store-with-products', [SupplierController::class, 'storeWithProducts'])->name('suppliers.storeWithProducts');
    
    // routes/rapports.php
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // GET for displaying the profile page
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // PATCH for updating the profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // DELETE for deleting the account
});
// routes de ventes
// Middleware pour protéger les routes
Route::middleware(['auth'])->group(function () {
    // Routes basiques RESTful pour les ventes
    Route::resource('sales', SalesController::class);

    // Routes spécifiques si nécessaire
    Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create'); // Formulaire de création de vente
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store'); // Enregistrement d'une vente
    Route::get('/sales/{sale}', [SalesController::class, 'show'])->name('sales.show'); // Voir une vente spécifique
    Route::get('/sales/{sale}/edit', [SalesController::class, 'edit'])->name('sales.edit'); // Formulaire d'édition
    Route::put('/sales/{sale}', [SalesController::class, 'update'])->name('sales.update'); // Mise à jour d'une vente
    Route::delete('/sales/{sale}', [SalesController::class, 'destroy'])->name('sales.destroy'); // Suppression d'une vente
});

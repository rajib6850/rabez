<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RABEZ Storefront Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [StorefrontController::class, 'product'])->name('product.detail');
Route::get('/product.html', function (\Illuminate\Http\Request $request) {
    $id = $request->query('id');
    if ($id) {
        $product = \App\Models\Product::where('id', $id)->orWhere('sku', $id)->orWhere('slug', $id)->first();
        if ($product) {
            return redirect()->route('product.detail', $product->slug ?: $product->id);
        }
        return redirect('/product/' . $id);
    }
    return redirect()->route('shop');
});
Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StorefrontController::class, 'processCheckout'])->name('checkout.process');
Route::post('/api/apply-coupon', [StorefrontController::class, 'applyCoupon'])->name('storefront.apply_coupon');
Route::get('/api/coupons/active-status', [StorefrontController::class, 'activeCouponsStatus'])->name('storefront.coupons_status');
Route::get('/track-order', [StorefrontController::class, 'trackOrder'])->name('track-order');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login, Registration, Logout)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/admin/login', fn () => redirect()->route('login'));

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| RABEZ Admin Dashboard & CRUD API Routes (Protected by Auth & Admin Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Orders
    Route::get('/api/orders', [AdminController::class, 'getOrders'])->name('admin.orders.list');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::post('/orders/{id}/update', [AdminController::class, 'updateOrder'])->name('admin.orders.update');
    Route::post('/orders/{id}/update-full', [AdminController::class, 'updateOrderFull'])->name('admin.orders.update_full');
    Route::delete('/orders/{id}', [AdminController::class, 'deleteOrder'])->name('admin.orders.delete');
    Route::post('/orders/bulk-delete', [AdminController::class, 'bulkDeleteOrders'])->name('admin.orders.bulk_delete');
    Route::post('/orders/{id}/book-courier', [AdminController::class, 'bookCourier'])->name('admin.orders.book');

    // Products
    Route::get('/api/products', [AdminController::class, 'getProducts'])->name('admin.products.list');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::post('/products/{id}/update', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::post('/products/bulk-delete', [AdminController::class, 'bulkDeleteProducts'])->name('admin.products.bulk_delete');

    // Categories
    Route::get('/api/categories', [AdminController::class, 'getCategories'])->name('admin.categories.list');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::post('/categories/{id}/update', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
    Route::post('/categories/bulk-delete', [AdminController::class, 'bulkDeleteCategories'])->name('admin.categories.bulk_delete');

    // Customers
    Route::get('/api/customers', [AdminController::class, 'getCustomers'])->name('admin.customers.list');
    Route::post('/customers/update', [AdminController::class, 'updateCustomer'])->name('admin.customers.update');
    Route::post('/customers/delete', [AdminController::class, 'deleteCustomer'])->name('admin.customers.delete');
    Route::post('/customers/bulk-delete', [AdminController::class, 'bulkDeleteCustomers'])->name('admin.customers.bulk_delete');
    Route::post('/api/check-phone', [AdminController::class, 'checkPhone'])->name('admin.check_phone');

    // Users (Team, Staff & System Roles)
    Route::get('/api/users', [AdminController::class, 'getUsers'])->name('admin.users.list');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/users/bulk-delete', [AdminController::class, 'bulkDeleteUsers'])->name('admin.users.bulk_delete');

    // Settings
    Route::post('/settings', [AdminController::class, 'saveSettings'])->name('admin.settings.save');

    // Coupons Management
    Route::get('/api/coupons', [AdminController::class, 'getCoupons'])->name('admin.coupons.list');
    Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('admin.coupons.store');
    Route::put('/coupons/{id}', [AdminController::class, 'updateCoupon'])->name('admin.coupons.update');
    Route::post('/coupons/{id}', [AdminController::class, 'updateCoupon']);
    Route::post('/coupons/{id}/toggle', [AdminController::class, 'toggleCoupon'])->name('admin.coupons.toggle');
    Route::delete('/coupons/{id}', [AdminController::class, 'deleteCoupon'])->name('admin.coupons.delete');

    // Media Gallery & WebP Engine
    Route::get('/api/media', [AdminController::class, 'getMedia'])->name('admin.media.list');
    Route::post('/media/upload', [AdminController::class, 'uploadMedia'])->name('admin.media.upload');
    Route::delete('/media/{id}', [AdminController::class, 'deleteMedia'])->name('admin.media.delete');
    Route::post('/media/bulk-delete', [AdminController::class, 'bulkDeleteMedia'])->name('admin.media.bulk_delete');
});

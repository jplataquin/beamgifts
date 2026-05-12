<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\StoreController;

Route::get('/', function () {
    return view('welcome');
});

// Public Content Pages
Route::get('/about', [App\Http\Controllers\PageController::class, 'about'])->name('page.about');
Route::get('/terms', [App\Http\Controllers\PageController::class, 'terms'])->name('page.terms');
Route::get('/privacy', [App\Http\Controllers\PageController::class, 'privacy'])->name('page.privacy');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/login', [App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Partner Management
        Route::resource('partners', App\Http\Controllers\Admin\PartnerController::class);
        Route::patch('partners/{partner}/ban', [App\Http\Controllers\Admin\PartnerController::class, 'toggleBan'])->name('partners.ban');

        // City Management
        Route::resource('cities', App\Http\Controllers\Admin\CityController::class);

        // Category Management
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

        // Product Management
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::patch('products/{product}/ban', [App\Http\Controllers\Admin\ProductController::class, 'toggleBan'])->name('products.ban');

        // Site Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });
});

Route::group(['prefix' => 'partner', 'as' => 'partner.'], function () {
    Route::get('/login', [App\Http\Controllers\Partner\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Partner\LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Partner\LoginController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'auth:partner'], function () {
        Route::get('/dashboard', [App\Http\Controllers\Partner\DashboardController::class, 'index'])->name('dashboard');
        
        // Store Management (Singleton)
        Route::singleton('store', App\Http\Controllers\Partner\StoreController::class);
        Route::post('store/upload-chunk', [App\Http\Controllers\Partner\StoreController::class, 'uploadChunk'])->name('store.upload_chunk');
        
        // Branch Management
        Route::resource('branches', App\Http\Controllers\Partner\BranchController::class);

        // Product Management
        Route::resource('products', App\Http\Controllers\Partner\ProductController::class);
        Route::post('products/upload-chunk', [App\Http\Controllers\Partner\ProductController::class, 'uploadChunk'])->name('products.upload_chunk');

        // Manager Management
        Route::resource('managers', App\Http\Controllers\Partner\ManagerController::class);

        // Voucher Redemption
        Route::get('/vouchers', [App\Http\Controllers\Partner\VoucherController::class, 'index'])->name('vouchers.index');
        Route::get('/vouchers/scan', [App\Http\Controllers\Partner\VoucherController::class, 'scan'])->name('vouchers.scan');
        Route::get('/vouchers/scan/{token}', [App\Http\Controllers\Partner\VoucherController::class, 'scanResult'])->name('vouchers.scan.result');
        Route::patch('/vouchers/{voucher}/claim', [App\Http\Controllers\Partner\VoucherController::class, 'claim'])->name('vouchers.claim');
    });
});

// Branch Manager Routes
Route::group(['prefix' => 'manager', 'as' => 'manager.'], function () {
    Route::get('/login', [App\Http\Controllers\Manager\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Manager\Auth\LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Manager\Auth\LoginController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'auth:manager'], function () {
        // Password Change Routes (No force middleware here)
        Route::get('/password/change', [App\Http\Controllers\Manager\Auth\PasswordController::class, 'edit'])->name('password.edit');
        Route::put('/password/change', [App\Http\Controllers\Manager\Auth\PasswordController::class, 'update'])->name('password.update');

        Route::group(['middleware' => 'manager.force_password_change'], function () {
            Route::get('/scan', [App\Http\Controllers\Manager\VoucherController::class, 'scan'])->name('vouchers.scan');
            Route::get('/scan/{token}', [App\Http\Controllers\Manager\VoucherController::class, 'scanResult'])->name('vouchers.scan.result');
            Route::patch('/vouchers/{voucher}/claim', [App\Http\Controllers\Manager\VoucherController::class, 'claim'])->name('vouchers.claim');
            Route::get('/transactions', [App\Http\Controllers\Manager\VoucherController::class, 'transactions'])->name('vouchers.transactions');
        });
    });
});

// Gifter Authentication
Route::get('/login', [App\Http\Controllers\Auth\GifterAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\GifterAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [App\Http\Controllers\Auth\GifterAuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\GifterAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [App\Http\Controllers\Auth\GifterAuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth:web'], function () {
    Route::get('/profile', [App\Http\Controllers\Auth\GifterAuthController::class, 'profile'])->name('profile');
    Route::get('/my-gifts', [App\Http\Controllers\VoucherController::class, 'index'])->name('my-gifts');
    Route::post('/my-gifts/{voucher}/message', [App\Http\Controllers\VoucherController::class, 'updateMessage'])->name('vouchers.update_message');
    Route::post('/my-gifts/upload-chunk', [App\Http\Controllers\VoucherController::class, 'uploadChunk'])->name('vouchers.upload_chunk');
    
    Route::get('/debug-auth', function() {
        return response()->json([
            'id' => Auth::id(),
            'guard' => Auth::getDefaultDriver(),
            'check' => Auth::check(),
            'user' => Auth::user()
        ]);
    });
    
    // Order History
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('my-orders');
    Route::get('/my-orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('my-orders.show');
});

Route::group(['prefix' => '{city_slug}', 'middleware' => 'city.context'], function () {
    Route::get('/', [CityController::class, 'index'])->name('city.home');
    Route::get('/stores/{store_slug}', [StoreController::class, 'show'])->name('store.show');
    Route::get('/products/{product_slug}', [CityController::class, 'showProduct'])->name('product.show');

    // Cart Routes
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{productId}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{productId}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    // Checkout Routes
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth:web');
    Route::get('/checkout/callback', [App\Http\Controllers\CheckoutController::class, 'callback'])->name('checkout.callback');
});

// HitPay Webhook (External)
Route::post('/hitpay/webhook', [App\Http\Controllers\CheckoutController::class, 'webhook'])->name('checkout.webhook');

// Voucher View (Public)
Route::get('/v/{token}', [App\Http\Controllers\VoucherController::class, 'show'])->name('voucher.show');

<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockReceiptController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\ReviewController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROUTE AUTENTIKASI (PUBLIC)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->name('branches.')->prefix('branches')->group(function () {
        Route::get('/', [BranchController::class, 'index'])
            ->name('index')
            ->middleware('permission:branches.view');

        Route::get('/create', [BranchController::class, 'create'])
            ->name('create')
            ->middleware('permission:branches.create');

        Route::post('/', [BranchController::class, 'store'])
            ->name('store')
            ->middleware('permission:branches.create');

        Route::get('/{id}/edit', [BranchController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:branches.update');

        Route::put('/{id}', [BranchController::class, 'update'])
            ->name('update')
            ->middleware('permission:branches.update');

        Route::delete('/{id}', [BranchController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:branches.delete');

        Route::get('/{id}', [BranchController::class, 'show'])
            ->name('show')
            ->middleware('permission:branches.view');
});

Route::middleware(['auth'])->name('categories.')->prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index')->middleware('permission:categories.view');
    Route::get('/create', [CategoryController::class, 'create'])->name('create')->middleware('permission:categories.create');
    Route::post('/', [CategoryController::class, 'store'])->name('store')->middleware('permission:categories.create');
    Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit')->middleware('permission:categories.update');
    Route::put('/{id}', [CategoryController::class, 'update'])->name('update')->middleware('permission:categories.update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy')->middleware('permission:categories.delete');
    Route::get('/{id}', [CategoryController::class, 'show'])->name('show')->middleware('permission:categories.view');
});

Route::middleware(['auth'])->name('roles.')->prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('permission:roles.view');
    Route::get('/create', [RoleController::class, 'create'])->name('create')->middleware('permission:roles.create');
    Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('permission:roles.create');
    Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit')->middleware('permission:roles.update');
    Route::put('/{id}', [RoleController::class, 'update'])->name('update')->middleware('permission:roles.update');
    Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy')->middleware('permission:roles.delete');
    Route::get('/{id}', [RoleController::class, 'show'])->name('show')->middleware('permission:roles.view');
});

Route::middleware(['auth'])->name('permissions.')->prefix('permissions')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('index')->middleware('permission:permissions.view');
    Route::get('/create', [PermissionController::class, 'create'])->name('create')->middleware('permission:permissions.create');
    Route::post('/', [PermissionController::class, 'store'])->name('store')->middleware('permission:permissions.create');
    Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit')->middleware('permission:permissions.update');
    Route::put('/{id}', [PermissionController::class, 'update'])->name('update')->middleware('permission:permissions.update');
    Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy')->middleware('permission:permissions.delete');
    Route::get('/{id}', [PermissionController::class, 'show'])->name('show')->middleware('permission:permissions.view');
});

Route::middleware(['auth'])->name('users.')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index')->middleware('permission:users.view');
    Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('permission:users.create');
    Route::post('/', [UserController::class, 'store'])->name('store')->middleware('permission:users.create');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit')->middleware('permission:users.update');
    Route::put('/{id}', [UserController::class, 'update'])->name('update')->middleware('permission:users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:users.delete');
    Route::get('/{id}', [UserController::class, 'show'])->name('show')->middleware('permission:users.view');
});

Route::middleware(['auth','permission:products.create'])
    ->prefix('products/import')
    ->name('products.import.')
    ->group(function () {
        Route::get('/', [ProductController::class, 'importForm'])->name('form');
        Route::post('/preview', [ProductController::class, 'importPreview'])->name('preview');
        Route::post('/confirm', [ProductController::class, 'importProcess'])->name('process');
    });

Route::middleware('auth')->group(function () {

    Route::get('/products/import/template',
        [ProductController::class, 'downloadImportTemplate']
    )->name('products.import.template');

    Route::get('/products/import/example-images',
        [ProductController::class, 'downloadImportImagesExample']
    )->name('products.import.images_example');

});

Route::middleware(['auth'])->name('products.')->prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index')->middleware('permission:products.view');
    Route::get('/create', [ProductController::class, 'create'])->name('create')->middleware('permission:products.create');
    Route::post('/', [ProductController::class, 'store'])->name('store')->middleware('permission:products.create');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit')->middleware('permission:products.update');
    Route::put('/{id}', [ProductController::class, 'update'])->name('update')->middleware('permission:products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy')->middleware('permission:products.delete');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show')->middleware('permission:products.view');

    // extra ajax routes
    Route::post('/{id}/images/upload', [ProductController::class, 'uploadImage'])->name('images.upload')->middleware('permission:products.update');
    Route::delete('/images/{id}', [ProductController::class, 'deleteImage'])->name('images.destroy')->middleware('permission:products.update');

    Route::post('/{id}/images/reorder', [ProductController::class, 'reorderImages'])->name('products.images.reorder');
    Route::post('/images/{id}/set-main', [ProductController::class, 'setMainImage'])->name('products.images.set_main');
    Route::delete('/images/{id}', [ProductController::class, 'deleteImage'])->name('products.images.destroy'); // if not present
});

Route::middleware(['auth'])->name('stock_receipts.')->prefix('stock-receipts')->group(function () {
    // Ajax helper to lookup variants
    Route::get('/variant-search', [StockReceiptController::class, 'variantSearch'])->name('variant.search')->middleware('permission:stock_receipts.create');

    Route::get('/', [StockReceiptController::class, 'index'])->name('index')->middleware('permission:stock_receipts.view');
    Route::get('/create', [StockReceiptController::class, 'create'])->name('create')->middleware('permission:stock_receipts.create');
    Route::post('/', [StockReceiptController::class, 'store'])->name('store')->middleware('permission:stock_receipts.create');
    Route::get('/{id}', [StockReceiptController::class, 'show'])->name('show')->middleware('permission:stock_receipts.view');
    Route::get('/{id}/edit', [StockReceiptController::class, 'edit'])->name('edit')->middleware('permission:stock_receipts.update');
    Route::put('/{id}', [StockReceiptController::class, 'update'])->name('update')->middleware('permission:stock_receipts.update');
    Route::delete('/{id}', [StockReceiptController::class, 'destroy'])->name('destroy')->middleware('permission:stock_receipts.delete');
});

Route::middleware(['auth'])->name('customers.')->prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index')->middleware('permission:customers.view');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('show')->middleware('permission:customers.view');
    // toggle aktif / non-aktif via AJAX (PUT)
    Route::put('/{id}/toggle-active', [CustomerController::class, 'toggleActive'])->name('toggleActive')->middleware('permission:customers.activate');
});

Route::middleware(['auth'])->name('units.')->prefix('units')->group(function () {
    Route::get('/', [UnitController::class, 'index'])->name('index')->middleware('permission:units.view');
    Route::get('/create', [UnitController::class, 'create'])->name('create')->middleware('permission:units.create');
    Route::post('/', [UnitController::class, 'store'])->name('store')->middleware('permission:units.create');
    Route::get('/{id}/edit', [UnitController::class, 'edit'])->name('edit')->middleware('permission:units.update');
    Route::put('/{id}', [UnitController::class, 'update'])->name('update')->middleware('permission:units.update');
    Route::delete('/{id}', [UnitController::class, 'destroy'])->name('destroy')->middleware('permission:units.delete');
    Route::get('/{id}', [UnitController::class, 'show'])->name('show')->middleware('permission:units.view');
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {

        // Orders management (backoffice)
        Route::middleware('can:orders.view')->group(function () {
            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
            Route::get('orders/{id}/print', [OrderController::class, 'print'])
                ->name('orders.print');
        });

        Route::middleware('can:orders.manage')->group(function () {
            Route::post('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
            Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        });
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::middleware('can:home_banners.view')->group(function () {
        Route::get('home-banners', [HomeBannerController::class, 'index'])->name('home-banners.index');
        Route::get('home-banners/create', [HomeBannerController::class, 'create'])->name('home-banners.create');
        Route::get('home-banners/{id}/edit', [HomeBannerController::class, 'edit'])->name('home-banners.edit');
    });

    Route::middleware('can:home_banners.manage')->group(function () {
        Route::post('home-banners', [HomeBannerController::class, 'store'])->name('home-banners.store');
        Route::put('home-banners/{id}', [HomeBannerController::class, 'update'])->name('home-banners.update');
        Route::delete('home-banners/{id}', [HomeBannerController::class, 'destroy'])->name('home-banners.destroy');
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::middleware('can:reviews.view')->group(function () {
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
    });

    Route::middleware('can:reviews.manage')->group(function () {
        Route::patch('reviews/{id}/status', [ReviewController::class, 'updateStatus'])->name('reviews.update-status');
        Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::delete('review-images/{id}', [ReviewController::class, 'destroyImage'])->name('review-images.destroy');
    });

});


/*
|--------------------------------------------------------------------------
| FALLBACK 404
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

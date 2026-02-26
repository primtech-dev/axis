<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentController;
use App\Http\Controllers\SupplierPayableReportController;
use App\Http\Controllers\CustomerReceivableReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalePaymentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpensePaymentController;

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

    Route::get('/', [UserController::class, 'index'])
        ->name('index')
        ->middleware('permission:users.view');

    Route::get('/create', [UserController::class, 'create'])
        ->name('create')
        ->middleware('permission:users.create');

    Route::post('/', [UserController::class, 'store'])
        ->name('store')
        ->middleware('permission:users.create');

    Route::get('/{id}/edit', [UserController::class, 'edit'])
        ->name('edit')
        ->middleware('permission:users.update');

    Route::put('/{id}', [UserController::class, 'update'])
        ->name('update')
        ->middleware('permission:users.update');

    Route::delete('/{id}', [UserController::class, 'destroy'])
        ->name('destroy')
        ->middleware('permission:users.delete');

    Route::get('/{id}', [UserController::class, 'show'])
        ->name('show')
        ->middleware('permission:users.view');
});

//Route::middleware('auth')->group(function () {
//
//    Route::get('/products/import/template',
//        [ProductController::class, 'downloadImportTemplate']
//    )->name('products.import.template');
//
//    Route::get('/products/import/example-images',
//        [ProductController::class, 'downloadImportImagesExample']
//    )->name('products.import.images_example');
//
//});

Route::middleware(['auth'])->name('customers.')->prefix('customers')->group(function () {

    Route::get('/', [CustomerController::class, 'index'])
        ->name('index')
        ->middleware('permission:customers.view');

    Route::get('/create', [CustomerController::class, 'create'])
        ->name('create')
        ->middleware('permission:customers.create');

    Route::post('/', [CustomerController::class, 'store'])
        ->name('store')
        ->middleware('permission:customers.create');

    Route::get('/{id}', [CustomerController::class, 'show'])
        ->name('show')
        ->middleware('permission:customers.view');

    Route::get('/{id}/edit', [CustomerController::class, 'edit'])
        ->name('edit')
        ->middleware('permission:customers.update');

    Route::put('/{id}', [CustomerController::class, 'update'])
        ->name('update')
        ->middleware('permission:customers.update');

    Route::delete('/{id}', [CustomerController::class, 'destroy'])
        ->name('destroy')
        ->middleware('permission:customers.delete');

    Route::put('/{id}/toggle-active', [CustomerController::class, 'toggleActive'])
        ->name('toggleActive')
        ->middleware('permission:customers.update');
});

Route::middleware(['auth'])->prefix('suppliers')->name('suppliers.')->group(function () {

    Route::get('/', [SupplierController::class, 'index'])
        ->name('index')
        ->middleware('permission:suppliers.view');

    Route::get('/create', [SupplierController::class, 'create'])
        ->name('create')
        ->middleware('permission:suppliers.create');

    Route::post('/', [SupplierController::class, 'store'])
        ->name('store')
        ->middleware('permission:suppliers.create');

    Route::get('/{id}/edit', [SupplierController::class, 'edit'])
        ->name('edit')
        ->middleware('permission:suppliers.update');

    Route::put('/{id}', [SupplierController::class, 'update'])
        ->name('update')
        ->middleware('permission:suppliers.update');

    Route::delete('/{id}', [SupplierController::class, 'destroy'])
        ->name('destroy')
        ->middleware('permission:suppliers.delete');

    Route::put('/{id}/toggle-active', [SupplierController::class, 'toggleActive'])
        ->name('toggleActive')
        ->middleware('permission:suppliers.update');

    Route::get('/{id}', [SupplierController::class, 'show'])
        ->name('show');
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

Route::middleware(['auth'])
    ->prefix('products')
    ->name('products.')
    ->group(function () {

        Route::get('/', [ProductController::class, 'index'])
            ->name('index')
            ->middleware('permission:products.view');

        Route::get('/create', [ProductController::class, 'create'])
            ->name('create')
            ->middleware('permission:products.create');

        Route::post('/', [ProductController::class, 'store'])
            ->name('store')
            ->middleware('permission:products.create');

        Route::get('/{id}/edit', [ProductController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:products.update');

        Route::put('/{id}', [ProductController::class, 'update'])
            ->name('update')
            ->middleware('permission:products.update');

        Route::delete('/{id}', [ProductController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:products.delete');

        Route::put('/{id}/toggle-active', [ProductController::class, 'toggleActive'])
            ->name('toggleActive')
            ->middleware('permission:products.update');

        Route::get('/{id}', [ProductController::class, 'show'])
            ->name('show')
            ->middleware('permission:products.view');
    });

Route::middleware(['auth'])
    ->prefix('payment-methods')
    ->name('payment-methods.')
    ->group(function () {

        Route::get('/', [PaymentMethodController::class, 'index'])
            ->name('index')
            ->middleware('permission:payment_methods.view');

        Route::get('/create', [PaymentMethodController::class, 'create'])
            ->name('create')
            ->middleware('permission:payment_methods.create');

        Route::post('/', [PaymentMethodController::class, 'store'])
            ->name('store')
            ->middleware('permission:payment_methods.create');

        Route::get('/{id}', [PaymentMethodController::class, 'show'])
            ->name('show')
            ->middleware('permission:payment_methods.view');

        Route::get('/{id}/edit', [PaymentMethodController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:payment_methods.update');

        Route::put('/{id}', [PaymentMethodController::class, 'update'])
            ->name('update')
            ->middleware('permission:payment_methods.update');

        Route::delete('/{id}', [PaymentMethodController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:payment_methods.delete');

        Route::put('/{id}/toggle-active', [PaymentMethodController::class, 'toggleActive'])
            ->name('toggleActive')
            ->middleware('permission:payment_methods.update');
    });

Route::middleware(['auth'])
    ->prefix('purchases')
    ->name('purchases.')
    ->group(function () {

        Route::get('/', [PurchaseController::class, 'index'])
            ->middleware('permission:purchases.view')
            ->name('index');

        Route::get('/create', [PurchaseController::class, 'create'])
            ->middleware('permission:purchases.create')
            ->name('create');

        Route::post('/', [PurchaseController::class, 'store'])
            ->middleware('permission:purchases.create')
            ->name('store');

        Route::get('/{id}', [PurchaseController::class, 'show'])
            ->middleware('permission:purchases.view')
            ->name('show');

        Route::get('/{id}/print', [PurchaseController::class, 'print'])
            ->name('print')
            ->middleware('permission:purchases.view');

        Route::get('{purchase}/pay', [PurchasePaymentController::class, 'create'])
            ->name('pay.create');

        Route::post('{purchase}/pay', [PurchasePaymentController::class, 'store'])
            ->name('pay.store');

        Route::get(
            'purchase-payments/{payment}/print',
            [PurchasePaymentController::class, 'print']
        )
            ->name('payments.print');
    });


Route::middleware(['auth'])
    ->prefix('reports')
    ->name('reports.')
    ->group(function () {

        Route::get(
            'supplier-payables/pdf',
            [SupplierPayableReportController::class, 'pdf']
        )
            ->name('supplier-payables.pdf');

        Route::get(
            'supplier-payables/excel',
            [SupplierPayableReportController::class, 'excel']
        )
            ->name('supplier-payables.excel');

        Route::get(
            'supplier-payables',
            [SupplierPayableReportController::class, 'index']
        )
            ->name('supplier-payables.index');
    });

Route::prefix('reports')->group(function () {

    Route::get('customer-receivables',
        [CustomerReceivableReportController::class, 'index']
    )->name('reports.customer-receivables.index');

    Route::get('customer-receivables/pdf',
        [CustomerReceivableReportController::class, 'pdf']
    )->name('reports.customer-receivables.pdf');

    Route::get('customer-receivables/excel',
        [CustomerReceivableReportController::class, 'excel']
    )->name('reports.customer-receivables.excel');

});

Route::middleware(['auth'])
    ->prefix('stocks')
    ->name('stocks.')
    ->group(function () {

        Route::get('/', [StockController::class, 'index'])
            ->name('index');


        Route::get('{product}/card', [StockController::class, 'card'])
            ->name('card');

        Route::get('export/excel', [StockController::class, 'exportExcel'])
            ->name('export.excel');

        Route::get('{product}/card/export/excel', [StockController::class, 'exportCardExcel'])
            ->name('card.export.excel');
    });

Route::middleware(['auth'])
    ->prefix('accounts')
    ->name('accounts.')
    ->group(function () {

        Route::get('/', [AccountController::class, 'index'])
            ->middleware('permission:accounts.view')
            ->name('index');

        Route::get('/create', [AccountController::class, 'create'])
            ->middleware('permission:accounts.create')
            ->name('create');

        Route::post('/', [AccountController::class, 'store'])
            ->middleware('permission:accounts.create')
            ->name('store');

        Route::get('/{id}/edit', [AccountController::class, 'edit'])
            ->middleware('permission:accounts.update')
            ->name('edit');

        Route::put('/{id}', [AccountController::class, 'update'])
            ->middleware('permission:accounts.update')
            ->name('update');

        Route::delete('/{id}', [AccountController::class, 'destroy'])
            ->middleware('permission:accounts.delete')
            ->name('destroy');
    });


Route::middleware(['auth'])
    ->prefix('sales')
    ->name('sales.')
    ->group(function () {

        Route::get('/', [SaleController::class, 'index'])
            ->middleware('permission:sales.view')
            ->name('index');

        Route::get('/create', [SaleController::class, 'create'])
            ->middleware('permission:sales.create')
            ->name('create');

        Route::post('/', [SaleController::class, 'store'])
            ->middleware('permission:sales.create')
            ->name('store');

        Route::get('/{id}', [SaleController::class, 'show'])
            ->middleware('permission:sales.view')
            ->name('show');

        Route::get('/{id}/print', [SaleController::class, 'print'])
            ->middleware('permission:sales.view')
            ->name('print');

        // Route::get('{sale}/pay', [SalePaymentController::class, 'create'])
        //     ->name('pay.create');

        // Route::post('{sale}/pay', [SalePaymentController::class, 'store'])
        //     ->name('pay.store');
    });

Route::middleware(['auth'])
    ->prefix('sales')
    ->name('sales.')
    ->group(function () {

        Route::get('{sale}/pay', [SalePaymentController::class, 'create'])
            ->middleware('permission:customer_payments.create')
            ->name('pay.create');

        Route::post('{sale}/pay', [SalePaymentController::class, 'store'])
            ->middleware('permission:customer_payments.create')
            ->name('pay.store');

        Route::get(
            'sale-payments/{payment}/print',
            [SalePaymentController::class, 'print']
        )
            ->name('payments.print');
    });

Route::middleware(['auth'])
    ->prefix('journals')
    ->name('journals.')
    ->group(function () {

        Route::get('/', [JournalController::class, 'index'])
            ->middleware('permission:journals.view')
            ->name('index');

        Route::get('/export', [JournalController::class, 'export'])
            // ->middleware('permission:journals.export')
            ->name('export');
    });

Route::middleware(['auth'])
    ->prefix('expenses')
    ->name('expenses.')
    ->group(function () {

        Route::get('/', [ExpenseController::class, 'index'])
            ->middleware('permission:expenses.view')
            ->name('index');

        Route::get('/create', [ExpenseController::class, 'create'])
            ->middleware('permission:expenses.create')
            ->name('create');

        Route::post('/', [ExpenseController::class, 'store'])
            ->middleware('permission:expenses.create')
            ->name('store');

        Route::get('/{id}', [ExpenseController::class, 'show'])
            ->middleware('permission:expenses.view')
            ->name('show');

        Route::get('/{id}/print', [ExpenseController::class, 'print'])
            ->middleware('permission:expenses.view')
            ->name('print');
    });

Route::middleware(['auth'])
    ->prefix('expenses')
    ->name('expenses.')
    ->group(function () {

        Route::get('{expense}/pay', [ExpensePaymentController::class, 'create'])
            ->name('pay.create');

        Route::post('{expense}/pay', [ExpensePaymentController::class, 'store'])
            ->name('pay.store');
    });

Route::middleware(['auth'])
    ->prefix('expense-payments')
    ->name('expenses.payments.')
    ->group(function () {

        Route::get('{payment}/print', [ExpensePaymentController::class, 'print'])
            ->name('print');
    });

Route::middleware(['auth'])
    ->prefix('profit-loss')
    ->name('profit-loss.')
    ->group(function () {

        Route::get('/sales', [ProfitLossController::class, 'perSale'])
            ->middleware('permission:profit_loss.view')
            ->name('sales');

        Route::get('/customer', [ProfitLossController::class, 'perCustomer'])
            ->middleware('permission:profit_loss.view')
            ->name('customer');
    });

/*
|--------------------------------------------------------------------------
| FALLBACK 404
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

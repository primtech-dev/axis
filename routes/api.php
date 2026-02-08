<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PurchaseItemApiController;

Route::middleware('web')->group(function () {
    Route::get(
        'purchases/{purchase}/items',
        [PurchaseItemApiController::class, 'items']
    );
});

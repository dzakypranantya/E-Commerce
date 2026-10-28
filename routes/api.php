<?php

use App\Http\Controllers\Api\LayananController;
use Illuminate\Support\Facades\Route;

Route::apiResource('layanan', LayananController::class)->names([
    'index' => 'api.layanan.index',
    'store' => 'api.layanan.store',
    'show' => 'api.layanan.show',
    'update' => 'api.layanan.update',
    'destroy' => 'api.layanan.destroy',
]);
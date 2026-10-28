<?php

use App\Http\Controllers\LayananController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/layanan');

Route::resource('layanan', LayananController::class)->except('show');

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend routes (layout-based)
|--------------------------------------------------------------------------
*/

Route::view('/', 'calls.index');

Route::view('/calls', 'calls.index');
Route::view('/calls/create', 'calls.create');

Route::view('/dashboard', 'reports.dashboard');

Route::view('/admin/users', 'admin.users.index');

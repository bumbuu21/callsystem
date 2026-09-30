<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/nevtreh', [AuthController::class, 'createLogin'])->name('login');
    Route::post('/nevtreh', [AuthController::class, 'login'])->name('login.store');
    Route::get('/burtguuleh', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/burtguuleh', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/duudlaguud', [CallController::class, 'index'])->name('calls.index');
    Route::get('/duudlaga/shine', [CallController::class, 'create'])->name('calls.create');
    Route::post('/duudlaga', [CallController::class, 'store'])->name('calls.store');
    Route::get('/duudlaga/{call}', [CallController::class, 'show'])->name('calls.show');
    Route::patch('/duudlaga/{call}/huvaariulah', [CallController::class, 'assign'])->name('calls.assign');
    Route::patch('/duudlaga/{call}/shiideh', [CallController::class, 'resolve'])->name('calls.resolve');
    Route::patch('/duudlaga/{call}/setgegdel', [CallController::class, 'comment'])->name('calls.comment');
    Route::get('/admin/duudlaguud', [AdminController::class, 'calls'])->name('admin.calls');
    Route::get('/admin/tailan', [AdminController::class, 'report'])->name('admin.report');
    Route::get('/admin/duudlaguud/csv', [AdminController::class, 'exportCalls'])->name('admin.calls.export');
    Route::get('/admin/duudlaguud/pdf', [AdminController::class, 'pdfCalls'])->name('admin.calls.pdf');
    Route::get('/admin/duudlaguud/excel', [AdminController::class, 'excelCalls'])->name('admin.calls.excel');
    Route::get('/admin/hereglegchid', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/hereglegchid/shine', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/hereglegchid', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::patch('/admin/hereglegchid/{user}/tolov', [AdminController::class, 'toggleUser'])->name('admin.users.toggle');
    Route::delete('/admin/hereglegchid/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.logs');
    Route::post('/garah', [AuthController::class, 'logout'])->name('logout');
});

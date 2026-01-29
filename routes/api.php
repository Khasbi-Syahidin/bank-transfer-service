<?php

use App\Http\Controllers\Api\V1\BankController;
use App\Http\Controllers\Api\V1\TransferController;
use App\Http\Controllers\Api\V1\TransferScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
Route::get('/transfers/schedule', [TransferScheduleController::class, 'show'])->name('transfers.schedule');

Route::post('/transfers/execute', [TransferController::class, 'execute'])->name('transfers.execute');
Route::get('/transfers/{transferId}/status', [TransferController::class, 'check'])->name('transfers.status');

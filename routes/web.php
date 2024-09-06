<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RepairPDFController;


Route::get('/repairs/download-pdf', [RepairPDFController::class, 'downloadPdf'])->name('repairs.downloadPdf');
Route::get('/', function () {
    return view('welcome');
});

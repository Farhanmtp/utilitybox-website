<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PostcodeController;
use App\Http\Controllers\Api\Powwr\CompaniesController;
use App\Http\Controllers\Api\Powwr\DealsController;
use App\Http\Controllers\Api\Powwr\DocumentController;
use App\Http\Controllers\Api\Powwr\DocuSignController;
use App\Http\Controllers\Api\Powwr\GetPromptController;
use App\Http\Controllers\Api\Powwr\MeterLookupController;
use App\Http\Controllers\Api\Powwr\OffersController;
use App\Http\Controllers\Api\Powwr\QuoteController;
use App\Http\Controllers\Api\Powwr\SuppliersController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::post('contact-form', [ContactController::class, 'submitForm']);

Route::post('validate/email', [AuthController::class, 'validateEmail']);

Route::prefix('powwr')->group(function () {
    Route::get('postcode', [PostcodeController::class, 'index']);
    Route::get('suppliers', [SuppliersController::class, 'index']);
    Route::post('meter-lookup', [MeterLookupController::class, 'index']);
    Route::post('offers', [OffersController::class, 'index']);
    Route::post('get-prompt', [GetPromptController::class, 'index']);

    Route::post('save-data', [DealsController::class, 'saveData']);
    Route::post('save-deal', [DealsController::class, 'saveDeal']);
    Route::post('get-deal', [DealsController::class, 'read']);

    Route::post('submit-quote', [QuoteController::class, 'index']);
    Route::post('add-document', [DocumentController::class, 'index']);
    Route::post('add-docusign', [DocuSignController::class, 'index']);
    Route::post('companies', [CompaniesController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [UserController::class, 'index']);
});

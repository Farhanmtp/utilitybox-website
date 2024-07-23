<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\CompareController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\ContractController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\OpenTheBox\ElectricityController;
use App\Http\Controllers\Web\OpenTheBox\GasController;
use App\Http\Controllers\Web\TermsAndConditions;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('/webhooks')->group(function () {
    Route::any('/docusign', [\App\Http\Controllers\Webhooks\DocusignController::class, 'index'])->name('webhooks.docusign');
});

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/open-the-box', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/compare', [CompareController::class, 'index'])->name('compare');
Route::get('/swift-contract-generation-system/{token_or_id?}', [ContractController::class, 'index'])->name('contract');
Route::get('/open-the-box/electricity', [ElectricityController::class, 'index'])->name('electricity');
Route::get('/open-the-box/gas', [GasController::class, 'index'])->name('gas');
Route::get('/terms-and-conditions', [TermsAndConditions::class, 'index'])->name('terms-and-conditions');

Route::prefix('/blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('blogDetail');
});

Route::post('/book-now', [ContactController::class, 'bookNow'])->name('book-now');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

require __DIR__ . '/admin.php';

<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

Route::middleware(['auth','verified'])->group(function() {
  Route::post('/item/{id}/comment', [CommentController::class, 'store']) -> name('comments.store');
  Route::post('/item/{id}/like', [LikeController::class, 'store']) -> name('likes.store');
  Route::delete('/item/{id}/like', [LikeController::class, 'destroy']) -> name('likes.destroy');
  Route::get('/sell', [ItemController::class, 'create']) -> name('items.create');
  Route::post('/sell/store', [ItemController::class, 'store']) -> name('items.store');
  Route::post('/sell/upload-temp', [ItemController::class, 'uploadTemp'])->name('items.uploadTemp');
  Route::delete('/sell/remove-temp', [ItemController::class, 'removeTemp'])->name('items.removeTemp');
  Route::get('/mypage', [MyPageController::class, 'index']) -> name('mypage.index');
  Route::get('/mypage/search', [MyPageController::class, 'search']) -> name('mypage.search');
  Route::get('/mypage/profile', [MyPageController::class, 'edit']) -> name('mypage.edit');
  Route::put('/mypage/profile/update', [MyPageController::class, 'update']) -> name('mypage.update');
  Route::get('/purchase/{id}', [PurchaseController::class, 'create']) -> name('purchase.create');
  Route::post('/purchase/{id}/store', [PurchaseController::class, 'store']) -> name('purchase.store');
  Route::get('/purchase/address/{id}', [PurchaseController::class, 'editAddress']) -> name('purchase.address.edit');
  Route::post('/purchase/address/update/{id}', [PurchaseController::class,'updateAddress']) -> name('purchase.address.update');
  Route::post('/purchase/save-payment-method', [PurchaseController::class, 'savePaymentMethod']) -> name('purchase.save.payment');

});
Route::get('/', [ItemController::class, 'index']) -> name('items.index');
Route::get('/item/{id}', [ItemController::class, 'show']) -> name('items.show');
Route::get('/search', [ItemController::class, 'search']) -> name('items.search');
Route::post('/webhook/stripe', [PurchaseController::class,'handleWebhook']) -> name('webhook.stripe');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();
  return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');


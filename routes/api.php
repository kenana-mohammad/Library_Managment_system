<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NotificationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//user
Route::controller(AuthController::class)->group(function(){
    //register
    Route::Post('users/register','Register');
    //login
    Route::Post('users/login','login');
    //logout
    Route::Post('logout','logout')->middleware('auth:api');
     //get User Profile
     Route::get('User/profile','index')->middleware('auth:api');
     //update User
     Route::Put('user/update/{id}','update')->middleware('auth:api');
});
//book
//Add
Route::middleware(['auth:api','checkAdmin'])->controller(BookController::class)->group(function(){
   Route::Post('book','store');
   Route::Put('book/{id}','update');
   Route::Delete('book/{id}','destroy');

});
Route::controller(BookController::class)->group(function(){
Route::get('book','index');
Route::get('book/{id}','show');
});

    //Author
    Route::middleware(['auth:api','checkAdmin'])->controller(AuthorController::class)->group(function(){
        Route::Post('author','store');
        Route::put('author/{id}','update');
        Route::Delete('author/{id}','destroy');

     });
     Route::controller(AuthorController::class)->group(function(){
     Route::get('author','index');
     Route::get('author/{id}','show');
     });
//
//Reviews
Route::controller(ReviewController::class)->group(function(){
 //Add Review To Book
 Route::Post('/reviews/books/{book_id}','AddReviewsToBook')->middleware('auth');
 //Add Review To Author
 Route::Post('/reviews/authors/{author_id}','AddReviewsToAuthor')->middleware('auth');
//list of reviews
Route::get('reviews','index');
//update
Route::Put('/review/{id}','update')->middleware('auth');
//delete
Route::Delete('/review/{id}','Delete')->middleware('auth');

});
//borrow Book

Route::controller(ReservationController::class)->group(function(){
    //
    Route::Post('borrow/{id}','borrow')->middleware('auth:api');
    Route::Post('return/{id}','returnBook')->middleware('auth:api');

});
//get all notifaction

Route::get('get/notifications',[NotificationController::class,'index']);
Route::get('read/{id}',[NotificationController::class,'ReadNotification'])->middleware('auth');
//test
Route::post('test',[NotificationController::class,'test']);


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UploadFileController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('home', [HomeController::class,'index'])->name('home');
Route::get('single/{post}', [SingleController::class,'index'])->name('single.index');


Auth::routes();

Route::middleware('auth:web')->group(function(){
    Route::post('single/{post}/comment', [SingleController::class,'save_comment'])->name('single.save_comment');
});

Route::prefix('admin/')->middleware(['admin'])->name('admin.')->group(function(){
    Route::resource('posts', PostController::class)->except('show');
    Route::post('upload-image', [UploadFileController::class,'upload_image'])->name('upload_file');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

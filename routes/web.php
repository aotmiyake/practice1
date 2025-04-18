<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

//一覧画面に遷移
Route::get('/list', [App\Http\Controllers\ArticleController::class, 'showList'])->name('list');

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/regist',[App\Http\Controllers\ArticleController::class, 'showRegistForm'])->name('regist');

Route::post('/regist',[App\Http\Controllers\ArticleController::class, 'registSubmit'])->name('submit');
//商品データ削除
Route::post('/delete/{id}',[App\Http\Controllers\ArticleController::class, 'delete'])->name('delete');
//商品詳細
Route::get('/detail/{id}',[App\Http\Controllers\ArticleController::class, 'detail'])->name('detail');
//情報更新遷移
Route::get('/update/{id}',[App\Http\Controllers\ArticleController::class, 'update'])->name('update');
//情報更新
Route::post('/update/{id}',[App\Http\Controllers\ArticleController::class, 'updateSubmit'])->name('infoUpdate');
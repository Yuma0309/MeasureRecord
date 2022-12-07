<?php

use Illuminate\Support\Facades\Route;
use App\Models\Record;
use Illuminate\Http\Request;

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

// 測定値一覧画面
Route::get('/', 'App\Http\Controllers\RecordsController@index');

// 保存処理
Route::post('/records', 'App\Http\Controllers\RecordsController@store');

// 編集画面
Route::post('/recordsedit/{records}', 'App\Http\Controllers\RecordsController@edit');

// 編集処理
Route::post('/records/update', 'App\Http\Controllers\RecordsController@update');

// 削除処理
Route::delete('/record/{record}', 'App\Http\Controllers\RecordsController@destroy');

//Auth
Auth::routes();
Route::get('/home', [App\Http\Controllers\RecordsController::class, 'index'])->name('home');

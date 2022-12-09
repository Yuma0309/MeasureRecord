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

// タイトル保存画面
Route::get('/titles', 'App\Http\Controllers\RecordsController@title');

// タイトル保存処理
Route::post('/titles/store', 'App\Http\Controllers\RecordsController@titlestore');

// 測定値一覧画面
Route::get('/', 'App\Http\Controllers\RecordsController@index');

// 測定値保存処理
Route::post('/records', 'App\Http\Controllers\RecordsController@store');

// 測定値検索処理
Route::post('/records/search', 'App\Http\Controllers\RecordsController@search');

// 測定値編集画面
Route::post('/recordsedit/{records}', 'App\Http\Controllers\RecordsController@edit');

// 測定値編集処理
Route::post('/records/update', 'App\Http\Controllers\RecordsController@update');

// 測定値削除処理
Route::delete('/record/{record}', 'App\Http\Controllers\RecordsController@destroy');

//Auth
Auth::routes();
Route::get('/home', [App\Http\Controllers\RecordsController::class, 'index'])->name('home');

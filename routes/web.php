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
Route::get('/titles', 'App\Http\Controllers\TitlesController@title');

// タイトル保存処理
Route::post('/titles/store', 'App\Http\Controllers\TitlesController@titlestore');

// タイトル画面
Route::get('/titlesindex', 'App\Http\Controllers\TitlesController@titleindex');

// タイトルグルーピング処理
Route::get('/titlesgroup', 'App\Http\Controllers\TitlesController@titlegroup');

// タイトル追加画面
Route::get('/titlesadd', 'App\Http\Controllers\TitlesController@titleaddindex');

// タイトル追加処理
Route::post('/titlesadd/store', 'App\Http\Controllers\TitlesController@titleadd');

// タイトル編集画面
Route::post('/titlesedit', 'App\Http\Controllers\TitlesController@titleedit');

// タイトル編集処理
Route::post('/titles/update', 'App\Http\Controllers\TitlesController@titleupdate');

// タイトル削除処理
Route::delete('/title', 'App\Http\Controllers\TitlesController@titledestroy');

// ------------------------------------------------------------------------------------------

// 測定値一覧画面
Route::get('/', 'App\Http\Controllers\RecordsController@index');
Route::post('/', 'App\Http\Controllers\RecordsController@index');

// チャートデータ取得処理
// Route::get('/chart-get', [App\Http\Controllers\RecordsController::class, 'chartGet'])->name('chart-get');

// 測定値保存処理
Route::post('/records', 'App\Http\Controllers\RecordsController@store');

// 測定値検索処理
Route::post('/records/search', 'App\Http\Controllers\RecordsController@search');

// 測定値編集画面
Route::post('/recordsedit', 'App\Http\Controllers\RecordsController@edit');

// 測定値編集処理
Route::post('/records/update', 'App\Http\Controllers\RecordsController@update');

// 測定値削除処理
Route::delete('/record', 'App\Http\Controllers\RecordsController@destroy');

// ------------------------------------------------------------------------------------------

// Auth
Auth::routes();
Route::get('/home', [App\Http\Controllers\RecordsController::class, 'index'])->name('home');

// ------------------------------------------------------------------------------------------

// // メール画面
// Route::get('/mail', [App\Http\Controllers\MailController::class, 'index']);

// // メール送信処理
// Route::post('/mail/send', [App\Http\Controllers\MailController::class, 'send'])->name('mail.send');

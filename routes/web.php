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

// 測定値一覧
Route::get('/', function () {
    return view('welcome');
});

// 保存処理
Route::post('/records', function (Request $request) {
    //
});

// 削除処理
Route::delete('/record/{record}', function (Record $record) {
    //
});

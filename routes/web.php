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
    $records = Record::orderBy('created_at', 'asc')->get();
    return view('records', [
        'records' => $records
    ]);
});

// 保存処理
Route::post('/records', function (Request $request) {

    // バリデーション
    $validator = Validator::make($request->all(), [
        'title' => 'required|min:1|max:30',
    ]);

    //バリデーション：エラー
    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    //Eloquentモデル（保存処理）
    $records = new Record;
    $records->title = $request->title;
    $records->date = '2022-12-05 00:00:00';
    $records->amount = '10.0';
    $records->comment = 'トレーニングの成果です！';
    $records->save();
    return redirect('/');

});

// 削除処理
Route::delete('/record/{record}', function (Record $record) {
    $record->delete();
    return redirect('/');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

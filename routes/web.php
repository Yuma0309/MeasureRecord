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
        'date' => 'required',
        'amount' => 'required|min:1|max:10',
        'comment' => 'required|min:1',
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
    $records->date = $request->date;
    $records->amount = $request->amount;
    $records->comment = $request->comment;
    $records->save();
    return redirect('/');
});

// 編集画面
Route::post('/recordsedit/{records}', function (Record $records) {
    //{records}id値を取得 = Record $records id値の1レコード取得
    return view('recordsedit', ['record' => $records]);
});

// 編集処理
Route::post('/records/update', function (Request $request) {
    // バリデーション
    $validator = Validator::make($request->all(), [
        'id' => 'required',
        'date' => 'required',
        'amount' => 'required|min:1|max:10',
        'comment' => 'required|min:1',
    ]);

    // バリデーション：エラー
    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    // データ編集
    $records = Record::find($request->id);
    $records->date = $request->date;
    $records->amount = $request->amount;
    $records->comment = $request->comment;
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

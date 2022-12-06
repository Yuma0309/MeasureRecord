<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Record; //Recordモデルを使えるようにする
use Validator; //バリデーションを使えるようにする
use Auth; //認証モデルを使用する

class RecordsController extends Controller
{
    // 測定値一覧表示
    public function index(){
        $records = Record::orderBy('created_at', 'asc')->get();
        return view('records', [
            'records' => $records
        ]);
    }

    // 保存処理
    public function store(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:1|max:30',
            'date' => 'required',
            'amount' => 'required|min:1|max:9',
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
    }


    // 編集画面表示
    public function edit(Record $records) {
        return view('recordsedit', ['record' => $records]);
    }
    
    // 編集処理
    public function update(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'date' => 'required',
            'amount' => 'required|min:1|max:9',
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
    }

    // 削除処理
    public function destroy(Record $record) {
        $record->delete();
        return redirect('/');
    }
    
}

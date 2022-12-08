<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Record; //Recordモデルを使えるようにする
use Validator; //バリデーションを使えるようにする
use Auth; //認証モデルを使用する

class RecordsController extends Controller
{
    //コンストラクタ（このクラスが呼ばれたら最初に処理をする）
    public function __construct()
    {
        //ログイン認証後にだけ表示
        $this->middleware('auth');
    }
    
    // 測定値一覧表示
    public function index(Request $request){
        $sort = $request->sort;
        if (is_null($sort)) { //$sortの初期値（値がない場合）
            $sort = 'created_at';
        }
        $records = Record::where('user_id', Auth::user()->id)->orderBy($sort, 'asc')->paginate(10);
        return view('records', [
            'sort' => $sort,
            'keyword' => '',
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
        $records->user_id = Auth::user()->id;
        $records->title = $request->title;
        $records->date = $request->date;
        $records->amount = $request->amount;
        $records->comment = $request->comment;
        $records->save();
        return redirect('/')->with('message', '保存しました');
    }

    // 検索処理
    public function search(Request $request){
        $sort = 'created_at';

        $keyword = $request->input('keyword');

        $query = Record::query();

        if(!empty($keyword)) {
            $query->where('user_id', Auth::user()->id)
                ->where('date', 'LIKE', "%{$keyword}%")
                ->orWhere('amount', 'LIKE', "%{$keyword}%")
                ->orWhere('comment', 'LIKE', "%{$keyword}%");
        }

        $records = $query->orderBy($sort, 'asc')->paginate(10);

        return view('records', compact('sort', 'records', 'keyword'));
    }

    // 編集画面表示
    public function edit($record_id) {
        $records = Record::where('user_id', Auth::user()->id)->find($record_id);
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
        $records = Record::where('user_id', Auth::user()->id)->find($request->id);
        $records->date = $request->date;
        $records->amount = $request->amount;
        $records->comment = $request->comment;
        $records->save();
        return redirect('/')->with('message', '保存しました');
    }

    // 削除処理
    public function destroy(Record $record) {
        $record->delete();
        return redirect('/');
    }
    
}

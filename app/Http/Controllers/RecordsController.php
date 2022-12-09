<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Record; //Recordモデルを使えるようにする
use App\Models\Title; //Titleモデルを使えるようにする
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

    // タイトル保存画面表示（タイトルが1つもない場合に表示させる）
    public function title() {
        return view('title');
    }
    
    // タイトル保存処理
    public function titlestore(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:1|max:30',
        ]);

        //バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/title')
                ->withInput()
                ->withErrors($validator);
        }

        //Eloquentモデル（保存処理）
        $title = new Title;
        $title->user_id = Auth::user()->id;
        $title->title = $request->title;
        $title->save();
        return redirect('/')->with('message', 'タイトルを保存しました');
    }
    
    // --------------------------------------------------------------------------------------------
    
    // 測定値一覧表示
    public function index(Request $request){

        // タイトルが1つもない場合、タイトル保存画面を表示
        // $records = Title::where('user_id', Auth::user()->id)->count();
        // if ($records === 0) {
        //     return redirect('/title');
        // }

        $sort = $request->sort;
        if (is_null($sort)) { //$sortの初期値（値がない場合）
            $sort = 'created_at';
        }
        $records = Record::where('user_id', Auth::user()->id)->orderBy($sort, 'asc')->paginate(10);//--------------------
        $title = Title::where('user_id', Auth::user()->id)->first();//------------------------------
        return view('records', [
            'sort' => $sort,
            'keyword' => '',
            'records' => $records,
            'title' => $title
        ]);
    }

    // 測定値保存処理
    public function store(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
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
        $title = Title::where('user_id', Auth::user()->id)->first();//------------------------------
        $records->user_id = Auth::user()->id;
        $records->title_id = $title->id;
        $records->date = $request->date;
        $records->amount = $request->amount;
        $records->comment = $request->comment;
        $records->save();
        return redirect('/')->with('message', '保存しました');
    }

    // 測定値検索処理
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

        $title = Title::where('user_id', Auth::user()->id)->first();//------------------------------

        return view('records', compact('sort', 'records', 'keyword', 'title'));
    }

    // 測定値編集画面表示
    public function edit($record_id) {
        $records = Record::where('user_id', Auth::user()->id)->find($record_id);
        return view('recordsedit', ['record' => $records]);
    }
    
    // 測定値編集処理
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

    // 測定値削除処理
    public function destroy(Record $record) {
        $record->delete();
        return redirect('/');
    }
    
}

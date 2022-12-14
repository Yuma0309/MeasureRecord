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
    
    // 測定値一覧表示
    public function index(Request $request){

        // タイトルが1つもない場合、タイトル保存画面を表示
        $titles = Title::where('user_id', Auth::user()->id)->count();
        if ($titles === 0) {
            return redirect('/titles');
        }

        $id = $request->id;

        $titles = Title::where('user_id', Auth::user()->id)->find($id);

        // タイトルがnullの場合、最初の1レコードを表示
        if (is_null($titles)) { //$sortの初期値（値がない場合）
            $titles = Title::where('user_id', Auth::user()->id)->first();
            $id = $titles->id;
        }

        $sort = $request->sort;
        if (is_null($sort)) { //$sortの初期値（値がない場合）
            $sort = 'created_at';
        }

        $records = Record::where('user_id', Auth::user()->id)
            ->where('title_id', $id)
            ->orderBy($sort, 'asc')
            ->paginate(10);
        
        for ($i = 0; $i < count($records); $i++) {
            $date[] = $records[$i]->date;
        }
        for ($i = 0; $i < count($records); $i++) {
            $amount[] = $records[$i]->amount;
        }

        session(['date' => $date]);
        session(['amount' => $amount]);

        return view('records', [
            'keyword' => '',
            'titles' => $titles,
            'sort' => $sort,
            'records' => $records
        ]);
    }
    
    // チャートデータを取得
    public function chartGet(){
        $date = session('date');
        $amount = session('amount');
        
        return [
            [
                'date' => $date, 
                'amount' => $amount, 
            ]
        ];
    }

    // 測定値保存処理
    public function store(Request $request){

        // バリデーション
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => 'required|min:1|max:9',
            'comment' => 'required|min:1',
        ]);

        $id = $request->id;

        $titles = Title::where('user_id', Auth::user()->id)->find($id);

        $sort = $request->sort;
        if (is_null($sort)) { //$sortの初期値（値がない場合）
            $sort = 'created_at';
        }

        $records = Record::where('user_id', Auth::user()->id)
            ->where('title_id', $id)
            ->orderBy($sort, 'asc')
            ->paginate(10);

        //バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/?id='.$titles->id)->withErrors($validator);
        }        

        //Eloquentモデル（保存処理）
        $records = new Record;
        $records->user_id = Auth::user()->id;
        $records->title_id = $id;
        $records->date = $request->date;
        $records->amount = $request->amount;
        $records->comment = $request->comment;
        $records->save();

        $records = Record::where('user_id', Auth::user()->id)
            ->where('title_id', $id)
            ->orderBy($sort, 'asc')
            ->paginate(10);
        
        session()->flash('message', '保存しました');
        
        return redirect('/?id='.$titles->id);
    }

    // 測定値検索処理
    public function search(Request $request){

        $keyword = $request->input('keyword');

        $id = $request->id;

        $titles = Title::where('user_id', Auth::user()->id)->find($id);

        $sort = 'created_at';

        $records = Record::where('user_id', Auth::user()->id)
                        ->where('title_id', $id)
                        ->where(function ($query) use ($keyword) {
                            $query->where('date', 'LIKE', "%{$keyword}%")
                                ->orWhere('amount', 'LIKE', "%{$keyword}%")
                                ->orWhere('comment', 'LIKE', "%{$keyword}%");
                        })
                        ->orderBy($sort, 'asc')
                        ->paginate(10000);

        session()->flash('message', '検索しました');

        return view('records', [
            'keyword' => $keyword,
            'titles' => $titles,
            'sort' => $sort,
            'records' => $records
        ]);
    }

    // 測定値編集画面表示
    public function edit($record_id) {

        $records = Record::where('user_id', Auth::user()->id)->find($record_id);

        $id = $records->title_id;

        $titles = Title::where('user_id', Auth::user()->id)->find($id);

        return view('recordsedit', [
            'record' => $records,
            'titles' => $titles
        ]);
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

        $sort = 'created_at';

        $id = $request->id;

        $records = Record::where('user_id', Auth::user()->id)->find($id);

        $title_id = $records->title_id;

        $titles = Title::where('user_id', Auth::user()->id)->find($title_id);

        // バリデーション：エラー
        if ($validator->fails()) {
            return view('recordsedit', [
                'record' => $records,
                'titles' => $titles
            ])->withErrors($validator);
        }

        // データ編集
        $records = Record::where('user_id', Auth::user()->id)->find($request->id);
        $records->date = $request->date;
        $records->amount = $request->amount;
        $records->comment = $request->comment;
        $records->save();

        $records = Record::where('user_id', Auth::user()->id)
            ->where('title_id', $title_id)
            ->orderBy($sort, 'asc')
            ->paginate(10);
        
        session()->flash('message', '保存しました');

        return redirect('/?id='.$titles->id);
    }

    // 測定値削除処理
    public function destroy(Record $record) {

        $sort = 'created_at';

        $id = $record->id;

        $records = Record::where('user_id', Auth::user()->id)->find($id);

        $title_id = $records->title_id;

        $titles = Title::where('user_id', Auth::user()->id)->find($title_id);

        $record->delete();

        $records = Record::where('user_id', Auth::user()->id)
            ->where('title_id', $title_id)
            ->orderBy($sort, 'asc')
            ->paginate(10);

        session()->flash('message', '削除しました');

        return redirect('/?id='.$titles->id);
    }
    
}

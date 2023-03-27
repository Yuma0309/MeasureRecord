<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Record; // Recordモデルを使えるようにする
use App\Models\Title; // Titleモデルを使えるようにする
use Validator; // バリデーションを使えるようにする
use Auth; // 認証モデルを使用する

class TitlesController extends Controller
{
    // コンストラクタ（このクラスが呼ばれたら最初に処理をする）
    public function __construct()
    {
        // ログイン認証後にだけ表示
        $this->middleware('auth');
    }

    // タイトル保存画面表示（タイトルが1つもない場合に表示させる）
    public function title() {
        return view('titles.titles');
    }
    
    // タイトル保存処理
    public function titlestore(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:1|max:30',
            'unit' => 'max:30',
        ]);

        // バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/titles')
                ->withInput()
                ->withErrors($validator);
        }

        // Eloquentモデル（保存処理）
        $titles = new Title;
        $titles->user_id = Auth::user()->id;
        $titles->title = $request->title;
        $titles->unit = $request->unit;
        $titles->save();
        return redirect('/')->with('message', 'タイトルを保存しました');
    }

    // タイトル画面表示
    public function titleindex(Request $request) {
        $page = $request->page;
        $titles = Title::orderBy('created_at', 'asc')->paginate(10);
        return view('titles.titlesindex', [
            'titles' => $titles,
            'page' => $page
        ]);
    }

    // タイトルグルーピング処理
    public function titlegroup(Request $request) {
        $page = $request->page;
        $titles = Title::orderBy('created_at', 'asc')->get();
        $titles = $titles->groupBy('title'); // グルーピング

        $array = []; // 多次元配列の次元を減らす
        foreach ($titles as $key => $valueParent) {
            foreach ($valueParent as $valueChild) {
                $array[] = $valueChild;
            }
        }

        $titles = collect($array); // 型の変換（配列 → コレクション）
        $titles = new LengthAwarePaginator( // ページャーに対応（コレクションから1ページあたり10000ずつ表示する）
            $titles->forPage($page, 10), // 表示するコレクション -> (現在のページ番号, 1ページあたりの表示数)
            count($titles),                 // コレクションの大きさ
            10,                          // 1ページあたりの表示数
            $page,                          // 現在のページ番号
            array('path' => $request->url()) // オプション（ページの遷移先パス）
        );

        return view('titles.titlesindex', [
            'titles' => $titles,
            'page' => $page
        ]);
    }

    // タイトル追加画面表示
    public function titleaddindex() {
        return view('titles.titlesadd');
    }

    // タイトル追加処理
    public function titleadd(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:1|max:30',
            'unit' => 'max:30',
        ]);

        // バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/titlesadd')
                ->withInput()
                ->withErrors($validator);
        }

        // Eloquentモデル（保存処理）
        $titles = new Title;
        $titles->user_id = Auth::user()->id;
        $titles->title = $request->title;
        $titles->unit = $request->unit;
        $titles->save();
        return redirect('/titlesindex')->with('message', 'タイトルを保存しました');
    }

    // タイトル編集画面表示
    public function titleedit(Request $request) {
        $titleId = $request->id;
        $titles = Title::find($titleId);
        $page = $request->page;
        return view('titles.titlesedit', [
            'title' => $titles,
            'page' => $page
        ]);
    }

    // タイトル編集処理
    public function titleupdate(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required|min:1|max:30',
            'unit' => 'max:30',
        ]);

        $titleId = $request->id;

        $title = Title::find($titleId);

        $page = $request->page;

        // バリデーション：エラー
        if ($validator->fails()) {
            return view('titles.titlesedit', [
                'title' => $title,
                'page' => $page
            ])->withErrors($validator);
        }

        // データ編集
        $titles = Title::find($titleId);
        $titles->title = $request->title;
        $titles->unit = $request->unit;
        $titles->save();
    
        session()->flash('message', 'タイトルを保存しました');

        return redirect('/titlesindex/?page='.$page);
    }

    // タイトル削除処理
    public function titledestroy(Request $request) {
        $titleId = $request->id;

        $title = Title::find($titleId);

        $page = $request->page;

        $title->delete();

        session()->flash('message', 'タイトルを削除しました');

        return redirect('/titlesindex/?page='.$page);
    }
}

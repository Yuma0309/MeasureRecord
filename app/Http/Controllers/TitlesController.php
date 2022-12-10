<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Record; //Recordモデルを使えるようにする
use App\Models\Title; //Titleモデルを使えるようにする
use Validator; //バリデーションを使えるようにする
use Auth; //認証モデルを使用する

class TitlesController extends Controller
{
    //コンストラクタ（このクラスが呼ばれたら最初に処理をする）
    public function __construct()
    {
        //ログイン認証後にだけ表示
        $this->middleware('auth');
    }

    // タイトル保存画面表示（タイトルが1つもない場合に表示させる）
    public function title() {
        return view('titles');
    }
    
    // タイトル保存処理
    public function titlestore(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:1|max:30',
        ]);

        //バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/titles')
                ->withInput()
                ->withErrors($validator);
        }

        //Eloquentモデル（保存処理）
        $titles = new Title;
        $titles->user_id = Auth::user()->id;
        $titles->title = $request->title;
        $titles->save();
        return redirect('/')->with('message', 'タイトルを保存しました');
    }

    // タイトル画面表示
    public function titleindex() {
        $titles = Title::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc')->paginate(10);;
        return view('titlesindex', [
            'titles' => $titles
        ]);
    }

    // タイトル追加画面表示
    public function titleaddindex() {
        return view('titlesadd');
    }

    // タイトル追加処理
    public function titleadd(Request $request){
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
        $titles = new Title;
        $titles->user_id = Auth::user()->id;
        $titles->title = $request->title;
        $titles->save();
        return redirect('/')->with('message', 'タイトルを保存しました');
    }

    // タイトル編集画面表示
    public function titleedit($title_id) {
        $titles = Title::where('user_id', Auth::user()->id)->find($title_id);
        return view('titlesedit', ['title' => $titles]);
    }

    // タイトル編集処理
    public function titleupdate(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required|min:1|max:30',
        ]);

        // バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/titlesindex')
                ->withInput()
                ->withErrors($validator);
        }

        // データ編集
        $titles = Title::where('user_id', Auth::user()->id)->find($request->id);
        $titles->title = $request->title;
        $titles->save();
        return redirect('/titlesindex')->with('message', 'タイトルを保存しました');
    }

}

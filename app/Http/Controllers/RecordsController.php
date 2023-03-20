<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Record; // Recordモデルを使えるようにする
use App\Models\Title; // Titleモデルを使えるようにする
use Validator; // バリデーションを使えるようにする
use Auth; // 認証モデルを使用する
use Carbon\Carbon; // Carbonクラスを使用する

class RecordsController extends Controller
{
    // コンストラクタ（このクラスが呼ばれたら最初に処理をする）
    public function __construct()
    {
        // ログイン認証後にだけ表示
        $this->middleware('auth');
    }
    
    // 測定値一覧表示
    public function index(Request $request){

        // タイトルが1つもない場合、タイトル保存画面を表示
        $titles = Title::count();
        if ($titles === 0) {
            return redirect('/titles');
        }

        $titleId = $request->id;

        $titles = Title::find($titleId);

        // タイトルがnullの場合、最初の1レコードを表示
        if (is_null($titles)) { // $sortの初期値（値がない場合）
            $titles = Title::first();
            $titleId = $titles->id;
        }

        // chartGet関数に値を送る
        // session(['title_id' => $titleId]);

        // チャートデータを取得
        $records = Record::where('title_id', $titleId)
            ->orderBy('date', 'asc')
            ->get();
        
        $graphYear = $request->graph_year; // 年別の表示
        if ($graphYear) {
            $records = Record::where('title_id', $titleId)
            ->whereBetween('date', ["{$graphYear}-01-01 00:00:00", "{$graphYear}-12-31 23:59:59"])
            ->orderBy('date', 'asc')
            ->get();
            if ($records->isEmpty()) { // $recordsの値が空の場合
                session()->flash('message', '該当するデータが見つかりませんでした');
            } else {
                session()->flash('message', '');
            }
        }

        $graphMonth = $request->graph_month; // 月別の表示
        $dayMax = Carbon::create($graphYear, $graphMonth)->endOfMonth(); // 月末の日付を取得
        if ($graphMonth) {
            $records = Record::where('title_id', $titleId)
            ->whereBetween('date', ["{$graphYear}-{$graphMonth}-01 00:00:00", "{$dayMax} 23:59:59"]) // 日付が存在しないと表示されない
            ->orderBy('date', 'asc')
            ->get();
            if ($records->isEmpty()) { // $recordsの値が空の場合
                session()->flash('message', '該当するデータが見つかりませんでした');
            } else {
                session()->flash('message', '');
            }
        }

        $graphDay = $request->graph_day; // 日別の表示
        if ($graphDay) {
            $records = Record::where('title_id', $titleId)
            ->whereBetween('date', ["{$graphYear}-{$graphMonth}-{$graphDay} 00:00:00", "{$graphYear}-{$graphMonth}-{$graphDay} 23:59:59"])
            ->orderBy('date', 'asc')
            ->get();
            if ($records->isEmpty()) { // $recordsの値が空の場合
                session()->flash('message', '該当するデータが見つかりませんでした');
            } else {
                session()->flash('message', '');
            }
        }
        
        $collection = collect($records);
        $dateMax = $collection->max('date');
        $dateMin = $collection->min('date');
        $dateMaxObj = new Carbon($dateMax);
        $dateMinObj = new Carbon($dateMin);
        $interval = $dateMaxObj->diffInDays($dateMinObj, true);

        if ($interval > 1) { // $records->dateの最大値と最小値の日にちの差が1日以上あれば実行
            for ($i = 1; $i <= $interval; $i++) {
                $dateMinObj->addDays(1);
                $dateSearch = Record::where('title_id', $titleId)
                    ->where('date', $dateMinObj->format('Y-m-d'))
                    ->get();
                $collection = collect($dateSearch);
                if ($collection->isEmpty()) { // $records->dateの最大値と最小値の日にちの間で、測定値がない日があればnullを入れる
                    $number = count($records); // $recordsの個数はforで繰り返されるたびに増えていく
                    $records[$number] = new Record;
                    $records[$number]->date = $dateMinObj->format('Y-m-d');
                    $records[$number]->amount = null;
                }
            }
        }

        $collection = collect($records);
        $records = $collection->sortBy('date')->values(); // $recordsのオブジェクトを日付の昇順に並べ替える
        
        $date = []; // chartjs.jsに値を渡すための配列を作成
        $amount = [];

        for ($i = 0; $i < count($records); $i++) { // 配列$dateと$amountに$records->dateと$records->amountの値をそれぞれ入れる
            $date[] = $records[$i]->date;
            $amount[] = $records[$i]->amount;
        }

        $sort = $request->sort;
        if (is_null($sort)) { // $sortの初期値（値がない場合）
            $sort = 'created_at';
        }

        $page = $request->page;

        // 測定値一覧の項目のボタンが押された回数に応じて昇順と降順を切り替える（奇数回なら昇順、偶数回なら降順）
        $sortNumber = $request->sortNumber;
        if (is_null($sortNumber)) { // $sortNumberの初期値（値がない場合）：'/'にリダイレクト後
            $sortNumber = 0;
            $sortOrder = 'desc';
        } else {
            if (!is_numeric($page)) { // $pageがnullの場合：測定値一覧の項目のボタンを押すと'/'にリダイレクトして自動的に$pageがnullになる
                $sortNumber = $sortNumber + 1;
                if ($sortNumber % 2 == 0) {
                    $sortOrder = 'desc';
                    session()->flash('message', '降順で並べ替えました');
                } else {
                    $sortOrder = 'asc';
                    session()->flash('message', '昇順で並べ替えました');
                }
            } else { // $pageが数値の場合：ペジネーションのボタンを押すと自動的に$pageが数値になる
                if ($sortNumber % 2 == 0) {
                    $sortOrder = 'desc';
                } else {
                    $sortOrder = 'asc';
                }
            }
        }

        $records = Record::where('title_id', $titleId)
            ->orderBy($sort, $sortOrder)
            ->paginate(10);
        
        // $keywordに値があれば検索する
        $keyword = $request->keyword;
        if (is_null($keyword)) { // $keywordの初期値（値がない場合）
            $keyword = '';
        } else {
            $records = Record::where('title_id', $titleId)
                ->where(function ($query) use ($keyword) {

                    // 全角スペースを半角に変換
                    $spaceConversion = mb_convert_kana($keyword, 's');

                    // 単語を半角スペースで区切り、配列にする（例："10 178" → ["10", "178"]）
                    $wordArraySearched = preg_split('/[\s,、]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);

                    // 単語をループで回し、レコードと部分一致するものがあれば、$queryとして保持される
                    foreach($wordArraySearched as $value) {
                        $query->orWhere('date', 'LIKE', "%{$value}%")
                            ->orWhere('amount', 'LIKE', "%{$value}%")
                            ->orWhere('comment', 'LIKE', "%{$value}%");
                    }

                })
                ->orderBy($sort, $sortOrder)
                ->paginate(10);

            session()->flash('message', '検索しました');
        }

        return view('records.records', [
            'titles' => $titles,
            'sort' => $sort,
            'sortNumber' => $sortNumber,
            'keyword' => $keyword,
            'records' => $records,
            'page' => $page,
            'date' => $date, 
            'amount' => $amount
        ]);
    }
    
    // チャートデータを取得
    // public function chartGet(){

    //     $titleId = session('title_id');

    //     $titles = Title::find($titleId);

    //     $records = Record::where('title_id', $titleId)
    //         ->orderBy('date', 'asc')
    //         ->get();
        
    //     $collection = collect($records);
    //     $dateMax = $collection->max('date');
    //     $dateMin = $collection->min('date');
    //     $dateMaxObj = new Carbon($dateMax);
    //     $dateMinObj = new Carbon($dateMin);
    //     $interval = $dateMaxObj->diffInDays($dateMinObj, true);

        // if ($interval > 1) { // $records->dateの最大値と最小値の日にちの差が1日以上あれば実行
        //     for ($i = 1; $i <= $interval; $i++) {
        //         $dateMinObj->addDays(1);
        //         $dateSearch = Record::where('title_id', $titleId)
        //             ->where('date', $dateMinObj->format('Y-m-d'))
        //             ->get();
        //         $collection = collect($dateSearch);
        //         if ($collection->isEmpty()) { // $records->dateの最大値と最小値の日にちの間で、測定値がない日があればnullを入れる
        //             $number = count($records); // $recordsの個数はforで繰り返されるたびに増えていく
        //             $records[$number] = new Record;
        //             $records[$number]->date = $dateMinObj->format('Y-m-d');
        //             $records[$number]->amount = null;
        //         }
        //     }
        // }

    //     $collection = collect($records);
    //     $records = $collection->sortBy('date')->values(); // $recordsのオブジェクトを日付の昇順に並べ替える
        
    //     $date = []; // chartjs.jsに値を渡すための配列を作成
    //     $amount = [];
    //     $titleName = [];

    //     for ($i = 0; $i < count($records); $i++) { // 配列$dateと$amountに$records->dateと$records->amountの値をそれぞれ入れる
    //         $date[] = $records[$i]->date;
    //         $amount[] = $records[$i]->amount;
    //     }

    //     $titleName[] = $titles->title;

    //     return [
    //         'date' => $date, 
    //         'amount' => $amount,
    //         'title_name' => $titleName
    //     ];
    // }

    // 測定値保存処理
    public function store(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => ['required', 'numeric', 'regex:/((^(-*)[0-9]{0,9})(.[0-9]{0,2}$))/', 'max:999999999.94', 'min:-999999999.94'],
            'comment' => 'required|min:1',
        ]);

        $titleId = $request->id;

        $titles = Title::find($titleId);

        $sort = $request->sort;
        if (is_null($sort)) { // $sortの初期値（値がない場合）
            $sort = 'created_at';
        }

        $records = Record::where('title_id', $titleId)
            ->orderBy($sort, 'asc')
            ->paginate(10);

        // バリデーション：エラー
        if ($validator->fails()) {
            return redirect('/?id='.$titles->id)->withErrors($validator);
        }        

        // Eloquentモデル（保存処理）
        $records = new Record;
        $records->user_id = Auth::user()->id;
        $records->title_id = $titleId;
        $records->date = $request->date;
        $records->amount = $request->amount;
        $records->comment = $request->comment;
        $records->save();
        
        session()->flash('message', '保存しました');
        
        return redirect('/?id='.$titles->id);
    }

    // 測定値検索処理
    public function search(Request $request){
        $keyword = $request->input('keyword');

        $titleId = $request->id;

        $titles = Title::find($titleId);

        $sort = $request->sort;

        $sortNumber = $request->sortNumber;

        return redirect('/?id='.$titles->id.'&sort='.$sort.'&sortNumber='.$sortNumber.'&keyword='.$keyword);
    }

    // 測定値編集画面表示
    public function edit(Request $request) {
        $recordId = $request->record_id;

        $records = Record::find($recordId);

        $titleId = $records->title_id;

        $titles = Title::find($titleId);

        $page = $request->page;

        return view('records.recordsedit', [
            'record' => $records,
            'titles' => $titles,
            'page' => $page
        ]);
    }
    
    // 測定値編集処理
    public function update(Request $request){
        // バリデーション
        $validator = Validator::make($request->all(), [
            'record_id' => 'required',
            'date' => 'required',
            'amount' => ['required', 'numeric', 'regex:/((^(-*)[0-9]{0,9})(.[0-9]{0,2}$))/', 'max:999999999.94', 'min:-999999999.94'],
            'comment' => 'required|min:1',
        ]);

        $sort = 'created_at';

        $recordId = $request->record_id;

        $records = Record::find($recordId);

        $titleId = $records->title_id;

        $titles = Title::find($titleId);

        $page = $request->page;

        // バリデーション：エラー
        if ($validator->fails()) {
            return view('records.recordsedit', [
                'record' => $records,
                'titles' => $titles,
                'page' => $page
            ])->withErrors($validator);
        }

        // データ編集
        $records = Record::find($request->record_id);
        $records->date = $request->date;
        $records->amount = $request->amount;
        $records->comment = $request->comment;
        $records->save();
        
        session()->flash('message', '保存しました');

        return redirect('/?id='.$titles->id.'&page='.$page);
    }

    // 測定値削除処理
    public function destroy(Request $request) {
        $recordId = $request->record_id;

        $sort = 'created_at';

        $record = Record::find($recordId);

        $titleId = $record->title_id;

        $titles = Title::find($titleId);

        $page = $request->page;

        $record->delete();

        session()->flash('message', '削除しました');

        return redirect('/?id='.$titles->id.'&page='.$page);
    }
}

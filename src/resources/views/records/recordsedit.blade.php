@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mx-0">
        <div class="card">
            <div class="card-body my-3">
                <div style="font-size:15pt;font-weight:bold;" class="card-title mt-1 mb-4 mx-2">
                    測定値編集フォーム
                </div>
                <div class="col-md-10">
                    @include('common.errors')
                    <form action="{{ url('records/update/?record_id='.$record->id.'&page='.$page) }}" method="POST">
                        @csrf

                        <!-- 日付 -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="date" class="form-label">
                                日付
                            </label>
                            <input type="date" name="date" class="form-control" id="date" value="{{$record->date}}"/>
                        </div>

                        <!-- 測定値 -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="amount" class="form-label">
                                測定値
                            </label>
                            <input type="text" name="amount" class="form-control" id="amount" value="{{$record->amount}}">
                        </div>

                        <!-- コメント -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="comment" class="form-label">
                                コメント
                            </label>
                            <input type="text" name="comment" class="form-control" id="comment" value="{{$record->comment}}">
                        </div>
                        
                        <!-- 保存ボタン -->
                        <div>
                            <button type="submit" class="btn btn-primary m-2">
                                保存
                            </button>
                            <a href="{{ url('/?id='.$titles->id.'&page='.$page) }}" class="btn btn-outline-secondary m-2">
                                キャンセル
                            </a>
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

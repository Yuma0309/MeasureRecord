@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-body my-3">
                <div style="font-size:15pt;font-weight:bold;" class="card-title mt-1 mb-4 mx-2">
                    測定値編集フォーム
                </div>
                <div class="col-md-10">
                    @include('common.errors')
                    <form action="{{ url('records/update') }}" method="POST">
                        @csrf

                        <!-- 日付 -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="date">
                                日付
                            </label>
                            <input type="date" name="date" class="form-control" value="{{$record->date}}"/>
                        </div>

                        <!-- 測定値 -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="amount">
                                測定値
                            </label>
                            <input type="text" name="amount" class="form-control" value="{{$record->amount}}">
                        </div>

                        <!-- コメント -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="comment">
                                コメント
                            </label>
                            <input type="text" name="comment" class="form-control" value="{{$record->comment}}">
                        </div>
                        
                        <!-- 保存ボタン -->
                        <div>
                            <button type="submit" class="btn btn-primary m-2">
                                保存
                            </button>
                            <a href="{{ url('/?id='.$titles->id.'&page='.$page) }}" class="btn btn-outline-secondary m-2">
                                キャンセル
                            </a>
                            <!-- id値を送信 -->
                            <input type="hidden" name="id" value="{{$record->id}}">
                            <input type="hidden" name="page" value="{{$page}}">
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

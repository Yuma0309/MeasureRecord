@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mx-0">
        <div class="card">
            <div class="card-body my-3">
                <div style="font-size:15pt;font-weight:bold;" class="card-title mt-1 mb-4 mx-2">
                    タイトル編集フォーム
                </div>
                <div class="col-md-10">
                    @include('common.errors')
                    <form action="{{ url('titles/update/?id='.$title->id.'&page='.$page) }}" method="POST">
                        @csrf

                        <!-- タイトル -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="title">
                                タイトル
                            </label>
                            <input type="text" name="title" class="form-control" value="{{$title->title}}"/>
                        </div>

                        <!-- 単位 -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="unit">
                                測定値の単位
                            </label>
                            <input type="text" name="unit" class="form-control" value="{{$title->unit}}"/>
                        </div>
                        
                        <!-- 保存ボタン/キャンセルボタン -->
                        <div>
                            <button type="submit" class="btn btn-primary m-2">
                                保存
                            </button>
                            <a href="{{ url('titlesindex/?page='.$page) }}" class="btn btn-outline-secondary m-2">
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
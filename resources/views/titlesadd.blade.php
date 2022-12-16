@extends('layouts.app')

@section('content')
<div class="row container">
    <div class="col-md-12">
        @include('common.errors')
        <form action="{{ url('titlesadd/store') }}" method="POST">
            @csrf

            <!-- タイトル -->
            <div class="form-group col-md-6 m-2">
                <label for="title" class="col-sm-3 control-label">タイトル</label>
                <input type="text" name="title" class="form-control">
            </div>

            <!-- 単位 -->
            <div class="form-group col-md-6 m-2">
                <label for="unit">測定値の単位</label>
                <input type="text" name="unit" class="form-control">
            </div>

            <!-- 保存ボタン/キャンセルボタン -->
            <div class="well well-sm">
                <button type="submit" class="btn btn-primary">保存</button>
                <a class="btn btn-link pull-right" href="{{ url('/titlesindex') }}">
                    キャンセル
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
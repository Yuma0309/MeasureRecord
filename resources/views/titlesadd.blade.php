@extends('layouts.app')

@section('content')
<div class="row container">
    <div class="col-md-12">
        @include('common.errors')
        <form action="{{ url('titlesadd/store') }}" method="POST">

            <!-- タイトル -->
            <div class="form-group col-md-6 m-2">
                <label for="comment" class="col-sm-3 control-label">タイトル</label>
                <input type="text" name="title" class="form-control">
            </div>

            <!-- 保存ボタン/キャンセルボタン -->
            <div class="well well-sm">
                <button type="submit" class="btn btn-primary">保存</button>
                <a class="btn btn-link pull-right" href="{{ url('/') }}">
                    キャンセル
                </a>
            </div>

            <!-- CSRF -->
            @csrf
            
        </form>
    </div>
</div>
@endsection
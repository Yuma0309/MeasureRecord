@extends('layouts.app')

@section('content')
<div class="row container">
    <div class="col-md-12">
    @include('common.errors')
    <form action="{{ url('titles/update') }}" method="POST">

        <!-- タイトル -->
        <div class="form-group">
            <label for="title">タイトル</label>
            <input type="text" name="title" class="form-control" value="{{$title->title}}"/>
        </div>
        
        <!-- 保存ボタン/キャンセルボタン -->
        <div class="well well-sm">
            <button type="submit" class="btn btn-primary">保存</button>
            <a class="btn btn-link pull-right" href="{{ url('/titlesindex') }}">
                キャンセル
            </a>
        </div>
         
         <!-- id値を送信 -->
         <input type="hidden" name="id" value="{{$title->id}}">
         
         <!-- CSRF -->
         @csrf
         
    </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="row container">
    <div class="col-md-12">
    @include('common.errors')
    <form action="{{ url('records/update') }}" method="POST">

        <!-- date -->
        <div class="form-group">
            <label for="date">日付</label>
            <input type="date" name="date" class="form-control" value="{{$record->date}}"/>
        </div>

        <!-- amount -->
        <div class="form-group">
            <label for="amount">測定値</label>
            <input type="text" name="amount" class="form-control" value="{{$record->amount}}">
        </div>

        <!-- comment -->
        <div class="form-group">
            <label for="comment">コメント</label>
            <input type="text" name="comment" class="form-control" value="{{$record->comment}}">
        </div>
        
        <!-- 保存ボタン/キャンセルボタン -->
        <div class="well well-sm">
            <button type="submit" class="btn btn-primary">保存</button>
            <a class="btn btn-link pull-right" href="{{ url('/') }}">
                キャンセル
            </a>
        </div>
         
         <!-- id値を送信 -->
         <input type="hidden" name="id" value="{{$record->id}}">
         
         <!-- CSRF -->
         @csrf
         
    </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')

<!-- タイトル -->
<div>{{ $titles->title }}</div>

<div class="row container">
    <div class="col-md-12">
        @include('common.errors')

        <form method="POST">
            @csrf
            <a class="btn btn-link pull-right" href="{{ url('titlesadd') }}">
                追加
            </a>
        </form>

        <form action="{{ url('titlesedit/'.$titles->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                編集
            </button>
        </form>

        <form action="{{ url('title/'.$titles->id) }}" method="POST">
            @csrf
            @method('DELETE')
            
            <button type="submit" class="btn btn-danger">
                削除
            </button>
            <a class="btn btn-link pull-right" href="{{ url('/') }}">
                キャンセル
            </a>
        </form>
        
    </div>
</div>
@endsection


<!-- resources/views/records.blade.php -->
@extends('layouts.app')
@section('content')

    <!-- Bootstrapの定形コード… -->
    <div class="card-body">
        <div class="card-title">
            測定値入力
        </div>

        <!-- バリデーションエラーの表示に使用 -->
        @include('common.errors')

        <!-- 測定値入力 -->
        <form action="{{ url('records') }}" method="POST" class="form-horizontal">
            @csrf

            <!-- タイトル -->
            <div class="form-group">
                <div class="col-sm-6">
                    <input type="text" name="title" class="form-control">
                </div>
            </div>

            <!-- 保存ボタン -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- 測定値一覧 -->
    
@endsection


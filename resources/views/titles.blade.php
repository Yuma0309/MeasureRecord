@extends('layouts.app')

@section('content')
<div class="row container">
    <div class="col-md-12">
        @include('common.errors')
        <form action="{{ url('titles/store') }}" method="POST">
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

            <!-- 保存ボタン -->
            <div class="form-row">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-primary m-2">
                        保存
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mx-0">
        <div class="card">
            <div class="card-body my-3">
                <div style="font-size:15pt;font-weight:bold;" class="card-title mt-1 mb-4 mx-2">
                    タイトル入力フォーム
                </div>
                <div class="col-md-10">
                    @include('common.errors')
                    <form action="{{ url('titles/store') }}" method="POST">
                        @csrf
                        
                        <!-- タイトル -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="title" class="form-label">
                                タイトル
                            </label>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>

                        <!-- 単位 -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="unit" class="form-label">
                                測定値の単位
                            </label>
                            <input type="text" name="unit" class="form-control" id="unit">
                        </div>

                        <!-- 保存ボタン -->
                        <div>
                            <button type="submit" class="btn btn-primary m-2">
                                保存
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
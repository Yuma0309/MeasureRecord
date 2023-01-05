@extends('layouts.app')

@section('content')

@if(!empty($successMessage))
    <div class="alert alert-success text-center" role="alert">
        {{ $successMessage }}
    </div>
@endif

@if(!empty($errorMessage))
    <div class="alert alert-danger text-center" role="alert">
        {{ $errorMessage }}
    </div>
@endif

<div class="card m-5">

    <!-- ヘッダー -->
    <div class="card-header">
        メール送信
    </div>
    <div class="card-body">
        @include('common.errors')
        <form action="{{ route('mail.send') }}" method="POST">
            @csrf

            <!-- 送信先のメールアドレス -->
            <div class="form-group mb-3">
                <label for="email" class="form-label">
                    メールアドレス
                </label>
                <input type="email" name="email" class="form-control" id="email">
            </div>

            <!-- 件名 -->
            <div class="form-group mb-3">
                <label for="subject" class="form-label">
                    件名
                </label>
                <input type="text" name="subject" class="form-control" id="subject">
            </div>

            <!-- メールの本文 -->
            <div class="form-group mb-3">
                <label for="contents" class="form-label">
                    内容
                </label>
                <textarea name="contents" class="form-control" id="contents" style="min-height:15em"></textarea>
            </div>

            <!-- 送信ボタン -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">
                    送信する
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

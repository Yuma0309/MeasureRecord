
<!-- resources/views/records.blade.php -->
@extends('layouts.app')

@section('content')

    <!-- タイトル -->
    <form method="POST">
        @csrf
        <a class="btn btn-link pull-right" href="{{ url('titlesindex') }}">
            {{ $titles->title }}
        </a>
        <!-- id値を送信 -->
        <input type="hidden" name="id" value="{{$titles->id}}">
    </form>

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
            <div class="form-row">
                <!-- 日付 -->
                <div class="form-group col-md-6 m-2">
                    <label for="date" class="col-sm-3 control-label">日付</label>
                    <input type="date" name="date" class="form-control">
                </div>
                <!-- 測定値 -->
                <div class="form-group col-md-6 m-2">
                    <label for="amount" class="col-sm-3 control-label">測定値</label>
                    <input type="text" name="amount" class="form-control">
                </div>
                <!-- コメント -->
                <div class="form-group col-md-6 m-2">
                    <label for="comment" class="col-sm-3 control-label">コメント</label>
                    <input type="text" name="comment" class="form-control">
                </div>
            </div>

            <!-- 保存ボタン -->
            <div class="form-row">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-primary m-2">
                        保存
                    </button>
                    <!-- id値を送信 -->
                    <input type="hidden" name="id" value="{{$titles->id}}">
                </div>
            </div>
        </form>
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- 検索 -->
    <div>
        <form action="{{ url('records/search') }}" method="POST">
            @csrf
            <input type="text" name="keyword" value="{{ $keyword }}">
            <input type="submit" value="検索">
            <!-- id値を送信 -->
            <input type="hidden" name="id" value="{{$titles->id}}">
        </form>
    </div>

    <!-- 測定値一覧 -->
    @if (isset($records))
        @if (count($records) > 0)
            <div class="card-body">
                <div class="card-body">
                    <table class="table table-striped task-table">
                        <!-- テーブルヘッダー -->
                        <thead>
                            <th>測定値一覧</th>
                            <th>&nbsp;</th>
                            <tr>

                                <th>
                                    <form action="{{ url('/?sort=date') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            日付
                                        </button>
                                        <!-- id値を送信 -->
                                        <input type="hidden" name="id" value="{{$titles->id}}">
                                    </form>
                                </th>

                                <th>
                                    <form action="{{ url('/?sort=amount') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            測定値
                                        </button>
                                        <!-- id値を送信 -->
                                        <input type="hidden" name="id" value="{{$titles->id}}">
                                    </form>
                                </th>
                                <th>
                                    <form action="{{ url('/?sort=comment') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            コメント
                                        </button>
                                        <!-- id値を送信 -->
                                        <input type="hidden" name="id" value="{{$titles->id}}">
                                    </form>
                                </th>
                            </tr>
                        </thead>
                        <!-- テーブル本体 -->
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <!-- 日付 -->
                                    <td class="table-text">
                                        <div>{{ $record->date }}</div>
                                    </td>

                                    <!-- 測定値 -->
                                    <td class="table-text">
                                        <div>{{ $record->amount }}</div>
                                    </td>

                                    <!-- コメント -->
                                    <td class="table-text">
                                        <div>{{ $record->comment }}</div>
                                    </td>

                                    <!-- 編集ボタン -->
                                    <td>
                                        <form action="{{ url('recordsedit/'.$record->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                編集
                                            </button>
                                        </form>
                                    </td>

                                    <!-- 削除ボタン -->
                                    <td>
                                        <form action="{{ url('record/'.$record->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="submit" class="btn btn-danger">
                                                削除
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ぺジネーション -->
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    {{ $records->appends([
                        'id' => $titles->id,
                        'sort' => $sort
                    ])->links()}}
                </div>
            </div>
            
        @endif
    @endif
@endsection

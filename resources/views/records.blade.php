
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
            <div class="form-row">
                <div class="form-group col-md-6 m-2">
                    <input type="text" name="title" class="form-control">
                </div>
            </div>

            <!-- 保存ボタン -->
            <div class="form-row">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-primary m-2">
                        Save
                    </button>
                </div>
            </div>
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
                        </thead>
                        <!-- テーブル本体 -->
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <!-- 測定値 -->
                                    <td class="table-text">
                                        <div>{{ $record->amount }}</div>
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
        @endif
    @endif
@endsection

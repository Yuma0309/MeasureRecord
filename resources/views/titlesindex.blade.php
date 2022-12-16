@extends('layouts.app')

@section('content')

    <div class="row container">
        <div class="col-md-12">
            @include('common.errors')
            <form method="POST">
                @csrf
                <a class="btn btn-link pull-right" href="{{ url('titlesadd') }}">
                    追加
                </a>
            </form>
        </div>
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- タイトル一覧 -->
    @if (isset($titles))
        @if (count($titles) > 0)
            <div class="card-body">
                <div class="card-body">
                    <table class="table table-striped task-table">
                        <!-- テーブルヘッダー -->
                        <thead>
                            <th>タイトル一覧</th>
                            <th>&nbsp;</th>
                        </thead>
                        <!-- テーブル本体 -->
                        <tbody>
                            @foreach ($titles as $title)
                                <tr>
                                    <!-- タイトル -->
                                    <td class="table-text">
                                        <form action="{{ url('/') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">{{ $title->title }}</button>
                                            <!-- id値を送信 -->
                                            <input type="hidden" name="id" value="{{$title->id}}">
                                        </form>
                                    </td>

                                    <td>
                                        <div>
                                            測定値の単位：{{ $title->unit }}
                                        </div>
                                    </td>

                                    <!-- 編集ボタン -->
                                    <td>
                                        <form action="{{ url('titlesedit/'.$title->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                編集
                                            </button>
                                        </form>
                                    </td>

                                    <!-- 削除ボタン -->
                                    <td>
                                        <form action="{{ url('title/'.$title->id) }}" method="POST">
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
                    {{ $titles->links()}}
                </div>
            </div>
            
        @endif
    @endif
@endsection

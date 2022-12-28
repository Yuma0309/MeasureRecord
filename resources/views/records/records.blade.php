@extends('layouts.app')

@section('content')

<!-- タイトル -->
<form method="POST">
    @csrf
    <div class="text-center mb-2">
        <a href="{{ url('titlesindex') }}" style="font-size:15pt;font-weight:bold;" class="btn btn-outline-dark">
            {{ $titles->title }}
        </a>
    </div>
    <!-- id値を送信 -->
    <input type="hidden" name="id" value="{{$titles->id}}">
</form>

<div class="row mx-0">
    <div class="col-md-1">
    </div>
    <div class="col-md-5 my-3">
        <div class="card">
            <div class="card-body my-3">
                <div style="font-size:15pt;font-weight:bold;" class="card-title mt-1 mb-4 mx-2">
                    測定値入力フォーム
                </div>
                <div>
                    @include('common.errors')
                    <form action="{{ url('records') }}" method="POST">
                        @csrf

                        <!-- 日付 -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="date">
                                日付
                            </label>
                            <input type="date" name="date" class="form-control">
                        </div>

                        <!-- 測定値 -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="amount">
                                測定値
                            </label>
                            <input type="text" name="amount" class="form-control">
                        </div>

                        <!-- コメント -->
                        <div class="form-group mt-1 mb-3 mx-2">
                            <label for="comment">
                                コメント
                            </label>
                            <input type="text" name="comment" class="form-control">
                        </div>

                        <!-- 保存ボタン -->
                        <div>
                            <button type="submit" class="btn btn-primary m-2">
                                保存
                            </button>
                            <!-- id値を送信 -->
                            <input type="hidden" name="id" value="{{$titles->id}}">
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- 折れ線グラフ -->
    <div class="col-md-5 my-3">
        @if (isset($titles->unit))
            <div class="text-secondary mx-2">
                ({{ $titles->unit }})
            </div>
        @endif
        <canvas id="myChart"></canvas>
        <!-- <script src="{{ asset('js/app.js') }}" ></script> -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.1.1/dist/chart.umd.min.js"></script>
        <script>
            var labels = <?php echo json_encode($date) ?>;
            var label = <?php echo json_encode($titles->title) ?>;
            var data = <?php echo json_encode($amount) ?>;
            window.addEventListener('load', function () {
                const ctx = document.getElementById("myChart").getContext("2d");
                const myChart = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: label,
                                spanGaps : true,
                                data: data,
                                borderColor: "rgb(30, 144, 255)",
                                backgroundColor: "rgba(135, 206, 250, 0.5)",
                            },
                        ],
                    },
                });
            })
        </script>
    </div>

    @if (session('message'))
        <div class="alert alert-success text-center">
            {{ session('message') }}
        </div>
    @endif

</div>

<!-- 検索 -->
<div class="text-end my-3 mx-3">
    <form action="{{ url('records/search') }}" method="POST">
        @csrf
        <input type="text" name="keyword" value="{{ $keyword }}">
        <input type="submit" value="検索">
        <!-- id値を送信 -->
        <input type="hidden" name="id" value="{{$titles->id}}">
        <input type="hidden" name="sort" value="{{$sort}}">
        <input type="hidden" name="sortNumber" value="{{$sortNumber}}">
    </form>
</div>

<!-- 測定値一覧 -->
@if (isset($records))
    <div style="font-size:15pt;font-weight:bold;" class="text-center my-2 mx-2">
        測定値一覧
    </div>
    <table class="table table-striped">

        <!-- テーブルヘッダー -->
        <thead>
            <tr>

                <!-- 日付の並べ替えボタン -->
                <th style="width:24%" class="text-center">
                    <form action="{{ url('/?sort=date') }}" method="POST">
                        @csrf
                        <button style="font-size:9pt;font-weight:bold;" type="submit" class="btn btn-outline-primary w-100">
                            日付
                        </button>
                        <!-- id値を送信 -->
                        <input type="hidden" name="id" value="{{$titles->id}}">
                        <input type="hidden" name="sortNumber" value="{{$sortNumber}}">
                    </form>
                </th>

                <!-- 測定値の並べ替えボタン -->
                <th style="width:28%" class="text-center">
                    <form action="{{ url('/?sort=amount') }}" method="POST">
                        @csrf
                        <button style="font-size:9pt;font-weight:bold;" type="submit" class="btn btn-outline-primary w-100">
                            測定値
                        </button>
                        <!-- id値を送信 -->
                        <input type="hidden" name="id" value="{{$titles->id}}">
                        <input type="hidden" name="sortNumber" value="{{$sortNumber}}">
                    </form>
                </th>

                <!-- コメントの並べ替えボタン -->
                <th style="width:42%" class="text-center">
                    <form action="{{ url('/?sort=comment') }}" method="POST">
                        @csrf
                        <button style="font-size:9pt;font-weight:bold;" type="submit" class="btn btn-outline-primary w-100">
                            コメント
                        </button>
                        <!-- id値を送信 -->
                        <input type="hidden" name="id" value="{{$titles->id}}">
                        <input type="hidden" name="sortNumber" value="{{$sortNumber}}">
                    </form>
                </th>

                <th style="width:3%">
                </th>

                <th style="width:3%">
                </th>

            </tr>
        </thead>

        <!-- テーブル本体 -->
        <tbody>
            @foreach ($records as $record)
                <tr>

                    <!-- 日付 -->
                    <td class="text-center align-middle">
                        <div>
                            {{ $record->date }}
                        </div>
                    </td>

                    <!-- 測定値 -->
                    <td class="text-center align-middle">
                        <div>
                            {{ $record->amount }}
                            @if (isset($titles->unit))
                                {{ $titles->unit }}
                            @endif
                        </div>
                    </td>

                    <!-- コメント -->
                    <td class="text-center align-middle">
                        <div>
                            {{ $record->comment }}
                        </div>
                    </td>

                    <!-- 編集ボタン -->
                    <td class="text-center align-middle">
                        <form action="{{ url('recordsedit') }}" method="POST">
                            @csrf
                            <button style="font-size:7pt;font-weight:bold;" type="submit" class="btn btn-outline-success">
                                編集
                            </button>
                            <input type="hidden" name="record_id" value="{{$record->id}}">
                            <input type="hidden" name="page" value="{{$page}}">
                        </form>
                    </td>

                    <!-- 削除ボタン -->
                    <td class="text-center align-middle">
                        <form action="{{ url('record/'.$record->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button style="font-size:7pt;font-weight:bold;" type="submit" class="btn btn-outline-danger" onclick='return confirm("本当に削除しますか？");'>
                                削除
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ぺジネーション -->
    <div class="pagination justify-content-center my-4">
        {{ $records->appends([
            'id' => $titles->id,
            'sort' => $sort,
            'sortNumber' => $sortNumber,
            'keyword' => $keyword
        ])->links()}}
    </div>
    
@endif
@endsection

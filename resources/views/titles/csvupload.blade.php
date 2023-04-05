@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mx-0">
        <div class="card">
            <div class="card-body my-3">
                <div style="font-size:15pt;font-weight:bold;" class="card-title mt-1 mb-4 mx-2">
                    CSV入力
                </div>
                <div>
                    <p>CSVファイルを選択してください</p>
                    <form action="{{ url('csv/upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="csvFile" id="csvFile" />
                        <button class="btn btn-success m-2">
                            入力
                        </button>
                        <a class="btn btn-outline-secondary m-2" href="{{ url('titlesindex') }}">
                            キャンセル
                        </a>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
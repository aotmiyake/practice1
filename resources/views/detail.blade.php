@extends('layouts.app')

@section('title', '詳細画面')

@section('content')
    <div class="container">
        <h1>詳細画面</h1>

        <div class="detail-table">
            <table>
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $detail->id }}</td>
                    </tr>
                    <tr>
                        <th>商品画像</th>
                        <td>
                            @if ($detail->img_path)
                                <img src="{{ asset('storage/' . $detail->img_path) }}" alt="商品画像" style="max-width: 150px;">
                            @else
                                画像なし
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>商品名</th>
                        <td>{{ $detail->product_name }}</td>
                    </tr>
                    <tr>
                        <th>価格</th>
                        <td>{{ $detail->price }}</td>
                    </tr>
                    <tr>
                        <th>在庫数</th>
                        <td>{{ $detail->stock }}</td>
                    </tr>
                    <tr>
                        <th>メーカー名</th>
                        <td>{{ optional($detail->company)->company_name ?? '不明な会社' }}</td>
                    </tr>
                    <tr>
                        <th>コメント</th>
                        <td>{{ $detail->comment }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="button-group">
            <a href="{{ route('update', ['id' => $detail->id]) }}" class="btn btn-primary">編集</a>
            <a href="{{ route('list') }}" class="btn btn-secondary">戻る</a>
        </div>
    </div>
@endsection
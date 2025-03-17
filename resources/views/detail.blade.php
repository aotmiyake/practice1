@extends('layouts.app')

@section('title', '詳細画面')

@section('content')
    <div class="container">
        <div class="content">
            <h1>詳細画面</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>商品画像</th>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>在庫数</th>
                        <th>メーカー名</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $detail->id }}</td>
                        <td>{{ $detail->img_path }}</td>
                        <td>{{ $detail->product_name }}</td>
                        <td>{{ $detail->price }}</td>
                        <td>{{ $detail->stock }}</td>
                        <td>{{ optional($detail->company)->company_name ?? '不明な会社' }}</td>
                        <td>{{ $detail->comment }}</td>
                        <td>
                        <a href="{{ route('update', ['id'=>$detail->id]) }}" class="btn btn-primary">詳細</a>
                        </td>
                        </td>
                        <td>
                            <a href="{{ route('list') }}" class="btn btn-primary">戻る</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
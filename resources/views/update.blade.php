@extends('layouts.app')

@section('title', '情報更新画面')

@section('content')
    <div class="container">
        <div class="row">
            <h1>情報更新画面</h1>
            
            {{-- 商品画像の表示 --}}
            @if ($update->img_path)
                <div class="form-group mb-4">
                    <label>現在の画像</label><br>
                    <img src="{{ asset('storage/' . $update->img_path) }}" alt="商品画像" style="max-width: 200px;">
                </div>
            @endif

            <form action="{{ route('infoUpdate', ['id' => $update->id]) }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="id">商品ID</label>
                    <input type="text" class="form-control" id="id" name="id" value="{{ $update->id }}" readonly>
                </div>

                <div class="form-group">
                    <label for="product_name">商品名</label>
                    <input type="text" class="form-control" id="product_name" name="product_name"
                        placeholder="{{ $update->product_name }}"
                        value="{{ old('product_name', $update->product_name) }}">
                    @if($errors->has('product_name'))
                        <p>{{ $errors->first('product_name') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="company_id">メーカー名</label>
                    <select class="form-control" id="company_id" name="company_id">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $update->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">値段</label>
                    <input type="text" class="form-control" id="price" name="price"
                        placeholder="{{ $update->price }}"
                        value="{{ old('price', $update->price) }}">
                    @if($errors->has('price'))
                        <p>{{ $errors->first('price') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="stock">在庫</label>
                    <input type="text" class="form-control" id="stock" name="stock"
                        placeholder="{{ $update->stock }}"
                        value="{{ old('stock', $update->stock) }}">
                    @if($errors->has('stock'))
                        <p>{{ $errors->first('stock') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="comment">コメント</label>
                    <textarea class="form-control" id="comment" name="comment" placeholder="{{ $update->comment }}">{{ old('comment', $update->comment) }}</textarea>
                    @if($errors->has('comment'))
                        <p>{{ $errors->first('comment') }}</p>
                    @endif
                </div>

                {{-- 商品画像アップロード欄（必要であれば追加） --}}
                <div class="form-group">
                    <label for="image">商品画像を変更する</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">更新</button>
                    <a href="{{ route('detail', ['id' => $update->id]) }}" class="btn btn-secondary">戻る</a>
                </div>
            </form>
        </div>
    </div>
@endsection
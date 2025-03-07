@extends('layouts.app')

@section('title', '情報更新画面')

@section('content')
    <div class="container">
        <div class="row">
            <h1>情報更新画面</h1>
            <form action="{{ route('infoUpdate' , ['id'=>$update->id]) }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="product_name">商品名</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="商品名" value="{{ old('product_name') }}">
                    @if($errors->has('product_name'))
                        <p>{{ $errors->first('product_name') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="company_id">メーカー名</label>
                    <select class="form-control" id="company_id" name="company_id" placeholder="メーカー名" value="{{ old('company_id') }}">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">値段</label>
                    <input type="text" class="form-control" id="price" name="price" placeholder="値段" value="{{ old('price') }}">
                    @if($errors->has('price'))
                        <p>{{ $errors->first('price') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="stock">在庫</label>
                    <input type="text" class="form-control" id="stock" name="stock" placeholder="在庫" value="{{ old('stock') }}">
                    @if($errors->has('stock'))
                        <p>{{ $errors->first('stock') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="comment">コメント</label>
                    <textarea class="form-control" id="comment" name="comment" placeholder="Comment">{{ old('comment') }}</textarea>
                    @if($errors->has('comment'))
                        <p>{{ $errors->first('comment') }}</p>
                    @endif
                </div>

                <button type="submit" class="btn btn-default">更新</button>
                <a href="{{ route('detail', ['id'=>$update->id]) }}" class="btn btn-primary">戻る</a>
            </form>
        </div>
    </div>
@endsection
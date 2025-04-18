@extends('layouts.app')

@section('content')
<div class="register-container">
    <h1 class="text-center mb-4">アカウント登録</h1>
    <form method="POST" action="{{ route('register') }}" class="register-form">
        @csrf

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input id="name" type="text" name="name"
                class="@error('name') is-invalid @enderror"
                value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email"
                class="@error('email') is-invalid @enderror"
                value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password"
                class="@error('password') is-invalid @enderror"
                required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">パスワード（確認）</label>
            <input id="password-confirm" type="password" name="password_confirmation" required>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary">登録</button>
        </div>
    </form>
</div>
@endsection
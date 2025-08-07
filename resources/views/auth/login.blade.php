@extends('layouts.app')

@section('title', 'ログイン画面')

@section('content')
<div class="login-container">
    <h2 class="text-center mb-4">ログイン</h2>
    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        {{-- メールアドレス --}}
        <div class="form-group mb-3">
            <label for="email">メールアドレス</label>
            <input id="email" type="email"
                class="@error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- パスワード --}}
        <div class="form-group mb-3">
            <label for="password">パスワード</label>
            <input id="password" type="password"
                class="@error('password') is-invalid @enderror"
                name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- チェックボックス --}}
        <div class="remember-wrapper">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">ログイン状態を保持する</label>
        </div>

        {{-- ボタン類 --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-primary w-100">ログイン</button>

            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    パスワードをお忘れですか？
                </a>
            @endif

            <a class="btn btn-secondary w-100" href="{{ route('register') }}">
                アカウントを新規登録する
            </a>
        </div>
    </form>
</div>
@endsection
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>商品一覧画面</title>

    <!-- 外部CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="main-container">
        @if (Route::has('login'))
            <div class="header-links">
                @auth
                    <a href="{{ url('/list') }}" class="link">Home</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="link" style="background: none; border: none; color: #374151; cursor: pointer;">
                            ログアウト
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="link">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="link">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div>
            <form action="{{ route('list') }}" method="GET">
                <input type="text" name="keyword" value="{{ old('keyword', $keyword) }}" placeholder="商品名で検索">
                <select name="company_id">
                    <option value="">すべての会社</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ $company->id == old('company_id', $company_id) ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                <input type="submit" value="検索">
            </form>
        </div>

        <div class="links">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>商品画像</th>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>在庫数</th>
                        <th>メーカー名</th>
                        <th>コメント</th>
                        <th colspan="2">
                            <button onclick="location.href='{{ route('regist') }}'">新規登録</button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td><img src="{{ asset('storage/' . $article->img_path) }}" alt="商品画像"></td>
                            <td>{{ $article->product_name }}</td>
                            <td>{{ $article->price }}</td>
                            <td>{{ $article->stock }}</td>
                            <td>{{ optional($article->company)->company_name ?? '不明な会社' }}</td>
                            <td>{{ $article->comment }}</td>
                            <td>
                                <a href="{{ route('detail', ['id' => $article->id]) }}">
                                    <button>詳細</button>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('delete', ['id' => $article->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-delete">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
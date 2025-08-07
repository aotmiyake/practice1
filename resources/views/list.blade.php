<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>商品一覧画面</title>

        <!-- 外部CSS -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <!-- tablesorter の読み込み -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css" />
    </head>
    <body>
        <div class="main-container">
        <h1>商品一覧画面</h1>
            @if (Route::has('login'))
                <div class="header-links">
                    @auth
                        <a href="{{ url('/list') }}" class="btn d-btn">Home</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn d-btn">
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
            <!-- 検索フォーム -->
            <div class="search-form">
                <form id="search-form" action="{{ route('list') }}" method="GET">
                    <div class="search-form-row">
                        <input type="text" name="keyword" value="{{ old('keyword', $keyword) }}" placeholder="商品名で検索">
                        <select name="company_id">
                            <option value="">すべての会社</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ $company->id == old('company_id', $company_id) ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-form-row">
                        <input type="text" name="price_min" placeholder="価格（下限）" value="{{ old('price_min') }}">
                        <input type="text" name="price_max" placeholder="価格（上限）" value="{{ old('price_max') }}">
                        <input type="text" name="stock_min" placeholder="在庫数（下限）" value="{{ old('stock_min') }}">
                        <input type="text" name="stock_max" placeholder="在庫数（上限）" value="{{ old('stock_max') }}">
                    </div>
                    <div class="search-form-row center">
                        <button type="submit" class="btn btn-primary">検索</button>
                    </div>
                </form>
            </div>

            <div class="links">
                <table id="article-table" class="tablesorter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="sorter-false">商品画像</th>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>在庫数</th>
                            <th>メーカー名</th>
                            <th>コメント</th>
                            <th colspan="2">
                                <button onclick="location.href='{{ route('regist') }}'" class="btn btn-primary">新規登録</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="article-list">
                        @foreach ($articles as $article)
                            <tr id="article-{{ $article->id }}">
                                <td>{{ $article->id }}</td>
                                <td><img src="{{ asset('storage/' . $article->img_path) }}" alt="商品画像"></td>
                                <td>{{ $article->product_name }}</td>
                                <td>{{ $article->price }}</td>
                                <td>{{ $article->stock }}</td>
                                <td>{{ optional($article->company)->company_name ?? '不明な会社' }}</td>
                                <td>{{ $article->comment }}</td>
                                <td>
                                    <a href="{{ route('detail', ['id' => $article->id]) }}" class="btn btn-primary">詳細</a>
                                </td>
                                <td>
                                    <button class="btn btn-delete" onclick="deleteArticle({{ $article->id }})">削除</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>

        <script>
        // tablesorter 初期化
        $(document).ready(function () {
            $("#article-table").tablesorter({
                sortList: [[0, 1]], // ID降順（1 = 降順）
                headers: {
                    1: { sorter: false }, // 商品画像
                    7: { sorter: false }, // 詳細ボタン
                    8: { sorter: false }  // 削除ボタン
                }
            });
        });

        // 検索フォーム非同期処理
        $('#search-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('articles.searchAjax') }}',
                type: 'GET',
                data: $(this).serialize(),
                success: function(data) {
                    // tablesorterのデータ解除（初期化解除）
                    $("#article-table").trigger("destroy");

                    // 一覧をクリア
                    $('#article-list').empty();

                    // 検索結果の表示
                    data.forEach(function(article) {
                        $('#article-list').append(`
                            <tr id="article-${article.id}">
                                <td>${article.id}</td>
                                <td><img src="/storage/${article.img_path}" alt="商品画像" width="100"></td>
                                <td>${article.product_name}</td>
                                <td>${article.price}</td>
                                <td>${article.stock}</td>
                                <td>${article.company?.company_name ?? '不明な会社'}</td>
                                <td>${article.comment ?? ''}</td>
                                <td><a href="{{ url('/detail') }}/${article.id}" class="btn btn-primary">詳細</a></td>
                                <td><button class="btn btn-delete" onclick="deleteArticle(${article.id})">削除</button></td>
                            </tr>
                        `);
                    });

                    // 再度tablesorter初期化
                    $("#article-table").tablesorter({
                        sortList: [[0, 1]],
                        headers: {
                            1: { sorter: false }, // 商品画像
                            7: { sorter: false }, // 詳細
                            8: { sorter: false }  // 削除
                        }
                    });
                },
                error: function() {
                    alert('検索に失敗しました');
                }
            });
        });

        // 削除ボタン非同期処理
        function deleteArticle(id) {
            if (!confirm('本当に削除しますか？')) return;

            $.ajax({
                url: `/practice1/public/articles/${id}`,  
                type: 'POST',
                data: { _method: 'DELETE' },    // ← LaravelがDELETEと認識する擬似フラグ
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                // tablesorter を一度破棄
                $("#article-table").trigger("destroy");

                // 対象行をDOMから削除
                $(`#article-${id}`).remove();

                // 再度 tablesorter を初期化
                $("#article-table").tablesorter({
                    sortList: [[0, 1]],
                    headers: {
                        1: { sorter: false }, // 商品画像
                        7: { sorter: false }, // 詳細
                        8: { sorter: false }  // 削除
                    }
                });

                alert(response.message);
            },
                error: function() {
                    alert('削除に失敗しました');
                }
            });
        }
        </script>
    </body>
</html>
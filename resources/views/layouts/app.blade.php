<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>勤怠管理</title>
    <style>
        body { font-family: sans-serif; max-width: 1000px; margin: 0 auto; padding: 16px; }
        .row { display: flex; gap: 8px; flex-wrap: wrap; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin-bottom: 12px; }
        .error { color: #b91c1c; }
        table { width: 100%; border-collapse: collapse; }
        th,td { border: 1px solid #ddd; padding: 6px; }
    </style>
</head>
<body>
<header class="row" style="justify-content: space-between; align-items: center;">
    <h1>勤怠管理システム</h1>
    @auth
        <form method="POST" action="/logout">@csrf<button>ログアウト</button></form>
    @endauth
</header>
@if (session('status'))<p>{{ session('status') }}</p>@endif
@if ($errors->any())
    <ul class="error">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
@yield('content')
</body>
</html>

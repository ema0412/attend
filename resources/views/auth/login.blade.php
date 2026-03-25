@extends('layouts.app')
@section('content')
<div class="card">
    <h2>ログイン</h2>
    <form method="POST" action="/login">
        @csrf
        <p><input name="email" placeholder="メールアドレス" value="{{ old('email') }}"></p>
        <p><input type="password" name="password" placeholder="パスワード"></p>
        <button>ログイン</button>
    </form>
    <p><a href="/register">会員登録はこちら</a></p>
</div>
@endsection

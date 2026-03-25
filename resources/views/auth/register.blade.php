@extends('layouts.app')
@section('content')
<div class="card">
    <h2>会員登録</h2>
    <form method="POST" action="/register">
        @csrf
        <p><input name="name" placeholder="お名前" value="{{ old('name') }}"></p>
        <p><input name="email" placeholder="メールアドレス" value="{{ old('email') }}"></p>
        <p><input type="password" name="password" placeholder="パスワード"></p>
        <p><input type="password" name="password_confirmation" placeholder="確認用パスワード"></p>
        <button>登録</button>
    </form>
    <p><a href="/login">ログインはこちら</a></p>
</div>
@endsection

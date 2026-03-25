@extends('layouts.app')
@section('content')
<div class="card">
    <h2>管理者ログイン</h2>
    <form method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <p><input name="email" placeholder="メールアドレス"></p>
        <p><input type="password" name="password" placeholder="パスワード"></p>
        <button>ログイン</button>
    </form>
</div>
@endsection

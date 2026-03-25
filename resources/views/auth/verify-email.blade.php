@extends('layouts.app')
@section('content')
<div class="card">
    <h2>メール認証誘導画面</h2>
    <p>メール認証を完了してください。</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button>認証メール再送</button>
    </form>
</div>
@endsection

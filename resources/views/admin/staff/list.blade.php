@extends('layouts.app')
@section('content')
<div class="card">
    <h2>スタッフ一覧</h2>
    <table>
        <tr><th>氏名</th><th>メール</th><th>詳細</th></tr>
        @foreach($staff as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><a href="{{ route('admin.staff.attendance', $user) }}">詳細</a></td>
            </tr>
        @endforeach
    </table>
</div>
@endsection

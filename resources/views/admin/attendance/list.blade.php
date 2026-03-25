@extends('layouts.app')
@section('content')
<div class="card">
    <h2>管理者 勤怠一覧 {{ $date->format('Y-m-d') }}</h2>
    <div class="row">
        <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->subDay()->toDateString()]) }}">前日</a>
        <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->addDay()->toDateString()]) }}">翌日</a>
    </div>
    <table>
        <tr><th>氏名</th><th>出勤</th><th>退勤</th><th>詳細</th></tr>
        @foreach($attendances as $a)
            <tr>
                <td>{{ $a->user->name }}</td>
                <td>{{ optional($a->clock_in_at)->format('H:i') }}</td>
                <td>{{ optional($a->clock_out_at)->format('H:i') }}</td>
                <td><a href="{{ route('admin.attendance.detail', $a) }}">詳細</a></td>
            </tr>
        @endforeach
    </table>
</div>
@endsection

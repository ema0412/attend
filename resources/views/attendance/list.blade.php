@extends('layouts.app')
@section('content')
<div class="card">
    <h2>{{ $month->format('Y年m月') }}</h2>
    <div class="row">
        <a href="{{ route('attendance.list', ['month' => $month->copy()->subMonth()->format('Y-m')]) }}">前月</a>
        <a href="{{ route('attendance.list', ['month' => $month->copy()->addMonth()->format('Y-m')]) }}">翌月</a>
    </div>
    <table>
        <tr><th>日付</th><th>出勤</th><th>退勤</th><th>詳細</th></tr>
        @foreach($attendances as $a)
            <tr>
                <td>{{ $a->work_date->format('Y-m-d') }}</td>
                <td>{{ optional($a->clock_in_at)->format('H:i') }}</td>
                <td>{{ optional($a->clock_out_at)->format('H:i') }}</td>
                <td><a href="{{ route('attendance.detail', $a) }}">詳細</a></td>
            </tr>
        @endforeach
    </table>
</div>
@endsection

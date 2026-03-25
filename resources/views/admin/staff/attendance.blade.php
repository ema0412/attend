@extends('layouts.app')
@section('content')
<div class="card">
    <h2>{{ $user->name }} さんの {{ $month->format('Y年m月') }} 勤怠</h2>
    <div class="row">
        <a href="{{ route('admin.staff.attendance', ['user' => $user->id, 'month' => $month->copy()->subMonth()->format('Y-m')]) }}">前月</a>
        <a href="{{ route('admin.staff.attendance', ['user' => $user->id, 'month' => $month->copy()->addMonth()->format('Y-m')]) }}">翌月</a>
        <a href="{{ route('admin.staff.attendance.csv', ['user' => $user->id, 'month' => $month->format('Y-m')]) }}">CSV出力</a>
    </div>
    <table>
        <tr><th>日付</th><th>出勤</th><th>退勤</th><th>詳細</th></tr>
        @foreach($attendances as $a)
            <tr>
                <td>{{ $a->work_date->format('Y-m-d') }}</td>
                <td>{{ optional($a->clock_in_at)->format('H:i') }}</td>
                <td>{{ optional($a->clock_out_at)->format('H:i') }}</td>
                <td><a href="{{ route('admin.attendance.detail', $a) }}">詳細</a></td>
            </tr>
        @endforeach
    </table>
</div>
@endsection

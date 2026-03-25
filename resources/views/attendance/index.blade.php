@extends('layouts.app')
@section('content')
<div class="card">
    <p>現在日時: {{ $now->format('Y-m-d H:i') }}</p>
    <p>ステータス: {{ $attendance->status }}</p>
    <div class="row">
        @if($attendance->status === \App\Models\Attendance::STATUS_OFF)
            <form method="POST" action="{{ route('attendance.clock-in') }}">@csrf<button>出勤</button></form>
        @endif
        @if($attendance->status === \App\Models\Attendance::STATUS_WORKING)
            <form method="POST" action="{{ route('attendance.break-start') }}">@csrf<button>休憩入</button></form>
            <form method="POST" action="{{ route('attendance.clock-out') }}">@csrf<button>退勤</button></form>
        @endif
        @if($attendance->status === \App\Models\Attendance::STATUS_BREAK)
            <form method="POST" action="{{ route('attendance.break-end') }}">@csrf<button>休憩戻</button></form>
        @endif
    </div>
</div>
@endsection

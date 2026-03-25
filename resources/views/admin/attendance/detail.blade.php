@extends('layouts.app')
@section('content')
<div class="card">
    <h2>管理者 勤怠詳細</h2>
    <p>名前: {{ $attendance->user->name }}</p>
    <p>日付: {{ $attendance->work_date->format('Y-m-d') }}</p>
    <form method="POST" action="{{ route('admin.attendance.update', $attendance) }}">
        @csrf
        <p>出勤 <input name="clock_in_at" value="{{ optional($attendance->clock_in_at)->format('H:i') }}"></p>
        <p>退勤 <input name="clock_out_at" value="{{ optional($attendance->clock_out_at)->format('H:i') }}"></p>
        <p>備考 <input name="note" value="{{ old('note', $attendance->note) }}"></p>
        <button>修正</button>
    </form>
</div>
@endsection

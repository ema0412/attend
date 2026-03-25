@extends('layouts.app')
@section('content')
<div class="card">
    <h2>勤怠詳細</h2>
    <p>名前: {{ $attendance->user->name ?? auth()->user()->name }}</p>
    <p>日付: {{ $attendance->work_date->format('Y-m-d') }}</p>
    @php($pending = $attendance->correctionRequests->firstWhere('status', \App\Models\AttendanceCorrectionRequest::STATUS_PENDING))
    @if($pending)
        <p class="error">承認待ちのため修正はできません。</p>
    @endif
    <form method="POST" action="{{ route('attendance.request-correction', $attendance) }}">
        @csrf
        <p>出勤 <input name="clock_in_at" value="{{ optional($attendance->clock_in_at)->format('H:i') }}"></p>
        <p>退勤 <input name="clock_out_at" value="{{ optional($attendance->clock_out_at)->format('H:i') }}"></p>
        @foreach($attendance->breaks as $i => $break)
            <p>休憩{{ $i+1 }}
                <input name="breaks[{{ $i }}][start]" value="{{ optional($break->started_at)->format('H:i') }}">
                <input name="breaks[{{ $i }}][end]" value="{{ optional($break->ended_at)->format('H:i') }}">
            </p>
        @endforeach
        <p>備考 <input name="note" value="{{ old('note', $attendance->note) }}"></p>
        <button @if($pending) disabled @endif>修正申請</button>
    </form>
</div>
@endsection

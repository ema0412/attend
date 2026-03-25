@extends('layouts.app')
@section('content')
<div class="card">
    <h2>修正申請承認</h2>
    <p>{{ $requestModel->attendance->user->name }} / {{ $requestModel->attendance->work_date->format('Y-m-d') }}</p>
    <p>申請出勤: {{ $requestModel->requested_clock_in_at->format('H:i') }}</p>
    <p>申請退勤: {{ $requestModel->requested_clock_out_at->format('H:i') }}</p>
    <p>備考: {{ $requestModel->requested_note }}</p>
    <form method="POST" action="{{ route('stamp-request.approve', $requestModel) }}">
        @csrf
        <button>承認</button>
    </form>
</div>
@endsection

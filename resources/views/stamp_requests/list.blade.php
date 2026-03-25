@extends('layouts.app')
@section('content')
<div class="card">
    <h2>承認待ち</h2>
    <table>
        <tr><th>対象</th><th>日付</th><th>詳細</th></tr>
        @foreach($pending as $r)
            <tr>
                <td>{{ $r->attendance->user->name }}</td>
                <td>{{ $r->attendance->work_date->format('Y-m-d') }}</td>
                <td>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('stamp-request.approve-form', $r) }}">詳細</a>
                    @else
                        承認待ち
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
<div class="card">
    <h2>承認済み</h2>
    <table>
        <tr><th>対象</th><th>日付</th></tr>
        @foreach($approved as $r)
            <tr>
                <td>{{ $r->attendance->user->name }}</td>
                <td>{{ $r->attendance->work_date->format('Y-m-d') }}</td>
            </tr>
        @endforeach
    </table>
</div>
@endsection

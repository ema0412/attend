<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\AttendanceCorrectionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $request->user()->id, 'work_date' => $today],
            ['status' => Attendance::STATUS_OFF]
        );

        return view('attendance.index', [
            'attendance' => $attendance->load('breaks'),
            'now' => now(),
        ]);
    }

    public function clockIn(Request $request)
    {
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $request->user()->id, 'work_date' => today()],
            ['status' => Attendance::STATUS_OFF]
        );

        if ($attendance->clock_in_at) {
            return back()->withErrors(['clock' => '出勤は1日1回までです']);
        }

        $attendance->update([
            'clock_in_at' => now(),
            'status' => Attendance::STATUS_WORKING,
        ]);

        return back();
    }

    public function breakStart(Request $request)
    {
        $attendance = Attendance::where('user_id', $request->user()->id)->whereDate('work_date', today())->firstOrFail();
        AttendanceBreak::create(['attendance_id' => $attendance->id, 'started_at' => now()]);
        $attendance->update(['status' => Attendance::STATUS_BREAK]);

        return back();
    }

    public function breakEnd(Request $request)
    {
        $attendance = Attendance::where('user_id', $request->user()->id)->whereDate('work_date', today())->firstOrFail();
        $latest = $attendance->breaks()->whereNull('ended_at')->latest('id')->firstOrFail();
        $latest->update(['ended_at' => now()]);
        $attendance->update(['status' => Attendance::STATUS_WORKING]);

        return back();
    }

    public function clockOut(Request $request)
    {
        $attendance = Attendance::where('user_id', $request->user()->id)->whereDate('work_date', today())->firstOrFail();

        if ($attendance->clock_out_at) {
            return back()->withErrors(['clock' => '退勤は1日1回までです']);
        }

        $attendance->update([
            'clock_out_at' => now(),
            'status' => Attendance::STATUS_DONE,
        ]);

        return back()->with('status', 'お疲れ様でした。');
    }

    public function list(Request $request)
    {
        $month = Carbon::parse($request->input('month', now()->format('Y-m')));
        $attendances = Attendance::where('user_id', $request->user()->id)
            ->whereBetween('work_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->with('breaks')
            ->orderBy('work_date')
            ->get();

        return view('attendance.list', compact('attendances', 'month'));
    }

    public function detail(Attendance $attendance)
    {
        $this->authorizeOwner($attendance);

        return view('attendance.detail', [
            'attendance' => $attendance->load('breaks', 'correctionRequests'),
        ]);
    }

    public function requestCorrection(AttendanceUpdateRequest $request, Attendance $attendance)
    {
        $this->authorizeOwner($attendance);

        $pending = $attendance->correctionRequests()->where('status', AttendanceCorrectionRequest::STATUS_PENDING)->exists();
        if ($pending) {
            return back()->withErrors(['request' => '承認待ちのため修正はできません。']);
        }

        AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'applicant_user_id' => $request->user()->id,
            'requested_clock_in_at' => Carbon::parse($attendance->work_date->format('Y-m-d') . ' ' . $request->clock_in_at),
            'requested_clock_out_at' => Carbon::parse($attendance->work_date->format('Y-m-d') . ' ' . $request->clock_out_at),
            'requested_note' => $request->note,
            'payload_breaks' => $request->input('breaks', []),
            'status' => AttendanceCorrectionRequest::STATUS_PENDING,
        ]);

        return redirect()->route('stamp-request.list');
    }

    private function authorizeOwner(Attendance $attendance): void
    {
        abort_unless(auth()->id() === $attendance->user_id, 403);
    }
}

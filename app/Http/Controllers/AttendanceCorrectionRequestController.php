<?php

namespace App\Http\Controllers;

use App\Models\AttendanceBreak;
use App\Models\AttendanceCorrectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceCorrectionRequestController extends Controller
{
    public function list(Request $request)
    {
        $query = AttendanceCorrectionRequest::with('attendance.user')
            ->where('applicant_user_id', $request->user()->id);

        if ($request->user()->is_admin) {
            $query = AttendanceCorrectionRequest::with('attendance.user');
        }

        return view('stamp_requests.list', [
            'pending' => (clone $query)->where('status', AttendanceCorrectionRequest::STATUS_PENDING)->latest()->get(),
            'approved' => (clone $query)->where('status', AttendanceCorrectionRequest::STATUS_APPROVED)->latest()->get(),
        ]);
    }

    public function approveForm(AttendanceCorrectionRequest $attendanceCorrectionRequest)
    {
        return view('stamp_requests.approve', ['requestModel' => $attendanceCorrectionRequest->load('attendance.user')]);
    }

    public function approve(Request $request, AttendanceCorrectionRequest $attendanceCorrectionRequest)
    {
        DB::transaction(function () use ($request, $attendanceCorrectionRequest) {
            $attendance = $attendanceCorrectionRequest->attendance;
            $attendance->update([
                'clock_in_at' => $attendanceCorrectionRequest->requested_clock_in_at,
                'clock_out_at' => $attendanceCorrectionRequest->requested_clock_out_at,
                'note' => $attendanceCorrectionRequest->requested_note,
            ]);

            $attendance->breaks()->delete();
            foreach ($attendanceCorrectionRequest->payload_breaks ?? [] as $break) {
                if (! empty($break['start']) && ! empty($break['end'])) {
                    AttendanceBreak::create([
                        'attendance_id' => $attendance->id,
                        'started_at' => $attendance->work_date->format('Y-m-d') . ' ' . $break['start'],
                        'ended_at' => $attendance->work_date->format('Y-m-d') . ' ' . $break['end'],
                    ]);
                }
            }

            $attendanceCorrectionRequest->update([
                'status' => AttendanceCorrectionRequest::STATUS_APPROVED,
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);
        });

        return redirect()->route('stamp-request.list');
    }
}

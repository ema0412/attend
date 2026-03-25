<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = Carbon::parse($request->input('date', today()->toDateString()));
        $attendances = Attendance::with('user', 'breaks')
            ->whereDate('work_date', $date)
            ->orderBy('user_id')
            ->get();

        return view('admin.attendance.list', compact('attendances', 'date'));
    }

    public function detail(Attendance $attendance)
    {
        return view('admin.attendance.detail', ['attendance' => $attendance->load('user', 'breaks')]);
    }

    public function update(AttendanceUpdateRequest $request, Attendance $attendance)
    {
        $attendance->update(['note' => $request->note]);

        return back()->with('status', '修正しました');
    }
}

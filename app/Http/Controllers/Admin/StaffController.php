<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::query()->where('is_admin', false)->orderBy('id')->get(['id', 'name', 'email']);

        return view('admin.staff.list', compact('staff'));
    }

    public function attendance(User $user)
    {
        $month = Carbon::parse(request('month', now()->format('Y-m')));
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('work_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->with('breaks')
            ->orderBy('work_date')
            ->get();

        return view('admin.staff.attendance', compact('user', 'attendances', 'month'));
    }

    public function csv(User $user): StreamedResponse
    {
        $month = Carbon::parse(request('month', now()->format('Y-m')));
        $rows = Attendance::where('user_id', $user->id)
            ->whereBetween('work_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->orderBy('work_date')
            ->get();

        return response()->streamDownload(function () use ($rows) {
            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['日付', '出勤', '退勤', '備考']);
            foreach ($rows as $row) {
                fputcsv($fp, [
                    $row->work_date?->format('Y-m-d'),
                    optional($row->clock_in_at)->format('H:i'),
                    optional($row->clock_out_at)->format('H:i'),
                    $row->note,
                ]);
            }
            fclose($fp);
        }, "{$user->id}-{$month->format('Y-m')}.csv");
    }
}

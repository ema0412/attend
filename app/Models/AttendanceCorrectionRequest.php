<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCorrectionRequest extends Model
{
    protected $fillable = [
        'attendance_id',
        'applicant_user_id',
        'requested_clock_in_at',
        'requested_clock_out_at',
        'requested_note',
        'payload_breaks',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'requested_clock_in_at' => 'datetime',
        'requested_clock_out_at' => 'datetime',
        'payload_breaks' => 'array',
        'approved_at' => 'datetime',
    ];

    public const STATUS_PENDING = '承認待ち';
    public const STATUS_APPROVED = '承認済み';

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}

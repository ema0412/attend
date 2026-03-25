<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clock_in_at' => ['required', 'date_format:H:i'],
            'clock_out_at' => ['required', 'date_format:H:i'],
            'note' => ['required', 'string', 'max:1000'],
            'breaks' => ['array'],
            'breaks.*.start' => ['nullable', 'date_format:H:i'],
            'breaks.*.end' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('clock_in_at') || ! $this->filled('clock_out_at')) {
                return;
            }

            $clockIn = Carbon::createFromFormat('H:i', $this->input('clock_in_at'));
            $clockOut = Carbon::createFromFormat('H:i', $this->input('clock_out_at'));

            if ($clockIn->gt($clockOut)) {
                $validator->errors()->add('clock_in_at', '出勤時間もしくは退勤時間が不適切な値です');
            }

            foreach ($this->input('breaks', []) as $i => $break) {
                if (empty($break['start']) || empty($break['end'])) {
                    continue;
                }

                $start = Carbon::createFromFormat('H:i', $break['start']);
                $end = Carbon::createFromFormat('H:i', $break['end']);

                if ($start->lt($clockIn) || $start->gt($clockOut)) {
                    $validator->errors()->add("breaks.$i.start", '休憩時間が不適切な値です');
                }

                if ($end->gt($clockOut) || $start->gt($end)) {
                    $validator->errors()->add("breaks.$i.end", '休憩時間もしくは退勤時間が不適切な値です');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }
}

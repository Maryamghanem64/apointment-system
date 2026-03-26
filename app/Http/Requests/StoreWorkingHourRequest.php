<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkingHourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy handles
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'day_of_week.in' => 'Select a valid day of the week.',
            'end_time.after' => 'End time must be after start time.',
        ];
    }
}


<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow admin, provider, client based on routes/middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'provider_id' => ['required', 'exists:providers,id'],
            'start_time' => ['required', 'date', 'after:now'],
            'end_time' => ['required', 'date', 'after:start_time', 'before:+1 year'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'client_note' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:stripe,free'],
        ];
    }

    /**
     * Get custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'service_id.required' => 'Please select a service.',
            'service_id.exists' => 'Selected service is invalid.',
            'provider_id.required' => 'Please select a provider.',
            'provider_id.exists' => 'Selected provider is invalid.',
            'start_time.required' => 'Start time is required.',
            'start_time.date' => 'Start time must be a valid date.',
            'start_time.after' => 'Start time must be in the future.',
            'end_time.required' => 'End time is required.',
            'end_time.after' => 'End time must be after start time.',
            'end_time.before' => 'End time cannot be more than 1 year in the future.',
            'duration_minutes.required' => 'Duration is required.',
            'duration_minutes.integer' => 'Duration must be a whole number.',
            'duration_minutes.min' => 'Minimum duration is 15 minutes.',
            'duration_minutes.max' => 'Maximum duration is 8 hours.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Payment method must be stripe or free.',
        ];
    }
}


<?php

namespace App\Http\Requests;

use App\Models\Provider;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Providers can create services for themselves
        // Admins can create global services
        return $this->user()->hasRole('admin') || 
               $this->user()->hasRole('provider');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services')->ignore($this->service?->id ?? 0),
            ],
            'description' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:15|max:480', // 15min to 8h
            'price' => 'required|numeric|min:0|max:99999.99',
            'color' => 'nullable|string|max:7|regex:/^#?[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'provider_id' => [
                'nullable',
                'exists:providers,id',
                Rule::when($this->user()->hasRole('provider'), fn() => 'in:' . $this->user()->providers()->first()?->id),
            ],
            'service_category_id' => 'nullable|exists:service_categories,id',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Service name is required.',
            'duration.min' => 'Minimum duration is 15 minutes.',
            'price.min' => 'Price cannot be negative.',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'provider_id' => $this->user()->hasRole('provider') 
                ? $this->user()->providers()->first()?->id 
                : ($this->provider_id ?? null),
        ]);
    }

    /**
     * Production-ready: Custom attributes for better UX
     */
    public function attributes(): array
    {
        return [
            'name' => 'service name',
            'duration' => 'service duration (minutes)',
            'price' => 'service price',
        ];
    }
}


<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admins can update request status
        return auth()->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['new', 'contacted', 'completed', 'archived']),
            ],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ];
    }

    #[\Override]
    public function messages(): array
    {
        return [
            'status.in' => __('validation.contact_request.status.in'),
        ];
    }
}

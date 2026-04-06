<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public form
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'min:5', 'max:255'],
            'message' => ['required', 'string', 'min:20', 'max:2000'],
            'request_type' => [
                'required',
                Rule::in(['booking', 'general', 'collaboration', 'other']),
            ],
            'preferred_language' => [
                'nullable',
                Rule::in(['ru', 'en', 'eo']),
            ],
            // Honeypot for spam protection
            'website' => ['nullable', 'max:0'],
        ];
    }

    #[\Override]
    public function prepareForValidation(): void
    {
        // Sanitize phone: keep only digits, +, (), -, spaces
        if ($this->filled('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^\d+\-\(\)\s]/', '', $this->phone),
            ]);
        }

        // Default language to site default (ru) if not provided
        if (! $this->filled('preferred_language')) {
            $this->merge([
                'preferred_language' => app()->getLocale(),
            ]);
        }
    }

    #[\Override]
    public function messages(): array
    {
        return [
            'name.required' => __('validation.name.required'),
            'email.required' => __('validation.email.required'),
            'email.email' => __('validation.email.email'),
            'subject.min' => __('validation.subject.min'),
            'message.min' => __('validation.message.min'),
            'message.max' => __('validation.message.max'),
            'request_type.in' => __('validation.request_type.in'),
        ];
    }

    #[\Override]
    public function attributes(): array
    {
        return [
            'name' => __('fields.name'),
            'email' => __('fields.email'),
            'phone' => __('fields.phone'),
            'subject' => __('fields.subject'),
            'message' => __('fields.message'),
            'request_type' => __('fields.request_type'),
        ];
    }

    /**
     * Get validated data for model creation
     */
    public function toModelData(): array
    {
        return [
            'name' => $this->validated('name'),
            'email' => $this->validated('email'),
            'phone' => $this->validated('phone'),
            'subject' => $this->validated('subject'),
            'message' => $this->validated('message'),
            'request_type' => $this->validated('request_type'),
            'preferred_language' => $this->validated('preferred_language'),
            'ip_address' => $this->ip(),
        ];
    }
}

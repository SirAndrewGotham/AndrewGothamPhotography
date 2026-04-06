<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public commenting (with moderation)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:3', 'max:1000'],
            // Guest fields (only if not authenticated)
            'guest_name' => [
                Rule::requiredIf(fn () => ! auth()->check()),
                'string',
                'max:255',
            ],
            'guest_email' => [
                Rule::requiredIf(fn () => ! auth()->check()),
                'email',
                'max:255',
            ],
            // Parent comment for replies
            'parent_id' => [
                'nullable',
                'exists:comments,id',
                function (string $attribute, mixed $value, \Closure $fail) {
                    // Prevent nested replies beyond 2 levels
                    $parent = Comment::find($value);
                    if ($parent?->parent_id) {
                        $fail(__('validation.comment.nested_limit'));
                    }
                },
            ],
            // Honeypot
            'website' => ['nullable', 'max:0'],
        ];
    }

    #[\Override]
    public function prepareForValidation(): void
    {
        // Strip tags from content but allow basic formatting
        if ($this->filled('content')) {
            $this->merge([
                'content' => strip_tags($this->content, '<p><br><strong><em>'),
            ]);
        }
    }

    #[\Override]
    public function messages(): array
    {
        return [
            'content.required' => __('validation.comment.content.required'),
            'content.min' => __('validation.comment.content.min'),
            'guest_name.required' => __('validation.comment.guest_name.required'),
            'guest_email.email' => __('validation.comment.guest_email.email'),
            'parent_id.exists' => __('validation.comment.parent_id.exists'),
        ];
    }

    #[\Override]
    public function attributes(): array
    {
        return [
            'content' => __('fields.comment'),
            'guest_name' => __('fields.name'),
            'guest_email' => __('fields.email'),
        ];
    }

    /**
     * Get validated data for model creation
     */
    public function toModelData(string $commentableType, int $commentableId): array
    {
        $data = [
            'commentable_type' => $commentableType,
            'commentable_id' => $commentableId,
            'content' => $this->validated('content'),
            'parent_id' => $this->validated('parent_id'),
            'ip_address' => $this->ip(),
            // Comments are unapproved by default (moderation queue)
            'is_approved' => false,
        ];

        // Attach user if authenticated, otherwise guest details
        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        } else {
            $data['guest_name'] = $this->validated('guest_name');
            $data['guest_email'] = $this->validated('guest_email');
        }

        return $data;
    }
}

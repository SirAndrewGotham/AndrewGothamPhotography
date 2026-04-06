<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CommentForm extends Component
{
    public $commentable;

    public string $content = '';

    public ?string $guest_name = '';

    public ?string $guest_email = '';

    public ?int $parent_id = null;

    public ?string $website = ''; // Honeypot

    public function mount($commentable): void
    {
        $this->commentable = $commentable;
    }

    public function submit(): void
    {
        // Honeypot check: bots usually fill hidden fields
        if (! in_array($this->website, [null, '', '0'], true)) {
            $this->addError('website', 'Spam detected.');

            return;
        }

        $rules = [
            'content' => 'required|string|min:3|max:1000',
        ];

        // Only validate guest fields when user is not authenticated
        if (Auth::guest()) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
        }

        $validated = $this->validate($rules);

        $data = [
            'content' => $validated['content'],
            'commentable_type' => $this->commentable->getMorphClass(),
            'commentable_id' => $this->commentable->getKey(),
            'parent_id' => $this->parent_id,
        ];

        if (Auth::guest()) {
            $data['author_name'] = $validated['guest_name'];
            $data['author_email'] = $validated['guest_email'];
        } else {
            $data['user_id'] = Auth::id();
        }

        Comment::create($data);

        Session::flash('comment_status', __('comments.submitted'));
        $this->reset(['content', 'guest_name', 'guest_email', 'website']);
    }

    public function render(): string
    {
        return <<<'BLADE'
<div class="space-y-6">
    @session('comment_status')
    <div class="p-4 bg-success/10 border border-success/20 text-success rounded-lg flex items-center gap-3 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ $value }}</span>
    </div>
    @endsession

    <form wire:submit="submit" class="space-y-4">
        @guest
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="guest_name" class="block text-sm font-medium text-content-secondary mb-1">
                    {{ __('fields.name') }} <span class="text-error">*</span>
                </label>
                <input
                    type="text"
                    id="guest_name"
                    wire:model="guest_name"
                    class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500 transition-all"
                    placeholder="{{ __('contact.placeholders.name') }}"
                >
                @error('guest_name') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="guest_email" class="block text-sm font-medium text-content-secondary mb-1">
                    {{ __('fields.email') }} <span class="text-error">*</span>
                </label>
                <input
                    type="email"
                    id="guest_email"
                    wire:model="guest_email"
                    class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500 transition-all"
                    placeholder="{{ __('contact.placeholders.email') }}"
                >
                @error('guest_email') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
            </div>
        </div>
        @endguest

        @auth
        <div class="flex items-center gap-3 p-3 bg-stage-800/50 border border-stage-700 rounded-lg">
            <div class="w-8 h-8 rounded-full bg-spotlight-950 flex items-center justify-center text-spotlight-500 font-medium text-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <span class="text-content-primary text-sm font-medium">{{ Auth::user()->name }}</span>
        </div>
        @endauth

        <div>
            <label for="content" class="block text-sm font-medium text-content-secondary mb-1">
                {{ __('fields.comment') }} <span class="text-error">*</span>
            </label>
            <textarea
                id="content"
                wire:model="content"
                rows="4"
                class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500 transition-all resize-y"
                placeholder="{{ __('comments.placeholder') }}"
            ></textarea>
            @error('content') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>

        <!-- Honeypot -->
        <input type="text" wire:model="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="submit"
            class="inline-flex items-center justify-center px-6 py-3 bg-spotlight-500 hover:bg-spotlight-400 text-stage-950 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-spotlight-500 focus:ring-offset-2 focus:ring-offset-stage-950 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span wire:loading.remove>{{ __('comments.submit') }}</span>
            <span wire:loading class="inline-flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('comments.sending') }}...
            </span>
        </button>
    </form>
</div>
BLADE;
    }
}

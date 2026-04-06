<?php

use App\Models\Album;
use Illuminate\Support\Facades\Auth;
use function Laravel\Folio\name;
//use function Laravel\Folio\{mount};

name('albums.show');

//mount(function (Album $album) {
//    return ['album' => $album];
//});

new class extends Livewire\Component
{
    public Album $album;

    public ?string $selectedTag = null;

    public bool $showComments = false;

    public function mount(Album $album): void
    {
        // Ensure album is published or user is admin
        if (! $album->is_published && (! Auth::check() || Auth::user()->role !== 'admin')) {
            abort(404);
        }
        $this->album = $album;
    }

    public function with(): array
    {
        $query = $this->album->images()->published()->with('tags');

        if ($this->selectedTag) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $this->selectedTag));
        }

        return [
            'images' => $query->orderBy('sort_order')->paginate(24),
            'availableTags' => $this->album->images->pluck('tags')->flatten()->unique('id')->sortBy('name'),
        ];
    }

    public function toggleComments(): void
    {
        $this->showComments = ! $this->showComments;
    }
};

?>

<x-layouts.app>
    <!-- Album Header -->
    <section class="py-12 md:py-16 border-b border-stage-700">
        <div class="max-w-7xl mx-auto px-4">
            <nav class="flex items-center text-sm text-content-muted mb-6">
                <a href="{{ route('home') }}" class="hover:text-content-primary transition-colors">{{ __('common.home') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <a href="{{ route('albums') }}" class="hover:text-content-primary transition-colors">{{ __('albums.title') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-content-primary">{{ $album->title }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-display font-bold text-content-primary mb-3">
                        {{ $album->title }}
                    </h1>
                    @if($album->description)
                    <p class="text-content-secondary text-lg max-w-3xl">
                        {{ $album->description }}
                    </p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <!-- Tag Filter Dropdown -->
                    @if($availableTags->isNotEmpty())
                    <select
                        wire:model.live="selectedTag"
                        class="px-4 py-2 bg-stage-800 border border-stage-700 rounded-lg text-content-primary text-sm focus:outline-none focus:ring-2 focus:ring-spotlight-500 cursor-pointer"
                    >
                        <option value="">{{ __('albums.all_tags') }}</option>
                        @foreach($availableTags as $tag)
                        <option value="{{ $tag->slug }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    @endif

                    <!-- Comments Toggle -->
                    <button
                        wire:click="toggleComments"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-stage-800 hover:bg-stage-700 border border-stage-700 rounded-lg text-content-primary text-sm transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        {{ $showComments ? __('common.hide_comments') : __('common.show_comments') }}
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Grid (Masonry) -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            @forelse($images as $image)
            @if($loop->first)
            <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
                @endif

                <div class="break-inside-avoid group relative">
                    <a href="{{ route('albums.images.show', [$album, $image]) }}" class="block">
                        <img
                            src="{{ $image->getUrl('thumb') }}"
                            alt="{{ $image->title ?? $album->title }}"
                            class="w-full rounded-lg cursor-pointer transition-transform duration-300 group-hover:scale-[1.02]"
                            loading="lazy"
                        >

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-stage-950/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-end p-4">
                            <div class="w-full">
                                @if($image->title)
                                <p class="text-content-primary text-sm font-medium truncate">{{ $image->title }}</p>
                                @endif
                                @if($image->tags->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($image->tags->take(3) as $tag)
                                    <span class="px-2 py-0.5 bg-stage-900/90 text-content-muted text-xs rounded border border-stage-700">
                                                {{ $tag->name }}
                                            </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>

                @if($loop->last)
            </div>
            @endif
            @empty
            <div class="text-center py-16">
                <p class="text-content-secondary">{{ __('albums.no_images') }}</p>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($images->hasPages())
            <div class="mt-12">
                {{ $images->links() }}
            </div>
            @endif
        </div>
    </section>

    <!-- Comments Section (Scaffolded) -->
    @if($showComments)
    <section class="py-12 border-t border-stage-700">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-2xl font-display font-bold text-content-primary mb-6">
                {{ __('comments.title') }}
            </h2>

            <!-- Comment Form (Scaffolded - uses StoreCommentRequest) -->
            <livewire:comment-form :commentable="$album" />

            <!-- Comments List (Placeholder) -->
            <div class="mt-8 space-y-6">
                @forelse($album->approvedComments()->with('user')->latest()->get() as $comment)
                <article class="bg-stage-900 border border-stage-700 rounded-xl p-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stage-800 flex items-center justify-center flex-shrink-0">
                                    <span class="text-content-primary font-medium">
                                        {{ substr($comment->author_name, 0, 1) }}
                                    </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-content-primary">{{ $comment->author_name }}</span>
                                <time class="text-content-muted text-xs">
                                    {{ $comment->created_at->format('d.m.Y') }}
                                </time>
                            </div>
                            <p class="text-content-secondary">{{ $comment->content }}</p>
                        </div>
                    </div>
                </article>
                @empty
                <p class="text-content-muted text-center py-8">{{ __('comments.be_first') }}</p>
                @endforelse
            </div>
        </div>
    </section>
    @endif
</x-layouts.app>

<?php

use App\Models\Album;
use App\Models\Tag;
use function Laravel\Folio\name;

name('albums');

new class extends Livewire\Component
{
    public ?string $search = null;

    public ?string $tag = null;

    public function with(): array
    {
        $query = Album::published()
            ->withPublishedImages()
            ->withCount('publishedImages');

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        // Tag filter (via relationship)
        if ($this->tag) {
            $query->whereHas('images.tags', fn ($q) => $q->where('slug', $this->tag));
        }

        $albums = $query->orderBy('sort_order')->paginate(12);
        $tags = Tag::orderBy('name')->get();

        return [
            'albums' => $albums,
            'tags' => $tags,
        ];
    }

    public function resetFilters(): void
    {
        $this->search = null;
        $this->tag = null;
    }
};

?>

<x-layouts.app>
    <section class="py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-3xl md:text-4xl font-display font-bold text-content-primary mb-4">
                    {{ __('albums.title') }}
                </h1>
                <p class="text-content-secondary max-w-2xl">
                    {{ __('albums.description') }}
                </p>
            </div>

            <!-- Filters -->
            <div class="mb-8 p-6 bg-stage-900 border border-stage-700 rounded-xl">
                <form wire:submit.prevent class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="{{ __('albums.search_placeholder') }}"
                            class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500"
                        >
                    </div>

                    <!-- Tag Filter -->
                    <div class="md:w-48">
                        <select
                            wire:model.live="tag"
                            class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary focus:outline-none focus:ring-2 focus:ring-spotlight-500 cursor-pointer"
                        >
                            <option value="">{{ __('albums.all_tags') }}</option>
                            @foreach($tags as $t)
                                <option value="{{ $t->slug }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset -->
                    @if($search || $tag)
                        <button
                            type="button"
                            wire:click="resetFilters"
                            class="px-4 py-3 text-content-secondary hover:text-content-primary transition-colors"
                        >
                            {{ __('common.reset') }}
                        </button>
                    @endif
                </form>
            </div>

            <!-- Albums Grid -->
            @forelse($albums as $album)
                @if($loop->first)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @endif

                        <a href="{{ route('albums.show', $album) }}" class="group block">
                            <article class="bg-stage-900 border border-stage-700 rounded-xl overflow-hidden hover:border-spotlight-500/50 transition-all duration-300">
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    @if($album->cover_image_path)
                                        <img
                                            src="{{ asset("storage/{$album->cover_image_path}") }}"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            alt="{{ $album->title }}"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="w-full h-full bg-stage-800 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-content-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-stage-950/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold text-content-primary mb-1 group-hover:text-spotlight-500 transition-colors">
                                        {{ $album->title }}
                                    </h3>
                                    @if($album->description)
                                        <p class="text-content-secondary text-sm line-clamp-2 mb-3">
                                            {{ $album->description }}
                                        </p>
                                    @endif
                                    <div class="flex items-center justify-between text-xs text-content-muted">
                                        <span>{{ $album->published_images_count }} {{ __('common.photos') }}</span>
                                        @if($album->images->first()?->tags->isNotEmpty())
                                            <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        {{ $album->images->first()->tags->first()->name }}
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </a>

                        @if($loop->last)
                    </div>
                @endif
            @empty
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-content-muted mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9.172 16.172a4 4 0 005.656 0M9.172 16.172a4 4 0 01-5.656 0m5.656 0a4 4 0 10-5.656-5.656m5.656 5.656V8m0 8.366V8"></path>
                    </svg>
                    <p class="text-content-secondary">{{ __('albums.no_results') }}</p>
                    @if($search || $tag)
                        <button wire:click="resetFilters" class="mt-4 text-spotlight-500 hover:text-spotlight-400">
                            {{ __('albums.clear_filters') }}
                        </button>
                    @endif
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-12">
                {{ $albums->links() }}
            </div>
        </div>
    </section>
</x-layouts.app>

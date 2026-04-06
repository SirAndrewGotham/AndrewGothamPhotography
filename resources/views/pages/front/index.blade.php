<?php

use App\Models\Album;
use App\Models\Image;
use Livewire\Volt\Component;
use function Laravel\Folio\name;

name('home');

new class extends \Livewire\Component
{
    public function placeholder(): string
    {
        return '<div class="animate-pulse grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="h-48 bg-stage-800 rounded-lg"></div>
            <div class="h-48 bg-stage-800 rounded-lg"></div>
            <div class="h-48 bg-stage-800 rounded-lg"></div>
            <div class="h-48 bg-stage-800 rounded-lg"></div>
        </div>';
    }

    public function with(): array
    {
        // Lazy-load featured albums (published, with published images)
        $featured = Album::published()
            ->withPublishedImages()
            ->withCount('publishedImages')
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        // Recent published images for hero carousel
        $recent = Image::published()
            ->with('album', 'tags')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return [
            'featured' => $featured,
            'recent' => $recent,
        ];
    }
};

?>

<x-layouts.app>
    <!-- Hero Section -->
    <section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0">
            <img
                src="{{ $recent->first()?->getUrl('hero') ?? asset('images/placeholder-hero.jpg') }}"
                class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                alt="{{ __('hero.background_alt') }}"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-stage-950 via-stage-950/80 to-stage-950/40"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center max-w-4xl mx-auto px-4 py-24">
            <h1 class="text-4xl md:text-6xl font-display font-bold text-content-primary mb-6 leading-tight">
                {{ __('hero.title') }}
                <span class="text-spotlight-500 block mt-2">{{ __('hero.subtitle') }}</span>
            </h1>
            <p class="text-lg md:text-xl text-content-secondary mb-8 max-w-2xl mx-auto">
                {{ __('hero.description') }}
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('albums') }}" class="px-8 py-4 bg-spotlight-500 hover:bg-spotlight-400 text-stage-950 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-spotlight-500 focus:ring-offset-2 focus:ring-offset-stage-950">
                    {{ __('hero.cta_portfolio') }}
                </a>
                <a href="{{ route('contact') }}" class="px-8 py-4 border border-stage-700 text-content-primary hover:bg-stage-800 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-stage-700">
                    {{ __('hero.cta_contact') }}
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-content-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Featured Albums -->
    <section class="py-16 md:py-24 bg-stage-900/50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-content-primary mb-4">
                    {{ __('home.featured_title') }}
                </h2>
                <p class="text-content-secondary max-w-2xl mx-auto">
                    {{ __('home.featured_description') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featured as $album)
                    <a href="{{ route('albums.show', $album) }}" class="group block">
                        <article class="bg-stage-900 border border-stage-700 rounded-xl overflow-hidden hover:border-spotlight-500/50 transition-all duration-300">
                            <div class="relative h-48 overflow-hidden">
                                @if($album->cover_image_path)
                                    <img
                                        src="{{ asset("storage/{$album->cover_image_path}") }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        alt="{{ $album->title }}"
                                    >
                                @else
                                    <div class="w-full h-full bg-stage-800 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-content-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1 bg-stage-950/90 text-content-primary text-xs rounded-full border border-stage-700">
                                        {{ $album->published_images_count }} {{ __('common.photos') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-content-primary mb-1 group-hover:text-spotlight-500 transition-colors">
                                    {{ $album->title }}
                                </h3>
                                @if($album->description)
                                    <p class="text-content-secondary text-sm line-clamp-2">
                                        {{ $album->description }}
                                    </p>
                                @endif
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('albums') }}" class="inline-flex items-center text-spotlight-500 hover:text-spotlight-400 font-medium transition-colors">
                    {{ __('common.view_all') }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Quick Stats / Trust Indicators -->
    <section class="py-12 border-t border-stage-700">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl md:text-4xl font-display font-bold text-content-primary mb-1">50+</div>
                    <div class="text-content-muted text-sm">{{ __('home.stats.theaters') }}</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-display font-bold text-content-primary mb-1">500+</div>
                    <div class="text-content-muted text-sm">{{ __('home.stats.productions') }}</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-display font-bold text-content-primary mb-1">10k+</div>
                    <div class="text-content-muted text-sm">{{ __('home.stats.photos') }}</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-display font-bold text-content-primary mb-1">2018</div>
                    <div class="text-content-muted text-sm">{{ __('home.stats.since') }}</div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

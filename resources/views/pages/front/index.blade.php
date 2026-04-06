<?php
// resources/views/pages/front/index.blade.php

use App\Models\Album;
use App\Models\Image;
use function Laravel\Folio\name;

name('home');

//use function Livewire\Volt\{state, computed};
//
//state(['count' => 0]);
//
//computed('double', fn () => $this->count * 2);

// Fetch data directly (Folio pages support this natively)
$featured = Album::published()
    ->withPublishedImages()
    ->withCount('publishedImages')
    ->orderBy('sort_order')
    ->take(4)
    ->get();

$recent = Image::published()
    ->with('album', 'tags')
    ->orderBy('created_at', 'desc')
    ->take(6)
    ->get();
?>

<x-layouts.app>
    {{-- Hero Section --}}
    <section class="relative h-[70vh] min-h-[500px] flex items-center justify-center bg-cover bg-center"
             style="background-image: url('{{ $recent->first()?->getUrl('hero') ?? asset('images/placeholder-hero.jpg') }}')">
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative z-10 text-center text-white px-4 max-w-4xl">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ __('hero.title') }}</h1>
            <p class="text-xl md:text-2xl mb-6">{{ __('hero.subtitle') }}</p>
            <p class="text-lg mb-8 opacity-90">{{ __('hero.description') }}</p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('albums') }}"
                   class="px-6 py-3 bg-white text-gray-900 rounded-lg font-medium hover:bg-gray-100 transition">
                    {{ __('hero.cta_albums') }}
                </a>
                <a href="{{ route('contact') }}"
                   class="px-6 py-3 border-2 border-white text-white rounded-lg font-medium hover:bg-white/10 transition">
                    {{ __('hero.cta_contact') }}
                </a>
            </div>
        </div>
    </section>

    {{-- Featured Albums --}}
    <section class="py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">{{ __('home.featured_title') }}</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ __('home.featured_description') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featured as $album)
{{--                    <a href="{{ route('albums.show', $album) }}" class="group block">--}}
                    <a href="{{ url('/albums/' . $album->slug) }}" class="group block">
                        <div class="relative aspect-square overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800">
                            @if($album->cover_image_path)
                                <img src="{{ $album->cover_image_path }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     alt="{{ $album->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    {{ __('common.no_image') }}
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="absolute bottom-4 left-4 right-4 text-white">
                                    <p class="text-sm">{{ $album->published_images_count ?? $album->images_count }} {{ __('common.photos') }}</p>
                                    <h3 class="font-semibold">{{ $album->title }}</h3>
                                    @if($album->description)
                                        <p class="text-sm opacity-90 line-clamp-2">{{ $album->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('albums') }}"
                   class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                    {{ __('common.view_all') }}
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-12 bg-gray-50 dark:bg-gray-900/50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold">50+</div>
                    <div class="text-gray-600 dark:text-gray-400">{{ __('home.stats.theaters') }}</div>
                </div>
                <div>
                    <div class="text-3xl font-bold">500+</div>
                    <div class="text-gray-600 dark:text-gray-400">{{ __('home.stats.productions') }}</div>
                </div>
                <div>
                    <div class="text-3xl font-bold">10k+</div>
                    <div class="text-gray-600 dark:text-gray-400">{{ __('home.stats.photos') }}</div>
                </div>
                <div>
                    <div class="text-3xl font-bold">2018</div>
                    <div class="text-gray-600 dark:text-gray-400">{{ __('home.stats.since') }}</div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

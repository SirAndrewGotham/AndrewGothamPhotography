<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Image;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Andrew Gotham',
            'email' => 'andrewgotham@mail.ru',
            'role' => 'admin',
            'phone' => '+7 (991) 873-9137',
            'locale' => 'ru',
            'password' => bcrypt('change-me-in-production'),
        ]);

        // Create tags
        $tags = collect([
            ['name' => 'Ballet', 'slug' => 'ballet', 'color' => '#d4af37'],
            ['name' => 'Opera', 'slug' => 'opera', 'color' => '#c0392b'],
            ['name' => 'Drama', 'slug' => 'drama', 'color' => '#8e44ad'],
            ['name' => 'Concert', 'slug' => 'concert', 'color' => '#2980b9'],
            ['name' => 'Backstage', 'slug' => 'backstage', 'color' => '#27ae60'],
            ['name' => 'Portrait', 'slug' => 'portrait', 'color' => '#e67e22'],
        ])->map(fn ($t) => Tag::factory()->create($t));

        // Create album structure
        $portfolio = Album::factory()->published()->create([
            'title' => 'Portfolio',
            'slug' => 'portfolio',
            'description' => 'Selected works from Moscow theaters',
        ]);

        $ballet = Album::factory()->published()->withParent($portfolio)->create([
            'title' => 'Ballet Productions',
            'slug' => 'ballet',
        ]);

        $opera = Album::factory()->published()->withParent($portfolio)->create([
            'title' => 'Opera Performances',
            'slug' => 'opera',
        ]);

        // Create sample images with tags
        Image::factory()
            ->published()
            ->count(12)
            ->forAlbum($ballet->id)
            ->create()
            ->each(fn ($img) => $img->tags()->attach(
                $tags->random(fake()->numberBetween(2, 4))->pluck('id')
            ));

        Image::factory()
            ->published()
            ->count(8)
            ->forAlbum($opera->id)
            ->create()
            ->each(fn ($img) => $img->tags()->attach(
                $tags->random(fake()->numberBetween(1, 3))->pluck('id')
            ));
    }
}

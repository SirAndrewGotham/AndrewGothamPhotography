<?php

use App\Livewire\CommentForm;
use App\Models\Album;
use App\Models\Comment;
use App\Models\User;
use Livewire\Livewire;

it('renders comment form correctly', function () {
    $album = Album::factory()->published()->create();

    Livewire::test(CommentForm::class, ['commentable' => $album])
        ->assertSeeHtml('form')
        ->assertSeeHtml('wire:submit="submit"');
});

it('allows authenticated user to submit comment', function () {
    $user = User::factory()->create();
    $album = Album::factory()->published()->create();

    Livewire::actingAs($user)->test(CommentForm::class, ['commentable' => $album])
        ->set('content', 'Beautiful lighting and composition!')
        ->call('submit');

    expect(Comment::where('commentable_type', Album::class)->count())->toBe(1);
});

it('requires guest details when not authenticated', function () {
    $album = Album::factory()->published()->create();

    Livewire::test(CommentForm::class, ['commentable' => $album])
        ->set('content', 'Guest comment')
        ->call('submit')
        ->assertHasErrors(['guest_name', 'guest_email']);
});

it('rejects spam honeypot', function () {
    $album = Album::factory()->published()->create();

    Livewire::test(CommentForm::class, ['commentable' => $album])
        ->set('content', 'Spam')
        ->set('guest_name', 'Spammer')
        ->set('guest_email', 'spam@example.com')
        ->set('website', 'http://bot.com')
        ->call('submit')
        ->assertHasErrors('website');
});

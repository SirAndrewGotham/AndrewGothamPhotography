<?php

use App\Models\Comment;
use App\Models\Image;
use App\Models\User;

it('requires authentication or guest details', function () {
    $response = $this->post('/images/1/comments', [
        'content' => 'Great photo!',
    ]);

    $response->assertSessionHasErrors(['guest_name', 'guest_email']);
});

it('allows authenticated users to comment without guest fields', function () {
    $user = User::factory()->create();
    $image = Image::factory()->published()->create();

    $response = $this->actingAs($user)->post("/images/{$image->id}/comments", [
        'content' => 'Beautiful composition!',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('comments', [
        'user_id' => $user->id,
        'content' => 'Beautiful composition!',
        'is_approved' => false, // Requires moderation
    ]);
});

it('prevents deeply nested replies', function () {
    $parent = Comment::factory()->create();
    $child = Comment::factory()->create(['parent_id' => $parent->id]);

    $response = $this->post('/images/1/comments', [
        'content' => 'Reply to reply',
        'parent_id' => $child->id,
        'guest_name' => 'User',
        'guest_email' => 'user@example.com',
    ]);

    $response->assertSessionHasErrors('parent_id');
});

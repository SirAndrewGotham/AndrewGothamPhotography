<?php

use App\Http\Requests\StoreContactRequest;

it('validates required fields', function () {
    $response = $this->post('/contact', []);

    $response->assertSessionHasErrors(['name', 'email', 'subject', 'message', 'request_type']);
});

it('creates contact request with valid data', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '+7 (991) 123-45-67',
        'subject' => 'Booking Inquiry',
        'message' => 'I would like to book a photoshoot for our theater production.',
        'request_type' => 'booking',
        'preferred_language' => 'en',
    ];

    $response = $this->post('/contact', $data);

    $response->assertSessionHas('status');
    $this->assertDatabaseHas('contact_requests', [
        'email' => 'test@example.com',
        'request_type' => 'booking',
    ]);
});

it('sanitizes phone number', function () {
    $request = new StoreContactRequest;
    $request->merge(['phone' => '+7 (991) 873-91-37!']);
    $request->prepareForValidation();

    expect($request->phone)->toBe('+7 (991) 873-91-37');
});

it('defaults preferred_language to app locale', function () {
    app()->setLocale('ru');

    $request = new StoreContactRequest;
    $request->merge(['name' => 'Test', 'email' => 't@t.com', 'subject' => 'Test', 'message' => 'Test message', 'request_type' => 'general']);
    $request->prepareForValidation();

    expect($request->preferred_language)->toBe('ru');
});

it('rejects spam honeypot', function () {
    $data = [
        'name' => 'Spammer',
        'email' => 'spam@example.com',
        'subject' => 'Spam',
        'message' => 'Buy now!',
        'request_type' => 'general',
        'website' => 'http://spam.com', // Filled honeypot
    ];

    $response = $this->post('/contact', $data);

    $response->assertSessionHasErrors('website');
});

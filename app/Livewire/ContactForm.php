<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\ContactRequest;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public ?string $phone = '';

    public string $subject = '';

    public string $message = '';

    public string $request_type = 'general';

    public ?string $preferred_language = null;

    public ?string $website = ''; // Honeypot

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:2000',
        'request_type' => 'required|in:general,booking,collaboration,other',
    ];

    public function submit(): void
    {
        $this->validate();

        // Skip if honeypot is filled
        if (! in_array($this->website, [null, '', '0'], true)) {
            return;
        }

        ContactRequest::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
            'request_type' => $this->request_type,
            'preferred_language' => $this->preferred_language,
        ]);

        Session::flash('status', __('contact.success'));
        $this->reset(['name', 'email', 'phone', 'subject', 'message', 'website']);
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}

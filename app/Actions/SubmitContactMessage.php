<?php

namespace App\Actions;

use App\Models\ContactMessage;

class SubmitContactMessage
{
    /**
     * Store a message submitted through the contact form.
     *
     * @param  array<string, mixed>  $data  The validated form data.
     */
    public function handle(array $data): ContactMessage
    {
        return ContactMessage::query()->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
        ]);
    }
}

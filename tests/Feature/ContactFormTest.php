<?php

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a valid contact message is stored and answered with a Persian json message', function () {
    $response = $this->postJson(route('contact.store'), [
        'name' => 'زهرا کریمی',
        'phone' => '09121234567',
        'email' => 'zahra@example.com',
        'subject' => 'پرسش درباره ثبت‌نام',
        'message' => 'سلام، برای ثبت‌نام پایه دهم سوالی داشتم.',
    ]);

    $response->assertOk();
    $response->assertJsonPath('success', true);

    expect($response->json('message'))
        ->toBeString()
        ->toContain('پیام شما با موفقیت ثبت شد');

    $this->assertDatabaseHas('contact_messages', [
        'name' => 'زهرا کریمی',
        'phone' => '09121234567',
        'email' => 'zahra@example.com',
        'subject' => 'پرسش درباره ثبت‌نام',
        'is_read' => false,
    ]);
});

test('a contact message without a json request is answered with a redirect and a Persian flash message', function () {
    $response = $this->from(route('contact.index'))->post(route('contact.store'), [
        'name' => 'زهرا کریمی',
        'phone' => '09121234567',
        'message' => 'سلام، برای ثبت‌نام پایه دهم سوالی داشتم.',
    ]);

    $response->assertRedirect(route('contact.index'));
    $response->assertSessionHas('success');

    expect(session('success'))
        ->toBeString()
        ->toContain('پیام شما با موفقیت ثبت شد');

    expect(ContactMessage::query()->count())->toBe(1);
});

test('an invalid mobile number is rejected with a Persian message', function () {
    $response = $this->postJson(route('contact.store'), [
        'name' => 'زهرا کریمی',
        'phone' => '12345',
        'message' => 'سلام، برای ثبت‌نام پایه دهم سوالی داشتم.',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('phone');

    expect($response->json('errors.phone.0'))
        ->toBe('شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.');

    expect(ContactMessage::query()->count())->toBe(0);
});

test('a contact message without a body is rejected with a Persian message', function () {
    $response = $this->postJson(route('contact.store'), [
        'name' => 'زهرا کریمی',
        'phone' => '09121234567',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('message');

    expect($response->json('errors.message.0'))
        ->toBe('متن پیام را وارد کنید.');

    expect(ContactMessage::query()->count())->toBe(0);
});

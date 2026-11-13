<?php

use App\Enums\AdmissionStatus;
use App\Enums\GradeLevel;
use App\Models\AdmissionRequest;
use App\Models\Major;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->major = Major::factory()->create([
        'title' => 'شبکه و نرم‌افزار رایانه',
        'slug' => 'network-and-software',
    ]);
});

test('a valid admission request is stored with its major and grade level', function () {
    $response = $this->postJson(route('admission.store'), [
        'student_name' => 'علی رضایی',
        'parent_name' => 'حسین رضایی',
        'phone' => '09121234567',
        'email' => 'parent@example.com',
        'grade_level' => GradeLevel::Tenth->value,
        'major_id' => $this->major->id,
        'note' => 'تمایل به رشته شبکه و نرم‌افزار رایانه.',
    ]);

    $response->assertOk();
    $response->assertJsonPath('success', true);

    expect($response->json('message'))
        ->toBeString()
        ->toContain('درخواست ثبت‌نام شما با موفقیت ثبت شد');

    $this->assertDatabaseHas('admission_requests', [
        'student_name' => 'علی رضایی',
        'parent_name' => 'حسین رضایی',
        'phone' => '09121234567',
        'email' => 'parent@example.com',
        'grade_level' => GradeLevel::Tenth->value,
        'major_id' => $this->major->id,
        'status' => AdmissionStatus::New->value,
    ]);

    expect(AdmissionRequest::query()->count())->toBe(1);
});

test('an admission request without a json request is answered with a redirect and a Persian flash message', function () {
    $response = $this->from(route('admission.index'))->post(route('admission.store'), [
        'student_name' => 'علی رضایی',
        'parent_name' => 'حسین رضایی',
        'phone' => '09121234567',
        'grade_level' => GradeLevel::Eleventh->value,
        'major_id' => $this->major->id,
    ]);

    $response->assertRedirect(route('admission.index'));
    $response->assertSessionHas('success');

    expect(session('success'))
        ->toBeString()
        ->toContain('درخواست ثبت‌نام شما با موفقیت ثبت شد');
});

test('an invalid grade level is rejected with a Persian message', function () {
    $response = $this->postJson(route('admission.store'), [
        'student_name' => 'علی رضایی',
        'parent_name' => 'حسین رضایی',
        'phone' => '09121234567',
        'grade_level' => 'thirteenth',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('grade_level');

    expect($response->json('errors.grade_level.0'))
        ->toBe('پایه تحصیلی انتخاب‌شده معتبر نیست.');

    expect(AdmissionRequest::query()->count())->toBe(0);
});

test('an admission request without a parent name is rejected with a Persian message', function () {
    $response = $this->postJson(route('admission.store'), [
        'student_name' => 'علی رضایی',
        'phone' => '09121234567',
        'grade_level' => GradeLevel::Tenth->value,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('parent_name');

    expect($response->json('errors.parent_name.0'))
        ->toBe('نام و نام خانوادگی والدین را وارد کنید.');

    expect(AdmissionRequest::query()->count())->toBe(0);
});

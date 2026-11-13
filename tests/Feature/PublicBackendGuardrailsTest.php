<?php

use App\Enums\GradeLevel;
use App\Enums\PostCategory;
use App\Models\Achievement;
use App\Models\AdmissionRequest;
use App\Models\ContactMessage;
use App\Models\GalleryImage;
use App\Models\Major;
use App\Models\MajorCourse;
use App\Models\Post;
use Database\Seeders\MajorCourseSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\PostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| The guardrails of the public backend
|--------------------------------------------------------------------------
|
| The rules the public website has to keep: a post is only readable when it
| is really published, the two forms are rate limited and answer with a
| Persian message, a mobile number typed with Persian digits is accepted and
| stored with Latin digits, and the seeded content never announces a fact the
| school has not confirmed.
|
*/

uses(RefreshDatabase::class);

test('a post with a future publication date is not reachable', function () {
    $post = Post::factory()->create([
        'title' => 'خبر زمان‌بندی‌شده هنرستان',
        'slug' => 'scheduled-post-slug',
        'published_at' => now()->addWeek(),
        'is_published' => true,
    ]);

    $this->get(route('news.show', $post->slug))->assertNotFound();

    /* The listing hides it as well. */
    $this->get(route('news.index'))
        ->assertOk()
        ->assertDontSee('خبر زمان‌بندی‌شده هنرستان');
});

test('a published post without a publication date stays reachable', function () {
    $post = Post::factory()->create([
        'title' => 'خبر بدون زمان انتشار',
        'slug' => 'post-without-date-slug',
        'published_at' => null,
    ]);

    $this->get(route('news.show', $post->slug))
        ->assertOk()
        ->assertSee('خبر بدون زمان انتشار');
});

test('the news page knows every category of the published posts', function () {
    Post::factory()->create([
        'category' => PostCategory::News,
        'published_at' => now(),
    ]);

    Post::factory()->create([
        'category' => PostCategory::Event,
        'published_at' => now(),
    ]);

    /* An unpublished post does not add a category to the filter. */
    Post::factory()->unpublished()->create([
        'category' => PostCategory::Announcement,
        'published_at' => now(),
    ]);

    $categories = $this->get(route('news.index'))->assertOk()->viewData('categories');

    expect($categories)->toBeInstanceOf(Collection::class);
    expect($categories->every(fn (mixed $category): bool => $category instanceof PostCategory))->toBeTrue();

    /* The categories are sorted by their Persian label. */
    expect($categories->map(fn (PostCategory $category): string => $category->value)->all())
        ->toBe(['news', 'event']);
});

test('the contact form is rate limited and answers with a Persian json message', function () {
    $payload = [
        'name' => 'زهرا کریمی',
        'phone' => '09121234567',
        'message' => 'پیام آزمایشی برای بررسی محدودیت نرخ درخواست.',
    ];

    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->postJson(route('contact.store'), $payload)->assertOk();
    }

    $response = $this->postJson(route('contact.store'), $payload);

    $response->assertStatus(429);

    expect($response->json('message'))
        ->toBeString()
        ->toContain('بیش از حد مجاز');

    /* The throttled request never reaches the database. */
    expect(ContactMessage::query()->count())->toBe(5);
});

test('a throttled form submission without json answers with a Persian page', function () {
    $payload = [
        'name' => 'زهرا کریمی',
        'phone' => '09121234567',
        'message' => 'پیام آزمایشی برای بررسی محدودیت نرخ درخواست.',
    ];

    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->post(route('contact.store'), $payload)->assertRedirect();
    }

    $response = $this->post(route('contact.store'), $payload);

    $response->assertStatus(429);

    expect($response->getContent())
        ->toContain('تعداد درخواست‌های شما بیش از حد مجاز است');
});

test('a mobile number typed with Persian digits is accepted and stored with Latin digits', function () {
    $response = $this->postJson(route('contact.store'), [
        'name' => 'زهرا کریمی',
        'phone' => '۰۹۱۲۱۲۳۴۵۶۷',
        'message' => 'پیام آزمایشی برای بررسی رقم‌های فارسی در شمارهٔ موبایل.',
    ]);

    $response->assertOk();
    $response->assertJsonPath('success', true);

    $this->assertDatabaseHas('contact_messages', [
        'phone' => '09121234567',
    ]);
});

test('the admission form accepts a mobile number typed with Arabic-Indic digits', function () {
    $major = Major::factory()->create([
        'title' => 'شبکه و نرم‌افزار رایانه',
        'slug' => 'network-and-software',
    ]);

    $response = $this->postJson(route('admission.store'), [
        'student_name' => 'علی رضایی',
        'parent_name' => 'حسین رضایی',
        'phone' => '٠٩١٢١٢٣٤٥٦٧',
        'grade_level' => GradeLevel::Tenth->value,
        'major_id' => $major->id,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('admission_requests', [
        'phone' => '09121234567',
        'major_id' => $major->id,
    ]);
});

test('the admission form refuses a major that is not active', function () {
    $major = Major::factory()->inactive()->create([
        'title' => 'رشته غیرفعال',
        'slug' => 'inactive-major',
    ]);

    $response = $this->postJson(route('admission.store'), [
        'student_name' => 'علی رضایی',
        'parent_name' => 'حسین رضایی',
        'phone' => '09121234567',
        'grade_level' => GradeLevel::Tenth->value,
        'major_id' => $major->id,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('major_id');

    expect(AdmissionRequest::query()->count())->toBe(0);
});

test('the home page shows the capacity of every major and the competition of a result', function () {
    Major::factory()->create([
        'title' => 'شبکه و نرم‌افزار رایانه',
        'slug' => 'network-and-software',
        'seats' => 30,
    ]);

    Achievement::factory()->create([
        'title' => 'رتبه اول کشوری سال تحصیلی ۱۴۰۴',
        'year' => 1404,
        'competition' => 'مسابقات کشوری',
        'order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('ظرفیت اعلام‌شده');
    $response->assertSee('مسابقات کشوری');
});

test('the gallery groups the images without an album under the general album', function () {
    GalleryImage::factory()->create([
        'path' => '/images/gallery/placeholder-general.svg',
        'album' => null,
        'order' => 1,
    ]);

    GalleryImage::factory()->create([
        'path' => '/images/gallery/placeholder-empty.svg',
        'album' => '',
        'order' => 2,
    ]);

    GalleryImage::factory()->create([
        'path' => '/images/gallery/placeholder-teaching.svg',
        'album' => 'آموزشی',
        'order' => 3,
    ]);

    $albums = $this->get(route('gallery'))->assertOk()->viewData('albums');

    expect($albums->keys()->all())->toBe(['عمومی', 'آموزشی']);
    expect($albums->get('عمومی'))->toHaveCount(2);
});

test('the about page shows the active majors of the school', function () {
    Major::factory()->create([
        'title' => 'شبکه و نرم‌افزار رایانه',
        'slug' => 'network-and-software',
        'subtitle' => 'دانش بنیان در حوزه فناوری اطلاعات',
        'is_active' => true,
    ]);

    Major::factory()->create([
        'title' => 'مکاترونیک',
        'slug' => 'mechatronics',
        'is_active' => true,
    ]);

    Major::factory()->inactive()->create([
        'title' => 'رشته غیرفعال هنرستان',
        'slug' => 'inactive-major',
    ]);

    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee('شبکه و نرم‌افزار رایانه');
    $response->assertSee('دانش بنیان در حوزه فناوری اطلاعات');
    $response->assertSee('مکاترونیک');
    $response->assertDontSee('رشته غیرفعال هنرستان');

    expect($response->viewData('majors'))->toHaveCount(2);
});

test('the 500 view never prints the technical detail of the failure', function () {
    $view = view('errors.500', [
        'exception' => new RuntimeException('جزئیات فنی محرمانه STORAGE_SECRET_XYZ'),
    ])->render();

    expect($view)
        ->toContain('مشکلی در نمایش این صفحه پیش آمد')
        ->not->toContain('STORAGE_SECRET_XYZ');
});

test('the seeder stores the canonical icon of the mechatronics major', function () {
    $this->seed(MajorSeeder::class);

    $major = Major::query()->where('slug', 'mechatronics')->first();

    expect($major)->not->toBeNull();
    expect($major->icon)->toBe('mechatronics');
});

test('the course seeder corrects a course instead of adding a second one', function () {
    $this->seed([MajorSeeder::class, MajorCourseSeeder::class]);

    $courses = MajorCourse::query()->count();

    $this->seed(MajorCourseSeeder::class);

    expect(MajorCourse::query()->count())->toBe($courses);
});

test('the course seeder removes the courses that are not part of the list any more', function () {
    $this->seed([MajorSeeder::class, MajorCourseSeeder::class]);

    $major = Major::query()->where('slug', 'mechatronics')->first();

    MajorCourse::query()->create([
        'major_id' => $major->id,
        'grade' => GradeLevel::Tenth->value,
        'title' => 'درس بازنشسته',
        'order' => 99,
    ]);

    $this->seed(MajorCourseSeeder::class);

    expect(MajorCourse::query()->where('title', 'درس بازنشسته')->exists())->toBeFalse();
});

test('the seeded posts are marked as samples and announce no fact', function () {
    $this->seed(PostSeeder::class);

    $posts = Post::query()->get();

    expect($posts)->not->toBeEmpty();

    foreach ($posts as $post) {
        expect($post->title)->toStartWith('نمونه — ');
        expect($post->body)->toContain('نمونه');
        expect($post->is_published)->toBeTrue();
    }
});

<?php

use App\Enums\GradeLevel;
use App\Models\Major;
use App\Models\MajorCourse;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->networkMajor = Major::factory()->create([
        'title' => 'شبکه و نرم‌افزار رایانه',
        'slug' => 'network-and-software',
    ]);

    $this->mechatronicsMajor = Major::factory()->create([
        'title' => 'مکاترونیک',
        'slug' => 'mechatronics',
    ]);

    MajorCourse::factory()
        ->for($this->networkMajor)
        ->forGrade(GradeLevel::Tenth)
        ->create(['title' => 'مبانی شبکه و سخت‌افزار']);

    MajorCourse::factory()
        ->for($this->mechatronicsMajor)
        ->forGrade(GradeLevel::Eleventh)
        ->create(['title' => 'کنترل صنعتی و PLC']);
});

test('the home page shows the name of the school', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('هنرستان جوار شرکت پالایش نفت بندرعباس');
    $response->assertSee('شبکه و نرم‌افزار رایانه');
});

test('the majors page lists every active major', function () {
    $response = $this->get(route('majors.index'));

    $response->assertOk();
    $response->assertSee('شبکه و نرم‌افزار رایانه');
    $response->assertSee('مکاترونیک');
});

test('a major page is reachable through its slug and shows its courses', function () {
    $response = $this->get(route('majors.show', $this->networkMajor->slug));

    $response->assertOk();
    $response->assertSee('شبکه و نرم‌افزار رایانه');
    $response->assertSee('مبانی شبکه و سخت‌افزار');
});

test('an unknown major slug returns a 404 response', function () {
    $this->get('/majors/unknown-major')->assertNotFound();
});

test('the news index shows the published posts', function () {
    $post = Post::factory()->create([
        'title' => 'خبر آزمایشی هنرستان',
        'slug' => 'sample-news-slug',
    ]);

    $response = $this->get(route('news.index'));

    $response->assertOk();
    $response->assertSee('خبر آزمایشی هنرستان');
    $response->assertSee($post->title);
});

test('a published post is reachable through its slug', function () {
    $post = Post::factory()->create([
        'title' => 'خبر منتشرشده هنرستان',
        'slug' => 'published-news-slug',
    ]);

    $response = $this->get(route('news.show', $post->slug));

    $response->assertOk();
    $response->assertSee('خبر منتشرشده هنرستان');
});

test('an unpublished post returns a 404 response with a Persian message', function () {
    $post = Post::factory()->unpublished()->create([
        'title' => 'خبر منتشرنشده هنرستان',
        'slug' => 'unpublished-news-slug',
    ]);

    $response = $this->get(route('news.show', $post->slug));

    $response->assertNotFound();
    $response->assertSee('مطلب مورد نظر یافت نشد');
});

test('an unknown post slug returns a 404 response with a Persian message', function () {
    $response = $this->get('/news/unknown-post-slug');

    $response->assertNotFound();
    $response->assertSee('مطلب مورد نظر یافت نشد');
});

test('the ajax posts endpoint returns the list of posts', function () {
    foreach (range(1, 10) as $index) {
        Post::factory()->create([
            'title' => 'خبر فهرست شماره '.$index,
            'slug' => 'list-news-slug-'.$index,
            'published_at' => now()->subDays(20 - $index),
        ]);
    }

    $response = $this->get(route('news.more', ['page' => 2]));

    $response->assertOk();
    $response->assertSee('خبر فهرست شماره 1');
    $response->assertDontSee('خبر فهرست شماره 10');
});

test('the remaining public pages respond successfully', function () {
    $this->get(route('about'))->assertOk();
    $this->get(route('education'))->assertOk();
    $this->get(route('achievements'))->assertOk();
    $this->get(route('gallery'))->assertOk();
    $this->get(route('admission.index'))->assertOk();
    $this->get(route('contact.index'))->assertOk();
});

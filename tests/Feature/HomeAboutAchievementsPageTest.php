<?php

use App\Enums\AchievementScope;
use App\Models\Achievement;
use App\Models\Major;
use App\Models\StaffMember;
use App\Models\Statistic;
use App\Support\SchoolContent;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\StaffMemberSeeder;
use Database\Seeders\StatisticSeeder;

/*
|--------------------------------------------------------------------------
| Home, About and Achievements pages
|--------------------------------------------------------------------------
|
| These three pages are the storytelling core of the website: the home page
| introduces the school, the about page explains it and the achievements
| page keeps the record of its results. The tests below seed the real
| content and assert that every page answers with the school name and the
| seeded records.
|
*/

const SCHOOL_NAME = 'هنرستان جوار شرکت پالایش نفت بندرعباس';

beforeEach(function () {
    $this->seed([
        MajorSeeder::class,
        AchievementSeeder::class,
        StatisticSeeder::class,
        PostSeeder::class,
        StaffMemberSeeder::class,
    ]);
});

test('the home page shows the school name, its majors and its latest results', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee(SCHOOL_NAME);
    $response->assertSee('شبکه و نرم‌افزار رایانه');
    $response->assertSee('مکاترونیک');
    $response->assertSee('رتبه اول کشوری');
});

test('the home page lists every active major with a link to its own page', function () {
    $response = $this->get(route('home'));

    $response->assertOk();

    foreach (Major::query()->active()->get() as $major) {
        $response->assertSee($major->title);
        $response->assertSee(route('majors.show', $major->slug), false);
    }
});

test('the home page shows the school leadership with a callable phone number', function () {
    $response = $this->get(route('home'));

    $response->assertOk();

    foreach (StaffMember::query()->get() as $member) {
        $response->assertSee($member->name);
        $response->assertSee('tel:'.$member->phone, false);
    }
});

test('the home page shows the statistics of the school', function () {
    $response = $this->get(route('home'));

    $response->assertOk();

    foreach (Statistic::query()->get() as $statistic) {
        $response->assertSee($statistic->label);
        $response->assertSee($statistic->value);
    }
});

test('the home page links to the registration, the majors and the achievements pages', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee(route('admission.index'), false);
    $response->assertSee(route('majors.index'), false);
    $response->assertSee(route('achievements'), false);
    $response->assertSee(route('about'), false);
});

test('the about page introduces the school and repeats its official introduction', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee(SCHOOL_NAME);
    $response->assertSee(SchoolContent::intro());
    $response->assertSee('شبکه و نرم‌افزار رایانه');
    $response->assertSee('مکاترونیک');
});

test('the about page shows the management team and their contact numbers', function () {
    $response = $this->get(route('about'));

    $response->assertOk();

    foreach (StaffMember::query()->get() as $member) {
        $response->assertSee($member->name);
        $response->assertSee($member->role);
        $response->assertSee('tel:'.$member->phone, false);
    }
});

test('the about page shows the achievements grouped by school year', function () {
    $response = $this->get(route('about'));

    $response->assertOk();

    foreach (Achievement::query()->orderBy('id')->get() as $achievement) {
        $response->assertSee($achievement->title);
    }

    $response->assertSee('۱۴۰۱');
    $response->assertSee('۱۴۰۳');
});

test('the achievements page shows the national first rank and every recorded result', function () {
    $response = $this->get(route('achievements'));

    $response->assertOk();
    $response->assertSee(SCHOOL_NAME);
    $response->assertSee('رتبه اول کشوری');

    foreach (Achievement::query()->orderBy('id')->get() as $achievement) {
        $response->assertSee($achievement->title);
        $response->assertSee($achievement->rank_label);
    }
});

test('the achievements page labels every result with its scope and school year', function () {
    $response = $this->get(route('achievements'));

    $response->assertOk();
    $response->assertSee(AchievementScope::National->label());
    $response->assertSee('۱۴۰۱');
    $response->assertSee('۱۴۰۲');
    $response->assertSee('۱۴۰۳');
});

test('the achievements page explains that the record is cumulative', function () {
    $response = $this->get(route('achievements'));

    $response->assertOk();
    $response->assertSee('تجمعی');
});

test('the three storytelling pages show the three grade levels of the school', function () {
    foreach (['home', 'about'] as $route) {
        $response = $this->get(route($route));

        $response->assertOk();
        $response->assertSee('دهم');
        $response->assertSee('یازدهم');
        $response->assertSee('دوازدهم');
    }
});

test('the three storytelling pages stay free of the English placeholder copy', function () {
    foreach (['home', 'about', 'achievements'] as $route) {
        $response = $this->get(route($route));

        $response->assertOk();
        $response->assertDontSee('Lorem ipsum');
        $response->assertDontSee('TODO');
    }
});

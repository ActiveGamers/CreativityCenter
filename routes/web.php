<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/majors', [MajorController::class, 'index'])->name('majors.index');
Route::get('/majors/{major:slug}', [MajorController::class, 'show'])->name('majors.show');

Route::get('/education', [EducationController::class, 'index'])->name('education');

Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements');

Route::get('/news', [PostController::class, 'index'])->name('news.index');
Route::get('/news/more', [PostController::class, 'more'])->name('news.more');
Route::get('/news/{post:slug}', [PostController::class, 'show'])->name('news.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

Route::get('/admission', [AdmissionController::class, 'index'])->name('admission.index');
Route::post('/admission', [AdmissionController::class, 'store'])->name('admission.store');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

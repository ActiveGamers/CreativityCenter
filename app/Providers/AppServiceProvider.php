<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The Persian answer of a throttled request.
     */
    public const THROTTLE_MESSAGE = 'تعداد درخواست‌های ارسالی شما بیش از حد مجاز است. لطفاً چند دقیقه دیگر صبر کنید و دوباره تلاش کنید.';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRateLimiters();
        $this->registerThrottleRenderer();
    }

    /**
     * The rate limits of the two public forms.
     *
     * The forms are open to everybody, so a bot could flood the database with
     * fake requests. The attempts are counted per IP address and per minute.
     */
    protected function registerRateLimiters(): void
    {
        RateLimiter::for('contact', fn (Request $request): Limit => Limit::perMinute(5)->by($request->ip()));

        RateLimiter::for('admission', fn (Request $request): Limit => Limit::perMinute(8)->by($request->ip()));
    }

    /**
     * Answer a throttled request with a Persian message.
     *
     * The forms are submitted with AJAX, so the throttle has to answer with
     * JSON; the default answer of the framework is an English sentence.
     */
    protected function registerThrottleRenderer(): void
    {
        /** @var Handler $handler */
        $handler = $this->app->make(ExceptionHandlerContract::class);

        $handler->renderable(function (ThrottleRequestsException $exception, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => self::THROTTLE_MESSAGE,
                ], Response::HTTP_TOO_MANY_REQUESTS, $exception->getHeaders());
            }

            return response(
                view('errors.429', ['throttleMessage' => self::THROTTLE_MESSAGE]),
                Response::HTTP_TOO_MANY_REQUESTS,
                $exception->getHeaders(),
            );
        });
    }
}

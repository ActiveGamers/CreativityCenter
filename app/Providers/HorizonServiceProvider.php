<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     * The dashboard is open to everybody in the local environment; anywhere
     * else only the allowlisted e-mail addresses may open it. The site has
     * no sign-in flow, so the user is nullable and an empty allowlist keeps
     * the dashboard closed outside local development.
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user = null) {
            return in_array(optional($user)->email, $this->allowedEmails(), true);
        });
    }

    /**
     * The e-mail addresses allowed to view Horizon outside the local environment.
     *
     * @return array<int, string>
     */
    protected function allowedEmails(): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', (string) env('HORIZON_ALLOWED_EMAILS', '')))
        ));
    }
}

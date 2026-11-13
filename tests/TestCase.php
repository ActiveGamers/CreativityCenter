<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Force the testing environment before the application is bootstrapped.
     *
     * PHPUnit does not override environment variables that already exist in the
     * process, so a container (or shell) that exports APP_ENV=local would
     * otherwise boot the application outside the testing environment. That
     * disables the CSRF exemption and turns every POST into a 419.
     */
    protected function setUp(): void
    {
        putenv('APP_ENV=testing');
        $_ENV['APP_ENV'] = 'testing';
        $_SERVER['APP_ENV'] = 'testing';

        parent::setUp();
    }
}

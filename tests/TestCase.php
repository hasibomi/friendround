<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set request url.
     *
     * @param string $url
     * @return string
     */
    protected function url(string $url): string
    {
        return '/api/v1/' . $url;
    }
}

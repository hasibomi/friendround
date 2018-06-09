<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // Generated from postman.
    protected $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hcGkvdjEvbG9naW4iLCJpYXQiOjE1Mjg1NzY4NzIsImV4cCI6MTUyODU4MDQ3MiwibmJmIjoxNTI4NTc2ODcyLCJqdGkiOiJqOTJBb3hOZzlVcllSYm9LIn0.QsqmR5CSQ2UvJ8HPh1o5s3JCQrQezEYa62B4uZmYCvo';

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

    /**
     * Get results array from JSON response.
     *
     * @param array $response
     * @return array
     */
    protected function results(array $response): array
    {
        return $response['results'];
    }
}

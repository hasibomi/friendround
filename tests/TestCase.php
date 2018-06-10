<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JWTAuth;

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

    /**
     * Generate a token based on a user created for test.
     *
     * @return string
     */
    protected function token(): string
    {
        # Seed the database first.
        $user = factory(\FriendRound\Models\User::class)->create([
            'name' => 'John Doe',
            'username' => 'john',
            'email' => 'john@doe.com',
            'password' => '123456'
        ]);

        # Generate token.
        $token = JWTAuth::fromUser($user);

        return 'Bearer ' . $token;
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

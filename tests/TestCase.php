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
     * Make a user & create token based on the user.
     *
     * @param array $data
     * @return string
     */
    protected function auth(?array $data = []): string
    {
        # Seed the database first.
        if (! empty($data)) {
            $user = factory(\FriendRound\Models\User::class)->create($data);
        } else {
            $user = factory(\FriendRound\Models\User::class)->create([
                'name' => 'John Doe',
                'username' => 'john',
                'email' => 'john@doe.com',
                'password' => '123456'
            ]);
        }

        # Generate token.
        $token = JWTAuth::fromUser($user);

        return $token;
    }

    /**
     * Set token for authorization header.
     *
     * @param string $token
     * @return string
     */
    protected function token(?string $token = ''): string
    {
        if (! $token) $token = $this->auth();

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

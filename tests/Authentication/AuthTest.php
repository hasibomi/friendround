<?php

namespace Tests\Authentication;

use Tests\TestCase;
use Illuminate\Foundation\Testing\{ RefreshDatabase, TestResponse };
use phpDocumentor\Reflection\Types\Void_;

class AuthTest extends TestCase
{
    /**
     * Test user registration request validation.
     *
     * @return void
     */
    public function testRegistrationDataValidation(): void
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json'
        ])->json('POST', $this->url('register'), [
            'name' => 'John Doe',
            'username' => 'john',
            'email' => 'john',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user registration api.
     *
     * @return void
     */
    public function testRegistrationIsSuccess(): void
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json'
        ])->json('POST', $this->url('register'), [
            'name' => 'John Doe',
            'username' => 'john',
            'email' => 'john@doe.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $response->assertStatus(201)->assertJson(['status' => 'success']);
    }

    /**
     * Test user login request api input data validation.
     *
     * @return void
     */
    public function testLoginDataValidation(): void
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json'
        ])->json('POST', $this->url('login'), [
            'username' => 'john',
            'password' => ''
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user can login successfully.
     *
     * @return array
     */
    public function testLoginIsSuccess(): array
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json'
        ])->json('POST', $this->url('login'), [
            'username' => 'john',
            'password' => '123456'
        ]);

        $response->assertStatus(200)->assertJson(['status' => 'success']);

        return $response->decodeResponseJson();
    }

    /**
     * Test user cannot login with wrong credentials.
     *
     * @return void
     */
    public function testLoginIsFailed(): void
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json'
        ])->json('POST', $this->url('login'), [
            'username' => 'john',
            'password' => '1234567'
        ]);

        $response->assertStatus(400)->assertJson(['status' => 'error']);
    }

    /**
     * Test user can logout successfully.
     *
     * @depends testLoginIsSuccess
     * @param array $response
     * @return void
     */
    public function testLogoutIsSuccess(array $response): void
    {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $response['token']
        ])->json('POST', $this->url('logout'))->assertStatus(200)->assertJson(['status' => 'success']);
    }

    /**
     * Test user cannot logout if he/she does not provide token
     *
     * @return void
     */
    public function testLogoutIsFailed(): void
    {
        $this->json('POST', $this->url('logout'))->assertStatus(400)->assertJson(['status' => 'error']);
    }

    /**
     * Test if user provides faulty token.
     *
     * @return void
     */
    public function testTokenIsBad(): void
    {
        $this->withHeaders([
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c'
        ])->json('POST', $this->url('logout'))
            ->assertStatus(400)
            ->assertJson(['status' => 'error']);
    }
}
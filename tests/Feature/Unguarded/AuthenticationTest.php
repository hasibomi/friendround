<?php

namespace Tests\Feature\Unguarded;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user login request api input data validation.
     *
     * @return void
     */
    public function testLoginDataValidation() : void
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
     * @return void
     */
    public function testLoginIsSuccess() : void
    {
        # Seed database first.
        factory(\FriendRound\Models\User::class)->create([
            'name' => 'John Doe',
            'username' => 'john',
            'email' => 'john@doe.com',
            'password' => '123456'
        ]);

        # Actual test.
        $response = $this->withHeaders([
            'Content-Type' => 'application/json'
        ])->json('POST', $this->url('login'), [
            'username' => 'john',
            'password' => '123456'
        ]);

        $response->assertStatus(200)->assertJson(['status' => 'success']);

        # Assert the user actually exists in the database.
        $this->assertDatabaseHas('users', [
            'username' => 'john'
        ]);
    }

    /**
     * Test user cannot login with wrong credentials.
     *
     * @return void
     */
    public function testLoginIsFailed() : void
    {
        # Seed database first.
        factory(\FriendRound\Models\User::class)->create([
            'name' => 'John Doe',
            'username' => 'john',
            'email' => 'john@doe.com',
            'password' => '123456'
        ]);

        # Actual test.
        $response = $this->withHeaders([
            'Content-Type' => 'application/json'
        ])->json('POST', $this->url('login'), [
            'username' => 'john',
            'password' => '12345675656fsfsd'
        ]);

        $response->assertStatus(400)->assertJson(['status' => 'error']);
    }

    /**
     * Test user can logout successfully.
     *
     * @return void
     */
    public function testLogoutIsSuccess() : void
    {
        $this->withHeaders([
            'Authorization' => $this->token()
        ])->json('POST', $this->url('logout'))->assertStatus(200)->assertJson(['status' => 'success']);
    }

    /**
     * Test user cannot logout if he/she does not provide token
     *
     * @return void
     */
    public function testLogoutIsFailed() : void
    {
        $this->json('POST', $this->url('logout'))->assertStatus(400)->assertJson(['status' => 'error']);
    }

    /**
     * Test if user provides faulty token.
     *
     * @return void
     */
    public function testTokenIsBad() : void
    {
        $this->withHeaders([
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c'
        ])->json('POST', $this->url('logout'))
            ->assertStatus(400)
            ->assertJson(['status' => 'error']);
    }
}

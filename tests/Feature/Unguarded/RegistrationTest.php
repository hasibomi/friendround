<?php

namespace Tests\Feature\Unguarded;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use FriendRound\Models\User;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration request validation.
     *
     * @return void
     */
    public function testRegistrationDataValidation() : void
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
        ])->json('POST', $this->url('register'), [
            'name' => 'John Doe',
            'username' => 'john',
            'email' => 'john@bang',
            'password' => '123456',
            'password_confirmation' => 'jghjghj'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test user registration api.
     *
     * @return void
     */
    public function testRegistrationIsSuccess() : void
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

        # Assert the data actually saved in the database.
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'username' => 'john',
            'email' => 'john@doe.com'
        ]);
    }
}

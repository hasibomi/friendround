<?php

namespace Tests\Feature\Unguarded;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    /**
     * Test user registration request validation.
     *
     * @return void
     */
    public function testRegistrationDataValidation() : void
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
    }
}

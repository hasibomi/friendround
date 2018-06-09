<?php

namespace Tests\Feature\Guarded\Search;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileViewTest extends TestCase
{
    /**
     * Test profile view of a user  & does exist the user in the database.
     *
     * @return void
     */
    public function testProfileViewIsSuccess(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('GET', $this->url('users/john'));

        $response->assertStatus(200)->assertJson(['status' => 'success']);

        $data = $response->decodeResponseJson();

        $this->assertDatabaseHas('users', ['username' => $this->results($data)['username']]);
    }

    /**
     * Test profile does not exist for wrong username & does not exist in the database.
     *
     * @return void
     */
    public function testProfileDoesnotExist(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->json('GET', $this->url('users/jane'));

        $response->assertStatus(404)->assertExactJson(['status' => 'error', 'message' => 'The requested user could not be found']);

        $data = $response->decodeResponseJson();

        $this->assertDatabaseMissing('users', [
            'username' => 'jane'
        ]);
    }
}

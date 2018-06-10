<?php

namespace Tests\Feature\Guarded\Search;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test profile view of a user  & does exist the user in the database.
     *
     * @return void
     */
    public function testProfileViewIsSuccess(): void
    {
        $response = $this->withHeaders([
            'Authorization' => $this->token()
        ])->json('GET', $this->url('users/john'));

        $response->assertStatus(200)->assertJson(['status' => 'success']);

        # Get the response back from the api call.
        $data = $response->decodeResponseJson();

        # Assert the returned user actually exists in the database.
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
            'Authorization' => $this->token()
        ])->json('GET', $this->url('users/bang'));

        $response->assertStatus(404)->assertExactJson(['status' => 'error', 'message' => 'The requested user could not be found']);

        # Get the response back from the api call.
        $data = $response->decodeResponseJson();

        # Assert the user with provided username actually does not exist in the database.
        $this->assertDatabaseMissing('users', [
            'username' => 'bang'
        ]);
    }
}

<?php

namespace Tests\Feature\Guarded\Friends;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;

class FriendRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed database with some users.
     *
     * @param array $data
     * @return void
     */
    private function seedData(?array $data = []): void
    {
        if (empty($data)) {
            $users = [
                [
                    'name' => 'Test User',
                    'username' => 'tuser',
                    'email' => 'user@test.com',
                    'password' => '123456'
                ],
                [
                    'name' => 'Spider Man',
                    'username' => 'spidy',
                    'email' => 'spiderman@web.com',
                    'password' => '123456'
                ]
            ];
        } else {
            $users = [$data];
        }
        
        \FriendRound\Models\User::insert($users);
    }

    /**
     * Test sending friend request to a user is success.
     *
     * @return void
     */
    public function testSendFriendRequestIsSuccess(): void
    {
        $this->seedData();
        $token = $this->token();
        $response = $this->withHeaders(['Authorization' => $token])->json('POST', $this->url('friendrequests/send/spidy'));

        $response->assertStatus(201)->assertExactJson(['status' => 'success', 'message' => 'Friend request sent']);

        # Assert friend request data actually get inserted in the database.
        $this->assertDatabaseHas('friends', [
            'sender_id' => 1003,
            'receiver_id' => 1002
        ]);
    }

    /**
     * Test sending friend request is failed if wrong username is provided.
     *
     * @return void
     */
    public function testFriendRequestIsFailed(): void
    {
        $this->seedData();
        $token = $this->token();
        $response = $this->withHeaders(['Authorization' => $token])->json('POST', $this->url('friendrequests/send/tuhin'));

        $response->assertStatus(404)->assertExactJson(['status' => 'error', 'message' => 'The requested user could not be found']);

        # Assert friend request data not actually exist in the database.
        $this->assertDatabaseMissing('friends', [
            'sender_id' => 1003,
            'receiver_id' => 1002
        ]);
    }
}

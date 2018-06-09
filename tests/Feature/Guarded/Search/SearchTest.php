<?php

namespace Tests\Feature\Guarded\Search;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    /**
     * Test search user input data validation.
     *
     * @return void
     */
    public function testSearchUserDataValidation() : void
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('GET', $this->url('search'))->assertStatus(422);
    }

    /**
     * Test search user is success.
     *
     * @return void
     */
    public function testSearchUserIsSuccess(): void
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('GET', $this->url('search'), [
            'term' => 'john@doe.com'
        ])->assertStatus(200)->assertJson(['status' => 'success']);
    }

    /**
     * Test search user is failed.
     *
     * @return void
     */
    public function testSearchUserIsEmpty(): void
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('GET', $this->url('search'), [
            'term' => 'jane'
        ])->assertStatus(200)->assertExactJson(['status' => 'success', 'results' => []]);
    }
}

<?php

namespace Tests\Feature;

use App\Theater;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TheaterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     *
     * @return void
     */
    public function get_first_page_theaters_with_default_limit_of_15() {
        factory(Theater::class, 20)->create();

        $response = $this->json('get',route('theaters.index'));

        $response->assertStatus(200);
        $response->assertJson([
            'total' => 20,
            'per_page' => 15
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_third_page_theaters_with_custom_limit() {
        factory(Theater::class, 100)->create();

        $response = $this->json('get', route('theaters.index', [
            'limit' => 20,
            'page' => 3
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'total' => 100,
            'per_page' => 20,
            'current_page' => 3,
            'last_page' => 5,
            'from' => 41,
            'to' => 60
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_empty_page_theaters() {
        factory(Theater::class, 10)->create();

        $response = $this->json('get', route('theaters.index', [
            'limit' => 9,
            'page' => 2
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'total' => 10,
            'per_page' => 9,
            'current_page' => 2,
            'data' => []
        ]);
    }
}

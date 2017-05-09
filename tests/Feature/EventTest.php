<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     *
     * @return void
     */
    public function get_first_page_events_with_default_limit_of_15() {
        factory(Event::class, 20)->create();

        $response = $this->json('get',route('events.index'));

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
    public function get_third_page_events_with_custom_limit() {
        factory(Event::class, 100)->create();

        $response = $this->json('get', route('events.index', [
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
    public function get_empty_page_events() {
        factory(Event::class, 10)->create();

        $response = $this->json('get', route('events.index', [
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

    // /**
    //  * @test
    //  *
    //  * @return void
    //  */
    // public function create_an_event() {

    //     $response = $this->json('post', '/events', [
    //         'name' => 'El Rey Leon',
    //         'description' => 'Mock Turtle in the distance, screaming with passion. She had already heard her voice sounded hoarse and strange, and the March Hare',
    //         'dramaturgic' => 'Aida Shanahan',
    //         'director' => 'Adella Denesik',
    //         'cast' => 'Alfonso Jast,Jacky Denesik',
    //         'email' => 'cormier.linnie@wehner.com',
    //         'facebook' => 'http://pagac.biz/voluptatem-doloremque-sit-eum-culpa-atque-sint',
    //         'instagram' => 'http://www.johnston.com/rerum-saepe-dolorum-ipsa-doloribus-exercitationem-tenetur-repellat.html',
    //         'twitter' => 'http://torp.com/',
    //         'webpage' => 'https://www.considine.biz/quo-veniam-sint-fuga-sunt-necessitatibus-voluptas',
    //         'profile_picture' => 'http://lorempixel.com/640/480/?95764',
    //         'category_id' => 1,
    //         'user_id' => 1
    //     ]);

    //     $response->assertStatus(200);
        
    // }

    /**
     * @test
     *
     * @return void
     */
    public function fetch_an_event() {

        $event = factory(\App\Event::class)->create();

        $response = $this->json('get',route('events.show', $event->id));

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $event->id,
            'name' => $event->name,
        ]);
        
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: La_ma
 * Date: 17/05/2017
 * Time: 11:47 PM
 */

namespace Tests\Feature;

use App\Review;
use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewTest extends TestCase {

    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * @test
     *
     * @return void
     */
    public function get_first_page_reviews_with_default_limit_of_15() {

        factory(Review::class, 20)->create();

        $response = $this->json('get',route('reviews.index'));

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
    public function get_third_page_reviews_with_custom_limit() {
        factory(Review::class, 20)->create();

        $response = $this->json('get', route('reviews.index', [
            'limit' => 5,
            'page' => 3
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'total' => 20,
            'per_page' => 5,
            'current_page' => 3,
            'last_page' => 4,
            'from' => 11,
            'to' => 15
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_empty_page_reviews() {
        factory(Review::class, 10)->create();

        $response = $this->json('get', route('reviews.index', [
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


    /**
     * @test
     *
     * return @void
     */
    public function get_single_reviews_info() {
        $theater = factory(Review::class)->create([
            'name' => 'Joshua Flores'
        ]);

        $response = $this->json('get', route('reviews.show',[
            'id' => $theater->id
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Joshua Flores'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function get_404_to_undefined_reviews_id() {
        $event = factory(Event::class)->create();
        $review = factory(Review::class)->create([
            'name' => 'Joshua Flores',
            "description"=> "White Rabbit, jumping up and went on: 'But why did they draw?' said Alice, a little ledge of rock, and, as the soldiers did. After these came the guests, mostly Kings and Queens, and among them.",
            "score"=> 3,
            "event_id"=> $event->id
        ]);

        $response = $this->json('get', route('reviews.show', [
            'id' => $review->id+1
        ]));

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error','error_message'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function create_a_review() {
        $event = factory(Event::class)->create();
        $data = [
            "name" => "Armando Manzanero",
            "description"=> "White Rabbit, jumping up and x  on: 'But why did they draw?' said Alice, a little ledge of rock, and, as the soldiers did. After these came the guests, mostly Kings and Queens, and among them.",
            "score"=> 3,
            "event_id"=> $event->id
        ];

        $response = $this->json('post', route('reviews.store'), $data);

        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'Armando Manzanero'
        ]);
    }

         /**
     * @test
     *
     * return @void
     */
    public function update_a_review() {

        $event = factory(Event::class)->create();
        $review = factory(Review::class)->create([
            'name' => 'Joshua',
            'event_id' => $event->id
        ]);

        $data = [
            'name' => 'Majo',
        ];

        $response = $this->json('put', route('reviews.update', [
            'id' => $review->id
        ]), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Majo'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function destroy_an_event() {

        $event = factory(Event::class)->create();
        $review = factory(Review::class)->create([
            'name' => 'Joshua',
            'event_id' => $event->id
        ]);

        $response = $this->json('delete', route('reviews.destroy', [
            'id' => $review->id
        ]));

        $response->assertStatus(204);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_404_to_undefined_event_id_on_destroy() {

        $event = factory(Event::class)->create();
        $review = factory(Review::class)->create([
            'name' => 'Joshua',
            'event_id' => $event->id
        ]);

        $response = $this->json('delete', route('reviews.destroy', [
            'id' => $review->id+1
        ]));

        $response->assertStatus(404);

        $response->assertJsonStructure([
            'error','error_message'
        ]);
    }
}

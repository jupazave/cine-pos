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
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReviewTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     *
     * @return void
     */
    public function get_first_page_reviews_with_default_limit_of_15()
    {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);
        factory(Review::class, 20)->create([
            'event_id' => $event->id
        ]);

        $response = $this->json('get', 'api/v1/events/' . $event->id . '/reviews/', [], $headers);

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
    public function get_third_page_reviews_with_custom_limit()
    {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);
        factory(Review::class, 20)->create([
            'event_id' => $event->id
        ]);

        $response = $this->json('get', 'api/v1/events/' . $event->id . '/reviews/', [
            'limit' => 5,
            'page' => 3
        ], $headers);

        $response->assertStatus(200);
        $response->assertJson([
            "total" => 20,
            "per_page" => 15,
            "current_page" => 3,
            "last_page" => 2,
            "next_page_url" => null,
            "prev_page_url" => "http://localhost/api/v1/events/1/reviews?page=2",
            "from" => null,
            "to" => null,
            "data" => []
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_empty_page_reviews()
    {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);
//        factory(Review::class, 10)->create();

        $response = $this->json('get', 'api/v1/events/' . $event->id . '/reviews/', [
            'limit' => 9,
            'page' => 2
        ], $headers);

        $response->assertStatus(200);
        $response->assertJson([
            "total" => 0,
            "per_page" => 15,
            "current_page" => 2,
            "last_page" => 0,
            "next_page_url" => null,
            "prev_page_url" => "http://localhost/api/v1/events/1/reviews?page=1",
            "from" => null,
            "to" => null,
            "data" => []

        ]);
    }


    /**
     * @test
     *
     * return @void
     */
    public function get_single_reviews_info()
    {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);
        $review = factory(Review::class)->create([
            'event_id' => $event->id
        ]);

        $response = $this->json('get', 'api/v1/events/' . $event->id . '/reviews/'.$review->id,[], $headers);

        $response->assertStatus(200);
        $response->assertJson([
            'event_id' => $event->id
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function get_404_to_undefined_reviews_id()
    {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);
        $review = factory(Review::class)->create([
            'name' => 'Joshua Flores',
            "description" => "White Rabbit, jumping up and went on: 'But why did they draw?' said Alice, a little ledge of rock, and, as the soldiers did. After these came the guests, mostly Kings and Queens, and among them.",
            "score" => 3,
            "event_id" => $event->id
        ]);

        $response = $this->json('get', 'api/v1/events/' . $event->id . '/reviews/2',[], $headers);

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error', 'error_message'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function create_a_review()
    {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);
        $data = [
            "name" => "Armando Manzanero",
            "description" => "White Rabbit, jumping up and x  on: 'But why did they draw?' said Alice, a little ledge of rock, and, as the soldiers did. After these came the guests, mostly Kings and Queens, and among them.",
            "score" => 3,
            "event_id" => $event->id
        ];

        $response = $this->json('post', 'api/v1/events/' . $event->id . '/reviews/', $data, $headers);

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
    public function destroy_a_review()
    {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);
        $review = factory(Review::class)->create([
            'name' => 'Joshua',
            'event_id' => $event->id
        ]);

        $response = $this->json('delete', 'api/v1/events/' . $event->id . '/reviews/' . $review->id, [], $headers);

        $response->assertStatus(204);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_404_to_undefined_review_id_on_destroy()
    {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);
        $review = factory(Review::class)->create([
            'name' => 'Joshua',
            'score' => 1,
            'event_id' => $event->id
        ]);

        $response = $this->json('delete', 'api/v1/events/' . $event->id . '/reviews/'. ($review->id+1), [], $headers);
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'error', 'error_message'
        ]);
    }

    private function createUser() {
        $user = factory(User::class)->create([
            'username' => 'usr',
            "password" => bcrypt("secret")
        ]);

        return $user;
    }

    private function getHeaderToken() {
        $data = [
            "username" => "usr",
            "password" => "secret",
        ];

        $response = $this->json('post', route('auth.login'), $data);
        $response->assertStatus(200);
        return $headers = ['HTTP_Authorization' => 'Bearer ' . $response->json()["token"]];
    }
}

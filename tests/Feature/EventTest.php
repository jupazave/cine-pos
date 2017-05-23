<?php

namespace Tests\Feature;

use App\Category;
use App\Event;
use App\User;
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
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        factory(Event::class, 20)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('get',route('events.index'), [], $headers);

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
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        factory(Event::class, 20)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('get', route('events.index', [
            'limit' => 5,
            'page' => 3
        ]), [], $headers);

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
    public function get_empty_page_events() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        factory(Event::class, 10)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('get', route('events.index', [
            'limit' => 9,
            'page' => 2
        ]), [], $headers);

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
     * @return void
     */
    public function fetch_an_event() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(\App\Event::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('get',route('events.show', $event->id), [], $headers);

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $event->id,
            'name' => $event->name,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_404_to_undefined_event_id() {

        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(\App\Event::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('get',route('events.show', $event->id+1), [], $headers);

        $response->assertStatus(404);

        $response->assertJsonStructure([
            'error','error_message'
        ]);
    }

     /**
      * @test
      *
      * @return void
      */
     public function create_an_event() {
         $user = $this->createUser();
         $headers = $this->getHeaderToken();
         $category = factory(Category::class)->create();
         $data = [
             'name' => 'El Rey Leon',
             'description' => 'Mock Turtle in the distance, screaming with passion. She had already heard her voice sounded hoarse and strange, and the March Hare',
             'dramaturgic' => 'Aida Shanahan',
             'director' => 'Adella Denesik',
             'cast' => 'Alfonso Jast,Jacky Denesik',
             'email' => 'cormier.linnie@wehner.com',
             'facebook' => 'http://pagac.biz/voluptatem-doloremque-sit-eum-culpa-atque-sint',
             'instagram' => 'http://www.johnston.com/rerum-saepe-dolorum-ipsa-doloribus-exercitationem-tenetur-repellat.html',
             'twitter' => 'http://torp.com/',
             'webpage' => 'https://www.considine.biz/quo-veniam-sint-fuga-sunt-necessitatibus-voluptas',
             'profile_picture' => 'http://lorempixel.com/640/480/?95764',
             'category_id' => $category->id,
             'user_id' => $user->id
         ];

         $response = $this->json('post', route('events.store'), $data, $headers);

         $response->assertStatus(201);
         $response->assertJson([
            'name' => 'El Rey Leon'
        ]);
     }

     /**
     * @test
     *
     * return @void
     */
    public function update_an_event() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id,
            'name' => 'El Rey Leon'
        ]);

        $data = [
            'name' => 'El Rey Leon 2',
        ];

        $response = $this->json('put', route('events.update', [
            'id' => $event->id
        ]), $data, $headers);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'El Rey Leon 2'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function destroy_an_event() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('delete', route('events.destroy', [
            'id' => $event->id
        ]), [], $headers);

        $response->assertStatus(204);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_404_to_undefined_event_id_on_destroy() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $event = factory(Event::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('delete', route('events.destroy', [
            'id' => $event->id+1
        ]), [], $headers);

        $response->assertStatus(404);

        $response->assertJsonStructure([
            'error','error_message'
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

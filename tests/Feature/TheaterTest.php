<?php

namespace Tests\Feature;

use App\Theater;
use App\User;
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
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        factory(Theater::class, 20)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('get',route('theaters.index'), [], $headers);

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
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        factory(Theater::class, 20)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('get', route('theaters.index', [
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
    public function get_empty_page_theaters() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        factory(Theater::class, 10)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('get', route('theaters.index', [
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
     * return @void
     */
    public function get_single_theater_info() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $theater = factory(Theater::class)->create([
            'user_id' => $user->id,
            'name' => 'Raul Migdonio'
        ]);

        $response = $this->json('get', route('theaters.show',[
            'id' => $theater->id
        ]), [], $headers);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Raul Migdonio'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function get_404_to_undefined_theater_id() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $theater = factory(Theater::class)->create([
            'user_id' => $user->id,
            'name' => 'Raul Migdonio'
        ]);

        $response = $this->json('get', route('theaters.show', [
           'id' => $theater->id+1
        ]), [], $headers);

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
    public function create_a_theater() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $data = [
            "name" => "Armando Manzanero",
            "description"=> "White Rabbit, jumping up and went on: 'But why did they draw?' said Alice, a little ledge of rock, and, as the soldiers did. After these came the guests, mostly Kings and Queens, and among them.",
            "address"=> "418 Conn Stravenue\nLeifview, RI 46353",
            "zip_code"=> "97200",
            "city"=> "Merida",
            "country"=> "Yucatan",
            "phone"=> "1-272-361-9037",
            "email"=> "tererersa21@gmail.com",
            "instagram"=> "http://conroy.com/corporis-aut-ut-harum-reprehenderit-consectetur-voluptas-ut",
            "twitter"=> "https://www.littel.org/qui-incidunt-sapiente-fuga-assumenda",
            "webpage"=> "http://www.heathcote.com/eius-assumenda-sed-autem-et-perspiciatis-dolorum.html",
            "profile_picture"=> "http://lorempixel.com/640/480/?94824",
            "user_id"=> $user->id
        ];

        $response = $this->json('post', route('theaters.store'), $data, $headers);

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
    public function update_a_theater() {
        $user = $this->createUser();
        $headers = $this->getHeaderToken();
        $theater = factory(Theater::class)->create([
            'user_id' => $user->id,
            'name' => 'Raul Migdonio'
        ]);


        $data = [
            'name' => 'Not Raul Migdonio',
        ];

        $response = $this->json('put', route('theaters.update', [
            'id' => $theater->id
        ]), $data, $headers);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Not Raul Migdonio',
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

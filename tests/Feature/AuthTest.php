<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     *
     * return @void
     */
    public function signup() {
        $data = [
            "username" => "usr.post",
            "email" => "usr.post@gmail.com",
            "password" => "secret",
            "first_name" => "Juan Pablo",
            "last_name" => "Zamora Veraza",
            "zipcode" => "97305",
            "city" => "Merida",
            "country" => "Yuc"
        ];

    
        $response = $this->json('post', route('auth.signup'), $data);
        $response->assertStatus(200);

        $headers = [ 'HTTP_Authorization' => 'Bearer ' . $response->json()["token"] ];

        $response = $this->json('get', route('auth.me'), array(), $headers);
        $response->assertStatus(200);

        $response->assertJson([
            'username' => 'usr.post',
            "email" => "usr.post@gmail.com"
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function login() {

        $user = factory(User::class)->create([
            'username' => 'usr.post',
            "password" => bcrypt("secret")
        ]);

        $data = [
            "username" => "usr.post",
            "password" => "secret",
        ];
    
        $response = $this->json('post', route('auth.login'), $data);
        $response->assertStatus(200);

        $headers = [ 'HTTP_Authorization' => 'Bearer ' . $response->json()["token"] ];

        $response = $this->json('get', route('auth.me'), array(), $headers);
        $response->assertStatus(200);

        $response->assertJson([
            'username' => 'usr.post'
        ]);
    }
}

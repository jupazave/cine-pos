<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
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

        $headers = [ 'HTTP_Authorization' => 'Bearer ' . $response->token ];

        $response = $this->json('get', route('me'), array(), $headers);
        $response->assertStatus(200);

        $response->assertJson([
            'username' => 'usrpost',
            "email" => "usrpost@gmail.com"
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
            "password" => "secret"
        ]);

        $data = [
            "username" => "usr.post",
            "password" => "secret",
        ];
    
        $response = $this->json('post', route('auth.login'), $data);
        $response->assertStatus(200);

        $headers = [ 'HTTP_Authorization' => 'Bearer ' . $response->token ];

        $response = $this->json('get', route('me'), array(), $headers);
        $response->assertStatus(200);

        $response->assertJson([
            'username' => 'usrpost'
        ]);
    }
}

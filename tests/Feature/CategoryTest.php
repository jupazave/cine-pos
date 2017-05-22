<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * @test
     *
     * @return void
     */
    public function get_first_page_categories_with_default_limit_of_15() {

        factory(Category::class, 20)->create();

        $response = $this->json('get',route('categories.index'));

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
    public function get_third_page_categories_with_custom_limit() {
        factory(Category::class, 20)->create();

        $response = $this->json('get', route('categories.index', [
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
    public function get_empty_page_categories() {
        factory(Category::class, 10)->create();

        $response = $this->json('get', route('categories.index', [
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
    public function get_single_category_info() {
        $category = factory(Category::class)->create([
            'name' => 'Action',
            'description' => 'Action film is a film genre in which the protagonist or protagonists end up in a series of challenges...'
        ]);

        $response = $this->json('get', route('categories.show',[
            'id' => $category->id
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Action',
            'description' => 'Action film is a film genre in which the protagonist or protagonists end up in a series of challenges...'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function get_404_to_undefined_category_id() {
        $category = factory(Category::class)->create([
            'name' => 'Action',
            'description' => 'Action film is a film genre in which the protagonist or protagonists end up in a series of challenges...'
        ]);

        $response = $this->json('get', route('categories.show', [
           'id' => $category->id+1
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
    public function create_a_category() {
        $data = [
            'name' => 'Action',
            'description' => 'Action film is a film genre in which the protagonist or protagonists end up in a series of challenges...'
        ];

        $response = $this->json('post', route('categories.store'), $data);

        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'Action',
            'description' => 'Action film is a film genre in which the protagonist or protagonists end up in a series of challenges...'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function update_a_category() {
        $category = factory(Category::class)->create([
            'name' => 'Action',
            'description' => 'Action film is a film genre in which the protagonist or protagonists end up in a series of challenges...'
        ]);


        $data = [
            'name' => 'Not Action',
        ];

        $response = $this->json('put', route('categories.update', [
            'id' => $category->id
        ]), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Not Action'
        ]);
    }
}

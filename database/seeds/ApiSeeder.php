<?php

use App\Category;
use App\Event;
use App\Review;
use App\Schedule;
use App\Theater;
use App\User;
use Illuminate\Database\Seeder;

class ApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(User::class, 10)->create();
        factory(Category::class, 15)->create();

        factory(Theater::class, 20)
            ->create([
                'user_id' => function() {
                    return User::all()->random(1)->first()->id;
                }
            ]);

        factory(Event::class, 40)
            ->create([
                'user_id' => function() {
                    return User::all()->random(1)->first()->id;
                },
                'category_id' => function() {
                    return Category::all()->random(1)->first()->id;
                }
            ]);

        factory(Review::class, 200)
            ->create([
                'event_id' => function() {
                    return Event::all()->random(1)->first()->id;
                }
            ]);

        factory(Schedule::class, 100)
            ->create([
                'event_id' => function() {
                    return Event::all()->random(1)->first()->id;
                },
                'theater_id' => function() {
                    return Theater::all()->random(1)->first()->id;
                }
            ]);
    }
}

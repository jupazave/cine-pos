<?php

namespace Tests\Feature;

use App\Category;
use App\Event;
use App\User;
use App\Schedule;
use App\Theater;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class ScheduleTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * @test
     *
     * @return void
     */
    public function get_first_page_schedules_with_default_limit_of_15() {
        $now = Carbon::now()->addHour(1)->toDateTimeString();
        $endNow = Carbon::now()->addHour(10)->toDateTimeString();
        factory(Schedule::class)->create([
            'start_date' => $now,
            'end_date' => $endNow
        ]);

        $response = $this->json('get',route('schedules.index'));

        $response->assertStatus(200);
        $response->assertJson([
            'total' => 1,
            'per_page' => 15
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function fetch_a_schedule() {

        $schedule = factory(\App\Schedule::class)->create();

        $response = $this->json('get',route('schedules.show', $schedule->id));

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $schedule->id,
            'stage' => $schedule->stage,
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_404_to_undefined_schedule_id() {

        $schedule = factory(\App\Schedule::class)->create();

        $response = $this->json('get',route('schedules.show', $schedule->id+1));

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
     public function create_a_schedule() {
         $event = factory(Event::class)->create();
         $theater = factory(Theater::class)->create();
         $data = [
            "stage" => "Salón Principal",
            "start_date" => "2018-11-24 09:59:56",
            "end_date" => "2018-11-24 11:59:56",
            'event_id' => $event->id,
            'theater_id' => $theater->id
         ];

         $response = $this->json('post', route('schedules.store'), $data);

         $response->assertStatus(201);
         $response->assertJson([
            'stage' => 'Salón Principal'
        ]);
     }

     /**
      * @test
      *
      * @return void
      */
     public function create_a_schedule_with_end_date_before_start_date() {
         $event = factory(Event::class)->create();
         $theater = factory(Theater::class)->create();
         $data = [
            "stage" => "Salón Principal",
            "start_date" => "2018-11-24 09:59:56",
            "end_date" => "2018-11-24 08:59:56",
            'event_id' => $event->id,
            'theater_id' => $theater->id
         ];

         $response = $this->json('post', route('schedules.store'), $data);

         $response->assertStatus(422);
         $response->assertJson([
            "The end date must be a date after start date."
        ]);
     }

     /**
      * @test
      *
      * @return void
      */
     public function create_a_schedule_with_collision() {

         $storedSchedule = factory(Schedule::class)->create([
            "start_date" => "2018-11-24 09:59:56",
            "end_date" => "2018-11-24 11:59:56"
         ]);
         $event = factory(Event::class)->create();
         $theater = factory(Theater::class)->create();
         $data = [
            "stage" => "Salón Principal",
            "start_date" => $storedSchedule->start_date,
            "end_date" => $storedSchedule->end_date,
            'event_id' => $storedSchedule->id,
            'theater_id' => $storedSchedule->id
         ];

         $response = $this->json('post', route('schedules.store'), $data);

         $response->assertStatus(400);
         $response->assertJsonStructure([
            'error','error_message'
        ]);
     }

     /**
     * @test
     *
     * return @void
     */
    public function update_a_schedule() {

        $schedule = factory(Schedule::class)->create([
            'stage' => 'Salón Principal'
        ]);

        $data = [
            'stage' => 'Salón Imperial',
        ];

        $response = $this->json('put', route('schedules.update', [
            'id' => $schedule->id
        ]), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'stage' => 'Salón Imperial'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function update_a_schedule_with_end_date_before_start_date() {

        $schedule = factory(Schedule::class)->create([
            "start_date" => "2018-11-24 09:59:56",
            "end_date" => "2018-11-24 11:59:56"
        ]);

        $data = [
            "end_date" => "2018-11-24 07:59:56"
        ];

        $response = $this->json('put', route('schedules.update', [
            'id' => $schedule->id
        ]), $data);

        $response->assertStatus(422);
        $response->assertJson([
            "The end date must be a date after start date."
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function update_a_schedule_with_collision() {

        $storedSchedule = factory(Schedule::class)->create([
            "start_date" => "2018-11-24 06:59:56",
            "end_date" => "2018-11-24 09:59:56"
         ]);

        $schedule = factory(Schedule::class)->create([
            "start_date" => "2018-11-24 10:00:00",
            "end_date" => "2018-11-24 11:59:56"
        ]);

        $data = [
            "start_date" => "2018-11-24 09:00:00",
        ];

        $response = $this->json('put', route('schedules.update', [
            'id' => $schedule->id
        ]), $data);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'error','error_message'
        ]);
    }

    /**
     * @test
     *
     * return @void
     */
    public function destroy_a_schedule() {

        $schedule = factory(Schedule::class)->create();

        $response = $this->json('delete', route('schedules.destroy', [
            'id' => $schedule->id
        ]));

        $response->assertStatus(204);
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_404_to_undefined_schedule_id_on_destroy() {

        $schedule = factory(Schedule::class)->create();

        $response = $this->json('delete', route('schedules.destroy', [
            'id' => $schedule->id+1
        ]));

        $response->assertStatus(404);

        $response->assertJsonStructure([
            'error','error_message'
        ]);
    }


}
<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Event;
use App\Http\Requests\Event\CreateScheduleRequest;
use App\Http\Requests\Event\UpdateScheduleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $city = $request->query('city') || 'Merida';
        $category_id = $request->query('category') || null;
        $startDate = $request->query('start_date') || null;
        $endDate = $request->query('end_date') || null;

        // if ($category_id) {
        //     $events = Event::where('category_id', $category_id)->get();
        //     //dd($events);

        //     foreach ($events as $event ) {
        //         foreach ($event->schedules as $schedule) {
        //             dd($schedule->pivot);
        //         }
        //     }


        // }

        $schedules = Schedule::with('event')->get();
//dd($request->query());
        $schedules->map(function ($schedule, $key) {
            dd($category_id);
            if($schedule->event->category_id === 3){
                return $schedule;
            } else {
                
            }
        });

        /*$events->map(function ($event, $key) {
            $reviewsCount = $event->reviewsCount();
            $event['reviewsCount'] =  $reviewsCount;
            return $event;
        });*/
        return response()->json($schedules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateScheduleRequest $request) {
        $schedule = new Schedule($request->all());
        $schedule->save();
        return response()->json($schedule, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $schedule = Schedule::with('event', 'theater')->find($id);
        if(!$schedule) {
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }
        return response()->json($schedule);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Theater  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->fill($request->all())->save();
        return response()->json($event, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Theater  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $event = Event::destroy($id);
        if(!$event) {
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }

        return response(null, 204);
    }
}
<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $events = Event::paginate($request->query('limit'));
        $events->map(function ($event, $key) {
            $reviewsCount = $event->reviewsCount();
            $event['reviewsCount'] =  $reviewsCount;
            return $event;
        });
        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEventRequest $request) {
        $event = new Event($request->all());
        $event->save();
        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Theater  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $event = Event::with('category', 'reviews')->find($id);
        if(!$event) {
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }
        $reviewsCount = $event->reviewsCount();
        $event['reviewsCount'] =  $reviewsCount;
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Theater  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, $id)
    {
        $event = Event::find($id);
        if(!$event) {
            abort(404);
        }
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
            abort(404);
        }

        return response(null, 204);
    }
}
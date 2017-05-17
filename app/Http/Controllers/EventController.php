<?php

namespace App\Http\Controllers;

use App\Event;
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
        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $event = new Event($request->all());
        $event->save();
        return response()->json($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Theater  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $event = Event::with('category')->find($id);
        if(!$event) {
            return response()->json([
                "error" => "not_found",
                "error_description" => "The requested resource was not found"
            ], 404);
        }
        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Theater  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $event->fill($request->all())->save();
        return response()->json($event, 201);
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
                "error_description" => "The requested resource was not found"
            ], 404);
        }

        return response(null, 204);
    }
}
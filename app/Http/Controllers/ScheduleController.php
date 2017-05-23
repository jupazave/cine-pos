<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\Event;
use App\Http\Requests\Event\CreateScheduleRequest;
use App\Http\Requests\Event\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\DB;

class ScheduleController extends Controller
{
    protected $category_id;
    protected $limit;
    protected $startDate;
    protected $endDate;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $city = $request->query("city") | null;
        $this->limit = $request->query('limit') | 15;
        $this->category_id = $request->query("category") | null ;
        $this->startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->query('start_date'))->toDateTimeString();
        $this->endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->query('end_date'))->toDateTimeString();

        $schedules = Schedule::where([
            ['start_date', '>=', $this->startDate],
            ['end_date', '<=', $this->endDate]
        ])->whereHas('event', function($query) {
            $query->where('category_id', $this->category_id);
        })->with('event')->paginate($this->limit);

        /*dd($schedules);
        if(!$this->category_id) {

        } else {
            $schedules = Schedule::whereHas('event', function($query) {
                $query->where('category_id', $this->category_id);
            })->with('event')->paginate($this->limit);
        }*/

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
<?php

namespace App\Http\Controllers;

use App\Schedule;
use Carbon\Carbon;
use App\Http\Requests\Schedule\CreateScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use Illuminate\Http\Request;

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
        $this->limit = $request->query('limit') | 15;
        $this->category_id = $request->query("category") | null ;
        $this->startDate = $request->query('start_date') ? Carbon::createFromFormat('Y-m-d H:i:s', $request->query('start_date'))->toDateTimeString() : null;
        $this->endDate = $request->query('end_date') ? Carbon::createFromFormat('Y-m-d H:i:s', $request->query('end_date'))->toDateTimeString() : null;

        if($this->category_id !== null && $this->startDate !== null && $this->endDate !== null) {
            $schedules = Schedule::where([
                ['start_date', '>=', $this->startDate],
                ['end_date', '<=', $this->endDate]])
                ->whereHas('event', function($query) {
                    $query->where('category_id', $this->category_id);
                })
                ->with('event')
                ->paginate($this->limit);
        } else if ($this->startDate !== null && $this->endDate !== null) {
            $schedules = Schedule::where([
                ['start_date', '>=', $this->startDate],
                ['end_date', '<=', $this->endDate]])
                ->with('event')
                ->paginate($this->limit);

        } else if($this->category_id !== null) {
            $schedules = Schedule::whereHas('event', function($query) {
                    $query->where('category_id', $this->category_id);
                })
                ->with('event')
                ->paginate($this->limit);
        } else {
            $schedules = Schedule::where([
                ['start_date', '>=', Carbon::now()->toDateTimeString()]])
                ->with('event')
                ->paginate($this->limit);
        }

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
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->fill($request->all())->save();
        return response()->json($schedule, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $schedule = Schedule::destroy($id);
        if(!$schedule) {
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }

        return response(null, 204);
    }
}
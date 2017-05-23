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
    protected $request;
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
        $this->request = $request;
        $schedule = new Schedule($request->all());

        $schedulesOfSameTheater = Schedule::whereHas('theater', function($query) {
            $query->where('id', $this->request->input('theater_id'));
        })->get();
        
        $flag = $this->checkCollisionDateTimes($request, $schedulesOfSameTheater);

        if($flag){
            $schedule->save();
            return response()->json($schedule, 201);
        } else {
            return response()->json([
                "error" => "bad_request",
                "error_message" => "The schedule has a collision with existing schedule"
            ], 400);
        }
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
            abort(404);
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
    public function update(UpdateScheduleRequest $request, $id)
    {
        $schedule = Schedule::find($id);
        if(!$schedule) {
            abort(404);
        }
        $this->request = $request;
        
        $schedulesOfSameTheater = Schedule::whereHas('theater', function($query) {
            $query->where('id', $this->request->input('theater_id'));
        })->get();
        
        $flag = $this->checkCollisionDateTimes($request, $schedulesOfSameTheater);

        if($flag){
            $schedule->fill($request->all())->save();
            return response()->json($schedule, 200);
        } else {
            return response()->json([
                "error" => "bad_request",
                "error_message" => "The schedule has a collision with existing schedule"
            ], 400);
        }
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
            abort(404);
        }

        return response(null, 204);
    }

    private function checkCollisionDateTimes($request, $schedules) {
        $flag = true;
        foreach ($schedules as $storedSchedule) {
            if ($storedSchedule->end_date < $request->input('start_date') || $storedSchedule->start_date > $request->input('end_date')) {
            } else {
                $flag = false;
            }
        }
        return $flag;
    }
}
<?php

namespace App\Http\Controllers;

use App\Theater;
use App\Http\Requests\Theater\CreateTheaterRequest;
use App\Http\Requests\Theater\UpdateTheaterRequest;
use Illuminate\Http\Request;

class TheaterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $theaters = Theater::paginate($request->query('limit'));
        return response()->json($theaters);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Theater\CreateTheaterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTheaterRequest $request) {
        $theater = new Theater($request->all());
        $theater->save();
        return response()->json($theater, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Theater  $theater
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $theater = Theater::find($id);
        if(!$theater) {
            abort(404);
        }
        return response()->json($theater);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Theater\UpdateTheaterRequest  $request
     * @param  \App\Theater  $theater
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTheaterRequest $request, $id)
    {
        $user = $this->getUser($request);
        $theater = Theater::find($id);
        if(!$theater) {
            abort(404);
        }

        if($user->id != $theater->user_id) {
            abort(403);
        }

        $theater->fill($request->all())->save();
        return response()->json($theater, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Theater  $theater
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $user = $this->getUser($request);

        $dTheater = Theater::find($id);
        if($dTheater) {
            abort(404);
        }

        $theater = Theater::find($id);
        if($user->id != $theater->user_id) {
            abort(403);
        }
        $theater = Theater::destroy($id);
        if(!$theater) {
            abort(404);
        }

        return response(null, 204);
    }

    private function getUser($request) {
        $user = null;
        if($request->attributes->get('user')) {
            $user = $request->attributes->get('user');
        } else {
            abort(403);
        }
        return $user;
    }
}

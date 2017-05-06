<?php

namespace App\Http\Controllers;

use App\Theater;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $theater = new Theater($request->all());
        $theater->save();
        return response()->json($theater);
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
            return response()->json([
                "error" => "not_found",
                "error_description" => "The requested resource was not found"
            ], 404);
        }
        return response()->json($theater);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Theater  $theater
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Theater $theater)
    {
        $theater->fill($request->all())->save();
        return response()->json($theater, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Theater  $theater
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $theater = Theater::destroy($id);
        if(!$theater) {
            return response()->json([
                "error" => "not_found",
                "error_description" => "The requested resource was not found"
            ], 404);
        }

        return response(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $theaters = Category::paginate($request->query('limit'));
        return response()->json($theaters);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Category\CreateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request) {
        $theater = new Category($request->all());
        $theater->save();
        return response()->json($theater, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $theater
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $theater = Category::find($id);
        if(!$theater) {
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }
        return response()->json($theater);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Category\UpdateCategoryRequest  $request
     * @param  \App\Category  $theater
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $theater)
    {
        $theater->fill($request->all())->save();
        return response()->json($theater, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $theater
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $theater = Category::destroy($id);
        if(!$theater) {
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }

        return response(null, 204);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: La_ma
 * Date: 17/05/2017
 * Time: 10:34 PM
 */

namespace App\Http\Controllers;

use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller {

    /*
     * Display the reviews list.
     * @return \Illuminate\Http\Review
     */
    public function index(Request $request)
    {
        $reviews = Review::paginate($request->query('limit'));
        return response()->json($reviews);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateReviewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateReviewRequest $request) {
        $review = new Review($request->all());
        $review->save();
        return response()->json($review, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Review  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $review = Review::find($id);
        if(!$review) {
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }
        return response()->json($review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateReviewRequest  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $review->fill($request->all())->save();
        return response()->json($review, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $review = Review::destroy($id);
        if(!$review) {
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }

        return response(null, 204);
    }

}
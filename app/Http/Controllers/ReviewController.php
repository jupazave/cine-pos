<?php
/**
 * Created by PhpStorm.
 * User: La_ma
 * Date: 17/05/2017
 * Time: 10:34 PM
 */

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Review;
use Illuminate\Http\Request;
use JWTAuth;


class ReviewController extends Controller {

    /*
     * Display the reviews list.
     * @return \Illuminate\Http\Review
     */
    public function index(Request $request, $event_id) {
//        dd($event_id);
        $reviews = Review::where('event_id','=', $event_id)->paginate($request->query('limit'));

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
    public function show($event_id, $id) {
        $review = Review::with('event')->find($id);
        if(!$review) {
            abort(404);
            return response()->json([
                "error" => "not_found",
                "error_message" => "The requested resource was not found"
            ], 404);
        }
        return response()->json($review);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $event_id, $id) {
        $user = $this->getUser($request);

        $dReview = Review::find($id);
        if(!$dReview) {
            abort(404);
        }
        $event = Event::with('reviews')->find($event_id);
        if($user->id != $event->user_id) {
            abort(403);
        }
        $review = Review::destroy($id);
        if(!$review) {
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
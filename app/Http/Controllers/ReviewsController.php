<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}


    public function index()
	{
		return Review::with('user')->paginate(5);
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(Request $request)
	{
		$data = $request->validate([
			'review' => 'required',
		]);
		$review = Auth::user()->reviews()->create($data);

		return response()->json(['review' => $review->load('user')]);

	}

	public function rating($id, $type)
	{
		$review = Review::findOrFail($id);
		$review->increment($type);
		return response()->json(['rating' => $review[$type]]);
	}
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResources;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Travel $travel)
    {
        $tours = $travel->tours->where('travel_id', $travel->id)->sortBy('starting_at');

        return response()->json(TourResources::collection($tours));
    }
}

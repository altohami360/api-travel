<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourListRequest;
use App\Http\Resources\TourResources;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TourController extends Controller
{
    public function index(Travel $travel, TourListRequest $request)
    {
        $tours  = $travel->tours()
            ->when($request->dataFrom, function ($query) use ($request) {
                $query->where('starting_data', '>=', $request->dataFrom);
            })
            ->when($request->dataTo, function ($query) use ($request) {
                $query->where('ending_data', '<=', $request->dataTo);
            })
            ->when($request->priceFrom, function($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom);
            })
            ->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo);
            })
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->get();

        return response()->json(TourResources::collection($tours));
    }
}

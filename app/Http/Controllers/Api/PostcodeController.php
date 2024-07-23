<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Models\Postcodes;
use Illuminate\Http\Request;

class PostcodeController extends ApiController
{

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string'],
        ], [
            'q.required' => 'Search value is required'
        ]);

        $q = $request->input('q');

        $postcodes = Postcodes::where('postcode', 'LIKE', $q . '%')
            ->limit(10)
            ->pluck('postcode');

        return response()->json(['success' => true, 'data' => $postcodes]);
    }
}

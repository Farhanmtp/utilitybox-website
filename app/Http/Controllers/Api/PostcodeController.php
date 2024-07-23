<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CryptoJsAes;
use App\Http\Requests\Api\LoginRequest;
use App\Models\Postcodes;
use Illuminate\Http\Request;

/**
 * @group Powwr
 * @unauthenticated
 */
class PostcodeController extends ApiController
{

    /**
     * Postcode Search
     *
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

        $postcodes = Postcodes::where('postcode', 'LIKE', $q . '%')->orWhere('postcode', $q)
            ->limit(10)
            ->pluck('postcode');

        return response()->json(['success' => true, 'data' => $postcodes, 'enc' => CryptoJsAes::encrypt($postcodes)]);
    }
}

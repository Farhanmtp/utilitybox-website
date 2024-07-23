<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\PowwrSupplierCollection;
use App\Models\PowwrSupplier;
use Illuminate\Http\Request;

class SuppliersController extends ApiController
{
    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $suppliers = PowwrSupplier::all();

        return $this->successResponse(new PowwrSupplierCollection($suppliers));
    }
}

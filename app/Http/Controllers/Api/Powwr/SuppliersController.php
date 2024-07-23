<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\SupplierCollection;
use App\Models\Suppliers;
use Illuminate\Http\Request;

/**
 * @group Powwr
 * @unauthenticated
 */
class SuppliersController extends ApiController
{
    /**
     * Supplier List
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $suppliers = Suppliers::active()->get();

        return $this->successResponse(new SupplierCollection($suppliers));
    }
}

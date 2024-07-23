<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\DealResource;
use App\Models\Deals;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('ApiToken')->plainTextToken,
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $user->createToken('ApiToken')->plainTextToken,
        ]);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function validateEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $email = $request->email;

        $user = User::select('id', 'first_name', 'last_name', 'email')->where('email', $email)->first();
        $deals = Deals::with(['supplier'])->where(function ($query) use ($email) {
            $query->where('customer_email', $email)->orWhere('customer->email', $email);
        })->where('status', 'pending')->get();

        $response = [
            'user' => $user,
            'deals' => DealResource::collection($deals),
        ];

        return $this->successResponse($response, '');
    }

}

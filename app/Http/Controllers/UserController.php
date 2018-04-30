<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

class UserController extends Controller
{
    /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    /**
     * Register user via API
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|max:255|unique:users',
            'name'     => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        User::create([
            'name'     => $request->get('name'),
            'email'    => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        $user = User::first();
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token'));
    }

    public function changeName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = auth()->user();
        $user->name = $request->get('name');
        $user->save();

        return response()->json([
            'message' =>
                "User name was successfully changed to {$request->get('name')}",
        ]);
    }
}

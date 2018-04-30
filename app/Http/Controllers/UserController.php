<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
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
     * Check if password is right
     *
     * @param string $password
     *
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($password, auth()->user()->password);
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

    /**
     * Change user name
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Change user email
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeEmail(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'email'    => "required|string|email|max:255|unique:users,email,{$user->id}",
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (!$this->checkPassword($request->get('password'))) {
            return response()->json(['error' => 'Please enter valid password'],
                401);
        }

        $user->email = $request->get('email');
        $user->save();

        return response()->json([
            'message' =>
                "User email was successfully changed to {$request->get('email')}",
        ]);
    }

    /**
     * Change user password
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'password'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (!$this->checkPassword($request->get('password'))) {
            return response()->json(['error' => 'Please enter valid password'],
                401);
        }

        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        return response()->json([
            'message' =>
                "User password was successfully changed",
        ]);
    }

    /**
     * Delete user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = auth()->user();
        $credentials = request(['email', 'password']);

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Please enter valid credentials'],
                401);
        }

        auth()->logout();
        $user->delete();

        return response()->json([
            'message' =>
                "Your account was successfully deleted",
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(name="User Authentication", description="User registration, login, and logout")
 */
class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"User Authentication"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful registration with token",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="Bearer {token}")
     *         )),
     *     @OA\Header(
     *         header="Accept",
     *         description="Specify the response format",
     *         required=true,
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *     @OA\Header(
     *         header="Content-Type",
     *         description="Content type of the request body",
     *         required=true,
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create($validated);

        return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"User Authentication"},
     *     summary="Login a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful login with token",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="Bearer {token}")
     *         )),
     *     @OA\Header(
     *         header="Accept",
     *         description="Specify the response format",
     *         required=true,
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *     @OA\Header(
     *         header="Content-Type",
     *         description="Content type of the request body",
     *         required=true,
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"User Authentication"},
     *     summary="Logout the user",
     *     security={{ "bearer": {} }},
     *     @OA\Response(response=200, description="Logged Out"),
     *     @OA\Header(
     *         header="Accept",
     *         description="Specify the response format",
     *         required=true,
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     *     @OA\Header(
     *         header="Content-Type",
     *         description="Content type of the request body",
     *         required=true,
     *         @OA\Schema(type="string", example="application/json")
     *     ),
     * )
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged Out!']);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Whoops\Handler\PlainTextHandler;

class AuthController extends Controller
{
/**
 * @OA\Post(
 *     path="/api/auth/register",
 *     summary="Registrar un nuevo usuario",
 *     tags={"Autenticación"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string", example="Juan Pérez"),
 *             @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Usuario registrado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Juan Pérez"),
 *                 @OA\Property(property="email", type="string", example="juan@example.com"),
 *                 @OA\Property(property="created_at", type="string", example="2025-04-07T12:00:00.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", example="2025-04-07T12:00:00.000000Z")
 *             ),
 *             @OA\Property(property="token", type="string", example="1|sadasdJHASD...ASDqweqwe")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Datos inválidos"
 *     )
 * )
 */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }
/**
 * @OA\Post(
 *     path="/api/auth/login",
 *     summary="Iniciar sesión",
 *     tags={"Autenticación"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="12345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Inicio de sesión exitoso",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Juan Pérez"),
 *                 @OA\Property(property="email", type="string", example="juan@example.com"),
 *                 @OA\Property(property="created_at", type="string", example="2025-04-07T12:00:00.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", example="2025-04-07T12:00:00.000000Z")
 *             ),
 *             @OA\Property(property="token", type="string", example="2|jhjhgASDJASDASDhj")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="unauthorized"
 *     )
 * )
 */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!Auth::attempt($request->only('email', 'password')))
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }
/**
 * @OA\Post(
 *     path="/api/auth/logout",
 *     summary="Cerrar sesión del usuario autenticado",
 *     tags={"Autenticación"},
 *     security={{"BearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Sesión cerrada exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Sesión cerrada")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="unauthorized"
 *     )
 * )
 */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}



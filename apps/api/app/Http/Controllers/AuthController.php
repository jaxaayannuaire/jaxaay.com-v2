<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $u = User::create($request->validated());

        return response()->json(['data' => new UserResource($u), 'token' => $u->createToken('api')->plainTextToken], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        $u = User::where('email', $data['email'])->first();
        if (! $u || ! Hash::check($data['password'], $u->password)) {
            return response()->json(['error' => ['code' => 'INVALID_CREDENTIALS', 'message' => 'Identifiants invalides.']], 401);
        }

return ['data' => new UserResource($u), 'token' => $u->createToken('api')->plainTextToken];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['data' => ['message' => 'Déconnexion réussie.']]);
    }

    public function me(Request $request)
    {
        return ['data' => new UserResource($request->user())];
    }
}

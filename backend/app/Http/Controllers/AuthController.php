<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\{LoginRequest, RegisterRequest};
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DB;
use Exception;
use Hash;
use Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::firstWhere(['email' => $validated['email']]);

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciais não encontradas'
            ], 401);
        }

        $remainingTime = now()->addHours(6);

        $token = $user->createToken('auth', expiresAt: $remainingTime);

        return response()->json([
            'message' => 'Usuário autenticado com sucesso',
            'user' => $user->only('name', 'email'),
            'remaining_time' => $remainingTime,
            'token' => $token->plainTextToken,
        ], 200);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                User::create($validated);
            });

            return response()->json([
                'message' => 'Usuário criado com sucesso'
            ], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'error' => 'Falha ao registrar usuário, tente novamente mais tarde'
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout feito com sucesso'
        ], 200);
    }
}

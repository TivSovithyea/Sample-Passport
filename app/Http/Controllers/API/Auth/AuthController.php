<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RefreshRequest;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $response = Http::asForm()->post(env('APP_URL') .'/oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'username' => $request->username,
            'password' => $request->password,
            'scope' => '',
        ]);

        $data = $response->json();

        return response()->json($data, $response->getStatusCode());
    }

    public function refresh(RefreshRequest $request)
    {
        $response = Http::asForm()->post(env('APP_URL') .'/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'scope' => '',
        ]);

        $data = $response->json();

        return response()->json($data, $response->getStatusCode());
    }

    public function logout(Request $request)
    {

        $request->user()->token()->revoke();

        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($request->user()->token()->id);
    }

    public function user()
    {
        return response()->json([
            'user' => Auth::user()
        ]);
    }
}



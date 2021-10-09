<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client as OClient;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'is_admin' => false,
            'password' => bcrypt($request->input('password')),
        ]);
    }

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            return $this->getTokenAndRefreshToken(request('email'), request('password'));
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function getTokenAndRefreshToken($email, $password)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', url('/') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);
        $result = json_decode((string)$response->getBody(), true);
        return response()->json($result, 200);
    }

    public function refreshToken(Request $request)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $response = Http::asForm()->post(url('/') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'scope' => '',
        ]);
        return $response->json();
    }

    public function logout(Request $request)
    {
        Auth::user()->token()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}

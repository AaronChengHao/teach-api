<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $type = $request->input('type'); // 'teacher' or 'student'
        $guard = $type === 'teacher' ? 'teacher' : 'student';

        $request->validate([
            'account' => 'required',
            'password' => 'required',
        ]);

        // 请求授权
        $response = Http::asForm()->post('http://127.0.0.1:8881/oauth/token', [
            'grant_type' => 'password',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'username' => 'taylor@laravel.com',
            'password' => 'my-password',
            'scope' => '*',
        ]);


        var_dump($response);die;

        var_dump(get_class(Auth::guard($guard)));
        if (Auth::guard($guard)->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard($guard)->user();
            // 根据用户类型获取访问令牌
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
//        $credentials = $request->only('account', 'password', 'type');
//
//        $guard = $credentials['type'] === 'teacher' ? 'teacher' : 'student';
//        if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
//            $request->session()->regenerate();
//
//            $token = Auth::guard($guard)->user()->createToken('token-name')->accessToken;
//
//            return response()->json(['token' => $token]);
//        }
//
//        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

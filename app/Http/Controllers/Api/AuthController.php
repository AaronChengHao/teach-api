<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Trait\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;

class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * 登录
     *
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'type'     => 'required'
        ]);
        $type     = $request->input('type'); // 'teacher' or 'student'
        $username = $request->input('username');
        $password = $request->input('password');

        $oauthClient = Passport::client()->whereProvider($type . 's')->firstOrFail();

        $clientId = $oauthClient->id;
        $clientKey = $oauthClient->secret;

        $host = 'nginx:81';
        $protocol = 'http';
        if (app()->environment() == 'testing') {
            $host = 'www.comiru-api.test';
        }

        if (app()->environment() == 'production') {
            $host = 'poper-ops-interview-05.herokuapp.com';
            $protocol = 'https';
        }

        $response = Http::asForm()->post("{$protocol}://{$host}/oauth/token", [
            'grant_type' => 'password',
            'client_id' => $clientId,
            'client_secret' => $clientKey,
            'username' => $username,
            'password' => $password,
            'scope' => '*',
        ]);

        if  (!$response->successful()) {
            $errMsg = $response->json('message');
            if (empty($errMsg)){
                $errMsg = $response->body();
            }
            return $this->apiError(message: $errMsg);
        }

        $tokenJson = $response->json();
        $result['token'] = $tokenJson;
        $result['role'] = $type;
        return $this->apiSuccess($result);
    }

    /**
     * 我的信息
     *
     * @param Request $request
     * @return array
     */
    public function me(Request $request)
    {
        return $this->apiSuccess($request->user());
    }
}

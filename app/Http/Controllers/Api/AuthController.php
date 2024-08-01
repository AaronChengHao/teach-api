<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Trait\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    use ResponseTrait;

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
        $guard    = $type === 'teacher' ? 'teacher' : 'student';

        $user = null;

        switch ($type) {
            case 'teacher':
                $user = Teacher::query()->whereAccount($username)->wherePassword($password)->first();
                break;
            case 'student':
                $user = Student::query()->whereAccount($username)->wherePassword($password)->first();
                break;
            default:
                throw new \Exception('xxxx');
        }

        if (empty($user)) {
            return $this->apiError(message: '账号或密码错误');
        }

        $result      = [];
        $tokenResult = $user->createToken('Personal Access Token');

        $result['user']      = $user;
        $result['metaToken'] = $tokenResult->token;
        $result['token']     = [
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
        ];
        $result['role'] = $type;
        return $this->apiSuccess($result);
    }

    public function me(Request $request)
    {
        return $this->apiSuccess($request->user());
    }
}

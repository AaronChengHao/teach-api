<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Teacher extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * 查找给定用户名的用户实例。
     */
    public function findForPassport(string $username): User
    {
        return $this->where('username', $username)->first();
    }

    /**
     * 验证用户的密码以获得 Passport 密码授权。
     */
    public function validateForPassportPasswordGrant(string $password): bool
    {
        return true;
    }
}

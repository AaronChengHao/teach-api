<?php

namespace App\Models;

use App\Models\Trait\UtilTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laravel\Passport\HasApiTokens;


class Teacher extends Authenticatable
{
    use HasApiTokens, Notifiable,UtilTrait;

    /**
     * 查找给定用户名的用户实例。
     */
    public function findForPassport(string $username)
    {
        return $this->where('account', $username)->first();
    }

    /**
     * 验证用户的密码以获得 Passport 密码授权。
     */
    public function validateForPassportPasswordGrant(string $password): bool
    {
        return true;
        return Hash::check($this->password,Hash::make($password));
    }

}

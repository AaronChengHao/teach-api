<?php

namespace App\Models;

use App\Models\Trait\UtilTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * 学生模型
 */
class Student  extends Authenticatable
{
    use HasApiTokens, Notifiable,UtilTrait;

    /**
     * 关联的课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class)->using(CourseStudent::class)->withTimestamps();
    }


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
    }
}

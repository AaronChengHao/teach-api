<?php

namespace App\Models;

use App\Models\Trait\UtilTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Student  extends Authenticatable
{
    use HasApiTokens, Notifiable,UtilTrait;

    /**
     * 课程
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class)->using(CourseStudent::class)->withTimestamps();
    }
}

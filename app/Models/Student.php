<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Student  extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    /**
     * 课程
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class)->using(CourseStudent::class)->withTimestamps();
    }
}

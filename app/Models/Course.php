<?php

namespace App\Models;

use App\Models\Trait\UtilTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory,UtilTrait;

    public function students()
    {
        return $this->belongsToMany(Student::class)->using(CourseStudent::class)->withTimestamps();
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}

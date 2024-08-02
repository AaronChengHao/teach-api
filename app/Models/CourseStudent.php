<?php

namespace App\Models;

use App\Models\Trait\UtilTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseStudent extends Pivot
{
    use HasFactory,UtilTrait;
}

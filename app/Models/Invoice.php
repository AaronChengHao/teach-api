<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $relations = ['teacher', 'course'];

    protected $appends = ['status_text'];

    /**
     * 待发送
     */
    public const STATUS_WAIT_SEND = '1';

    /**
     * 待支付
     */
    public const STATUS_WAIT_PAY = '2';

    /**
     * 已支付
     */
    public const STATUS_PAYED = '3';


    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public function getStatusTextAttribute()
    {
        $text = '';
        switch ($this->status) {
            case 1:
                $text = '待发送';
                break;
            case 2:
                $text = '待支付';
                break;
            default:
                break;
        }
        return $text;
    }
}

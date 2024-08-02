<?php

namespace App\Models;

use App\Models\Trait\UtilTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory,UtilTrait;

    protected $relations = ['teacher', 'course'];

    protected $appends = ['status_text','status_class'];

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

    public function getStatusTextAttribute()
    {
        $text = '';
        switch ($this->status) {
            case Invoice::STATUS_WAIT_SEND:
                $text = '待发送';
                break;
            case Invoice::STATUS_WAIT_PAY:
                $text = '待支付';
                break;
            case Invoice::STATUS_PAYED:
                $text = '已支付';
                break;
            default:
                break;
        }
        return $text;
    }
    public function getStatusClassAttribute()
    {
        $text = '';
        switch ($this->status) {
            case Invoice::STATUS_WAIT_SEND:
                $text = 'primary';
                break;
            case Invoice::STATUS_WAIT_PAY:
                $text = 'warning';
                break;
            case Invoice::STATUS_PAYED:
                $text = 'success';
                break;
            default:
                break;
        }
        return $text;
    }
}

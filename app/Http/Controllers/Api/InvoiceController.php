<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Trait\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Invoice;
use Illuminate\Http\Request;

/**
 * 账单控制器
 */
class InvoiceController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Invoice::query()->with(['teacher','student','course'])->orderByDesc('id')-> paginate($request->input('size',10)); // 每页显示10条数据
        return $this->apiSuccess($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'  => ['required', 'exists:courses,id'],
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $courseId  = $request->input('course_id');
        $studentId = $request->input('student_id');
        $teacherId = $request->user()->id;

        $course = Course::findOrFail($courseId);

        $invoice             = new Invoice();
        $invoice->course_id  = $courseId;
        $invoice->student_id = $studentId;
        $invoice->teacher_id = $teacherId;
        $invoice->price      = $course->price;
        $invoice->status     = Invoice::STATUS_WAIT_SEND;
        $invoice->saveOrFail();

        return $this->apiSuccess([]);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * 发送
     *
     * @return void
     */
    public function send(Invoice $invoice)
    {
        $invoice->status = Invoice::STATUS_WAIT_PAY;
        $invoice->saveOrFail();
        return $this->apiSuccess([]);
    }
}

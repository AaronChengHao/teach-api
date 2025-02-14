<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Trait\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

/**
 * 课程控制器
 */
class CourseController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 使用分页获取 Post 模型的实例
        $posts = Course::query()->whereTeacherId($request->user()->id)->orderByDesc('id')-> paginate($request->input('size',10)); // 每页显示10条数据
        return $this->apiSuccess($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $price = $request->input('price');
        $yearMonth = $request->input('year_month');
        $students = $request->input('student_ids');

        $course = new Course();
        $course->name = $name;
        $course->price = $price;
        $course->year_month = $yearMonth;
        $course->teacher_id = $request->user()->id;

        $course->saveOrFail();

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
        $name = $request->input('name');
        $price = $request->input('price');
        $yearMonth = $request->input('year_month');

        $course =Course::find($id);
        $course->name = $name;
        $course->price = $price;
        $course->year_month = $yearMonth;

        $course->saveOrFail();

        return $this->apiSuccess([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Course::destroy($id);
        return $this->apiSuccess([]);
    }
}

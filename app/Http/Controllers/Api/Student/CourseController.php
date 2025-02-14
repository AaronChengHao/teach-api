<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Api\Trait\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
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
        /** @var $student Student **/
        $student = $request->user();
        $posts = $student->courses()->with('teacher')->orderByDesc('id')-> paginate($request->input('size',10));
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
        $date = $request->input('date');

        $course = new Course();
        $course->name = $name;
        $course->price = $price;
        $course->date = $date;
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
        $date = $request->input('date');

        $course =Course::find($id);
        $course->name = $name;
        $course->price = $price;
        $course->date = $date;

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

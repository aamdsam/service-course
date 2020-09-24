<?php

namespace App\Http\Controllers;

use App\ImageCourse;
use App\Course;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ImageCourseController extends Controller
{
    public function index(Request $request)
    {
        $imageCourse = ImageCourse::query();
        $courseId = $request->query('course_id');

        $imageCourse->when($courseId, function ($query) use ($courseId) {
            return $query->where('course_id', '=', $courseId);
        });
        return response()->json([
            'status' => 'success',
            'data' => $imageCourse->get()
        ]);
    }

    public function show($id)
    {
        $imageCourse = ImageCourse::find($id);
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'imageCourse Not Found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $imageCourse
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'course_id' => 'required|integer',
            'image' => 'required|url'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found',
            ], 404);
        }

        $imageCourse = ImageCourse::create($data);

        return response()->json(['status' => 'success', 'data' => $imageCourse]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'course_id' => 'integer',
            'image' => 'url'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $imageCourse = ImageCourse::find($id);
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'imageCourse not found',
            ], 404);
        }

        $CourseId = $request->input('course_id');
        if ($CourseId) {
            $course = Course::find($CourseId);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'course not found',
                ], 404);
            }
        }

        $imageCourse->fill($data);

        $imageCourse->save();
        return response()->json(['status' => 'success', 'data' => $imageCourse]);
    }

    public function destroy($id)
    {
        $imageCourse = ImageCourse::find($id);
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'image_course Not Found'
            ], 404);
        }

        $imageCourse->delete();

        return response()->json([
            'status' => 'success',
            'data' => $imageCourse
        ]);
    }
}

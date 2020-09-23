<?php

namespace App\Http\Controllers;

use App\MyCourse;
use App\Course;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MyCourseController extends Controller
{
    public function index(Request $request)
    {
        $myCourses = MyCourse::query()->with('course');
        $courseId = $request->query('course_id');
        $userId = $request->query('user_id');

        $myCourses->when($courseId, function ($query) use ($courseId) {
            return $query->where('course_id', '=', $courseId);
        });

        $myCourses->when($userId, function ($query) use ($userId) {
            return $query->where('user_id', '=', $userId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $myCourses->get()
        ]);
    }

    public function show($id)
    {
        $myCourse = MyCourse::find($id);
        if (!$myCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'myCourse Not Found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'course_id' => 'required|integer',
            'user_id' => 'required|integer'
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
                'message' => 'course not found'
            ], 404);
        }

        $userId = $request->input('user_id');
        $user = getUser($userId);
        if ($user['status']==='error'){
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']); 
        }

        $isExistMyCourse = MyCourse::where('course_id','=', $courseId)
                            ->where('user_id','=', $userId)
                            ->exists();

        if ($isExistMyCourse){
            return response()->json([
                'status' => 'error',
                'message' => 'user already take this course'
            ], 409);
        }
        $myCourse = MyCourse::create($data);

        return response()->json(['status' => 'success', 'data' => $myCourse]);
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

        $myCourse = MyCourse::find($id);
        if (!$myCourse) {
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

        $myCourse->fill($data);

        $myCourse->save();
        return response()->json(['status' => 'success', 'data' => $myCourse]);
    }

    public function destroy($id)
    {
        $myCourse = MyCourse::find($id);
        if (!$myCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'image_course Not Found'
            ]);
        }

        $myCourse->delete();

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}

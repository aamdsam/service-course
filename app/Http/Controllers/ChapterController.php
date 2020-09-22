<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chapter;
use App\Course;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function index()
    {
        $mentors = Chapter::all();
        return response()->json([
            'status' => 'success',
            'data' => $mentors
        ]);
    }

    public function show($id)
    {
        $mentor = Chapter::find($id);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor Not Found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
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

        $chapter = Chapter::create($data);

        return response()->json(['status' => 'success', 'data' => $chapter]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'profile' => 'url',
            'profession' => 'string',
            'email' => 'email'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $mentor = Chapter::find($id);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found',
            ], 404);
        }

        $mentor->fill($data);

        $mentor->save();
        return response()->json(['status' => 'success', 'data' => $mentor]);
    }

    public function destroy($id)
    {
        $mentor = Chapter::find($id);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor Not Found'
            ]);
        }

        $mentor->delete();

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    }
}

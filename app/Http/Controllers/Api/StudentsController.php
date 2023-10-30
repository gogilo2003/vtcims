<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Student;
use App\Http\Resources\StudentResource;
use Illuminate\Validation\Rule;

use Validator;

class StudentsController extends Controller
{
    public function getStudents(Request $request)
    {
        // $data = collect(Student::get()->toArray());
        // $filtered = $data->only(['admission_no','name','intake_name','course_name','sponsor_name','program_name']);
        // return response($data)->header('Content-Type','application/json');

        return StudentResource::collection(Student::orderBy('created_at', 'DESC')->get());
    }

    public function getStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:students,id'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Some fields failed Validation. Please check and try again',
                'errors' => $validator->errors()
            ])->header('Content-Type', 'application/json');
        }

        $student = Student::find($request->id);
        return response($student)->header('Content-Type', 'application/json');
    }

    public function setStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admission_no' => 'required|exists:students,id',
            'status' => [
                'required',
                Rule::in(['In session', 'On Attachment', 'Completed', 'Dropout'])
            ]
        ]);

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => 'Some fields failed validation',
                'errors' => make_html_list($validator->errors()->all())
            ];
            return response()->json($res);
        }

        $student = Student::find($request->admission_no);
        $student->status = $request->status;
        $student->save();

        $res = [
            'success' => true,
            'message' => 'Status Updated',
        ];
        return response()->json($res);
    }
}

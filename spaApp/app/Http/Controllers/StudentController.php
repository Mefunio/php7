<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        $students = Student::all();
        return StudentResource::collection($students);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'index' => 'regex:/^s[0-9][0-9][0-9]$/ | unique:students',
            'email' => 'required|email|unique:students',
            'age' => 'required|integer|min:18|max:100',
            'description' => 'nullable|string',
            'paid' => 'boolean'
        ]);

        $student = Student::create($validatedData);

        return new StudentResource($student);
    }

    public function show(Student $student)
    {
        return new StudentResource($student);
    }

    public function update(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'index' => "regex:/^s[0-9][0-9][0-9]$/ | unique:students,index,$student->id",
            'email' => "required|email|unique:students,email,$student->id",
            'age' => 'required|integer|min:18|max:100',
            'description' => 'nullable|string',
            'paid' => 'boolean'
        ]);

        $student->update($validatedData);

        return new StudentResource($student);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully'
        ], 204);
    }
}



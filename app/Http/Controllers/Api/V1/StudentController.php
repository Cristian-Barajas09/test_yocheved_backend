<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function __construct(
        private StudentService $studentService
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ["data" => $this->studentService->all()];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        $student = $this->studentService->create(
            $request->name,
            $request->middle_name,
            $request->last_name,
            $request->birth_date
        );
        return response()->json(["data" => $student], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($student_id)
    {

        $student = $this->studentService->find($student_id);

        if (!$student) {
            return response()->json(["message" => "Student not found"], 404);
        }

        return response()->json(["data" => $student]);

    }
}

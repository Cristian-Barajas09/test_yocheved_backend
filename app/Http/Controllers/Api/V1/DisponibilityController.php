<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisponibilityRequest;
use App\Models\StudentDisponibility;
use Illuminate\Http\Request;
use App\Services\StudentAvailableService;

class DisponibilityController extends Controller
{

    public function __construct(
        private StudentAvailableService $studentDisponibility
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disponibilities = StudentDisponibility::all(["id", "day"]);

        $availability = [];

        foreach ($disponibilities as $disponibility) {
            $availability[$disponibility->day] = true;
        }

        return response()->json(["data" => $availability]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($student_id , DisponibilityRequest $request) {

        $disponibility = $this->studentDisponibility->create($student_id, $request->day);

        return response()->json(["data" => $disponibility], 201);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($student_id, $day) {
        $disponibility = $this->studentDisponibility->delete($student_id, $day);

        return response()->json(["data" => $disponibility], 200);
    }

}

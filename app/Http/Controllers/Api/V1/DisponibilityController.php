<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisponibilityRequest;
use App\Models\StudentDisponibility;
use Illuminate\Http\Request;

class DisponibilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["data" => StudentDisponibility::all(["id","day"])]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(int $user_id, DisponibilityRequest $request)
    {

        $day = $request->day;

        $disponibility = StudentDisponibility::create([
            'student_id' => $user_id,
            'day' => $day,
        ]);

        return response()->json(["data" => $disponibility], 201);
    }

}

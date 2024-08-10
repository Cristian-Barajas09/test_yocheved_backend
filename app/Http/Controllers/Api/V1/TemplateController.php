<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        // Store the data
        $template = Template::create($request->all());

        // Return the response
        return response()->json([
            'status' => 'success',
            'message' => 'Template created successfully',
            'data' => $template
        ], 201);
    }

}

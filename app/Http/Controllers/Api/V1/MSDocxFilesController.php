<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\MSDocxFilesService;
use Illuminate\Http\Request;

class MSDocxFilesController extends Controller
{

    public function __construct(
        private MSDocxFilesService $msDocxFilesService
    ){}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:docx|max:1024',
        ]);

        $file = $request->file('file');
        $path = $file->getPathname();

        $content = $this->msDocxFilesService->readDocxFile($path);

        return response()->json([
            'content' => $content,
        ]);
    }
}

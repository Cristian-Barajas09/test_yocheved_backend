<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use ZipArchive;

class ReportController extends Controller
{

    public function __construct(
        private ReportService $reportService
    ) {}

    public function testPdf() {
        return $this->reportService->testPdf();
    }

    public function getReportByTemplate($templateId,Request $request) {
        $startDate = $request->input('start_date');
        $studentId = $request->input('student_id');
        $endDate = $request->input('end_date');
        $duration = $request->input('duration');

        $reports = $this->reportService->getReportByTemplate($templateId, $startDate, $endDate, $duration, $studentId);

        // Crear un archivo ZIP
        $zip = new ZipArchive;
        $zipFileName = 'reports.zip';
        $zipFilePath = storage_path($zipFileName);

        

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            if(!$reports) {
                $zip->close();
                return response()->json(['message' => 'No reports found'], 404);
            }
            foreach ($reports as $index => $report) {
                $fileName = 'report_' . ($index + 1) . '.pdf';
                $zip->addFromString($fileName, $report);
            }
            $zip->close();
        }

        // Enviar el archivo ZIP al cliente
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}

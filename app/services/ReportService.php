<?php

namespace App\Services;

use App\Models\ScheduleSession;
use App\Models\Session;
use App\Models\Template;
use Illuminate\Database\Eloquent\Collection;
use PDF;

class ReportService
{


    public function testPdf()
    {
        $data = [
            'title' => 'Test PDF',
            'content' => 'This is a test PDF file.'
        ];

        $pdf = PDF::loadView('pdf.test', $data);
        return $pdf->download('test.pdf');
    }

    public function getReportByTemplate($templateId, $startDate, $endDate, $duration, $studentId)
    {
        $template = Template::find($templateId);


        if (!$template) {
            return null;
        }

        // Filtrar sesiones por rango de fechas
        // session->start >= $startDate && session->end <= $endDate
        /**
         * @var Collection<int,ScheduleSession>
         */
        $schedule_sessions = ScheduleSession::whereHas('session', function ($query) use ($startDate, $endDate, $studentId) {
            $query->where('start', '>=', $startDate)
            ->where('end', '<=', $endDate)
            ->where('student_id', $studentId);
        })->get();



        $reports = [];
        foreach ($schedule_sessions as $schedule_session) {

            $compileTemplate = $this->replaceShortcodes($template->content, [
                'student_full_name' => $schedule_session,
                'session_date' => $schedule_session->start,
                'session_minutes' => $schedule_session->duration,
                'session_start_time' => $schedule_session->start,
                'session_end_time' => $schedule_session->end,
                'target_start_date' => "2021-01-01",
                'target_end_date' => "2021-12-31",
                'target' => 5,
                'session_rating' => $schedule_session->target
            ]);

            $data = [
                'title' => 'Student improvement report card',
                'content' => $compileTemplate
            ];

            $pdf = PDF::loadView('pdf.template', $data);
            $reports[] = [
                'pdf_content' => $pdf->output(),
                'session_minutes' => $schedule_session->duration
            ];
        }

        return $this->splitReports($reports, $duration);
    }

    private function splitReports($reports, $duration)
    {
        $splitReports = [];

        foreach ($reports as $report) {
            $totalMinutes = $report['session_minutes'];

            $numReports = ceil($totalMinutes / $duration);

            for ($i = 0; $i < $numReports; $i++) {
                $splitData = [
                    'title' => 'Student improvement report card',
                    'content' => $this->getSplitContent($report['pdf_content'], $i, $duration)
                ];

                $pdf = PDF::loadView('pdf.template', $splitData);
                $splitReports[] = $pdf->output();
            }
        }

        return $splitReports;
    }

    private function getSplitContent($content, $index, $duration)
    {
        // Implementa la lógica para dividir el contenido en partes
        // Aquí puedes ajustar el contenido según el índice y la duración
        return $content . " (Part " . ($index + 1) . ")";
    }

    private function replaceShortcodes($template, $data)
    {
        $shorcodes = [
            'student_full_name' => function ($data) {
                return $data['student_full_name'];
            },
            'session_date' => function ($data) {
                return $data['session_date'];
            },
            'session_minutes' => function ($data) {
                return $data['session_minutes'];
            },
            'session_start_time' => function ($data) {
                return $data['session_start_time'];
            },
            'session_end_time' => function ($data) {
                return $data['session_end_time'];
            },
            'target_start_date' => function ($data) {
                return $data['target_start_date'];
            },
            'target_end_date' => function ($data) {
                return $data['target_end_date'];
            },
            'target' => function ($data) {
                return $data['target'];
            },
            'session_rating' => function ($data) {
                return $data['session_rating'];
            }
        ];

        $prefix = '@';

        foreach ($shorcodes as $key => $value) {
            $template = str_replace($prefix . $key, $value($data), $template);
        }



        return $template;
    }
}

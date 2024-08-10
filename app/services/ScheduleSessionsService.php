<?php

namespace App\Services;

use App\Mail\ScheduleSessionsMail;
use App\Models\ScheduleSession;
use App\Models\Session;
use App\Models\StudentDisponibility;
use Illuminate\Support\Facades\Mail;

class ScheduleSessionsService
{

    public function __construct() {}

    public function create(Session $session, $start, $end,$duration,$is_recurring = false)
    {

        $scheduleSession = new ScheduleSession();
        $scheduleSession->start = $start;
        $scheduleSession->end = $end;
        $scheduleSession->duration = $duration;
        $scheduleSession->target = 0;
        $scheduleSession->session_id = $session->id;


        $scheduleSession->save();
        if ($is_recurring) {
            $this->repeatDaily($session, $start, $end, $duration);
        }
        return $scheduleSession;
    }

    private function repeatDaily(Session $session, $start, $end, $duration)
    {
        $currentStart = strtotime($start);
        $currentEnd = strtotime($end);
        $maxDuration = 15 * 60; // 15 minutos en segundos

        while ($duration <= $maxDuration) {
            $currentStart = strtotime('+1 day', $currentStart);
            $currentEnd = strtotime('+1 day', $currentEnd);

            $this->create($session, date('Y-m-d H:i:s', $currentStart), date('Y-m-d H:i:s', $currentEnd), $duration);

            $duration += $duration;
        }
    }


    public function existSessionDoesEqualDate($start, $end)
    {
        $condition = ScheduleSession::where(function ($query) use ($start, $end) {
            $query->whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end]);
        })->exists();

        return $condition;
    }
    public function rateStudent(Session $session, $rating)
    {
        if ($rating < 0 || $rating > 10) {
            throw new \Exception("La calificación debe estar entre 0 y 10.");
        }

        $session->student_rating = $rating;
        $session->save();
    }

    public function sendNotification(ScheduleSession $session)
{
    $studentEmail = $session->session->student->email;
    $studentName = $session->session->student->name . ' ' . $session->session->student->lastname;
    $userEmail = $session->session->user->email;
    $userName = $session->session->user->name . ' ' . $session->session->user->lastname;

    Mail::to($studentEmail)->send(new ScheduleSessionsMail(
        $studentEmail,
        $studentName,
        $session->start
    ));

    Mail::to($userEmail)->send(new ScheduleSessionsMail(
        $userEmail,
        $userName,
        $session->start
    ));
}



    public function assignTargetStudents($session_id, $target)
    {
        /**
         * @var ScheduleSession $session
         */
        $session = ScheduleSession::find($session_id);

        if (!$session) {
            return null;
        }

        // validate that the target is greater than the period

        $period = $session->session()->first()->period_by_session()
            ->first()
            ->period()
            ->first();

        if(!$period) {
            return null;
        }

        $period_target = $period->target;

        if ($period_target > $target) {
            return null;
        }

        $session->target = $target;

        $session->save();
        return $session;
    }
}

<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScheduleSessionsMail;
use App\Models\ScheduleSession;
use Carbon\Carbon;

class NotifyUsersBeforeSession extends Command
{
    protected $signature = 'notify:users-before-session';
    protected $description = 'Notifica a los usuarios y estudiantes 5 minutos antes de la sesión programada';

    public function handle()
    {
        $sessions = ScheduleSession::where('start', '=', Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s'))->get();

        foreach ($sessions as $session) {
            $this->sendNotification($session);
        }
    }

    private function sendNotification(ScheduleSession $session)
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
}

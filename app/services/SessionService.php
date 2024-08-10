<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Student;

class SessionService {

    public function getSessions() {
        $sessions = Session::with(['student','user','scheduleSessions'])->paginate(
            request()->get('per_page', 10)
        );

        return $sessions;
    }

    public function create($student_id,$user_id,): Session | null {

        $student =  Student::find($student_id);

        if(!$student) {
            return null;
        }

        $session = new Session();

        $session->student_id = $student_id;
        $session->user_id = $user_id;
        $session->save();

        return $session;
    }
}

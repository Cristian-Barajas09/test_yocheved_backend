<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SessionRequest;
use App\Models\ScheduleSession;
use App\Models\Session;
use App\Services\ScheduleSessionsService;
use App\Services\SessionService;
use App\Services\StudentAvailableService;
use Carbon\Carbon;
use Illuminate\Http\Request;


//todo: validate that the student is available ✅
//todo: validate that don't exist a session in the same date ✅
//todo: the session must be created by the user
//todo: the session is unique or repeated daily for maximum 15 minutes
//todo: send a notification to the student by email
//todo: the user can assign target students to the final session

class SessionsController extends Controller
{

    public function __construct(
        private StudentAvailableService $studentAvailableService,
        private ScheduleSessionsService $scheduleSessionsService,
        private SessionService $sessionService
    )
    {}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = $this->sessionService->getSessions();
        return response()->json(["data" => $sessions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SessionRequest $request)
    {
        $session = $request->all();

        $session_start = $request->session_start;
        $duration = $request->duration;
        $time = strtotime($request->time);

        $date_start = new Carbon($session_start);
        $date_start->setTime(date('H', $time), date('i', $time));
        $date_end = (clone $date_start)->add($duration, 'minutes');


        // pass days monday to sunday
        $isAvailable = $this->studentAvailableService->isAvailable(
            $request->student_id,
            strtolower($date_start->format('l')),
            strtolower($date_end->format('l'))
        );

        if (!$isAvailable) {
            return response()->json(["message" => "The student is not available"], 400);
        }

        $existSessionDoesEqualDate = $this->scheduleSessionsService->existSessionDoesEqualDate(
            $date_start->format('Y-m-d H:i:s'),
            $date_end->format('Y-m-d H:i:s')
        );

        if($existSessionDoesEqualDate) return response()->json(["message" => "A session is already scheduled for that date."], 400);


        $user = auth()->user();

        $session = $this->sessionService->create($request->student_id, $user->id);

        if(!$session) {
            return response()->json(["message" => "The session could not be created."], 400);
        }


        $scheduleSession = $this->scheduleSessionsService->create(
            $session, $date_start, $date_end, $duration, $request->is_recurring
        );

        if(!$scheduleSession) {
            return response()->json(["message" => "The session could not be created."], 400);
        }

        $this->scheduleSessionsService->sendNotification($scheduleSession);

        return response()->json(["message" => "The session was created successfully."], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $session_id)
    {
        $this->scheduleSessionsService->assignTargetStudents($session_id, $request->target);

        return response()->json([],200);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(ScheduleSessions $scheduleSessions)
    // {
    //     //
    // }
}

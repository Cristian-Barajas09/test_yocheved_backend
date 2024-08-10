<?php
namespace App\Services;
use App\Models\StudentDisponibility;
class StudentAvailableService {
    public function create($student_id, $day) {
        $resultTrash = StudentDisponibility::onlyTrashed()->where("student_id", $student_id)->where("day",$day)->first();

        if ($resultTrash) {
            $resultTrash->restore();
            return $resultTrash;
        }

        $disponibility = new StudentDisponibility();
        $disponibility->student_id = $student_id;
        $disponibility->day = $day;
        $disponibility->save();

        return $disponibility;
    }

    public function delete($student_id, $day) {
        $disponibility = StudentDisponibility::where("student_id", $student_id)->where("day", $day)->first();

        if ($disponibility) {
            $disponibility->delete();
        }

        return $disponibility;
    }

    public function isAvailable($student_id, $start, $end) {

        $disponibility = StudentDisponibility::where("student_id", $student_id)
            ->where("day", $start)
            ->orWhere("day", $end)
            ->first();


        return $disponibility ? false : true;
    }
}

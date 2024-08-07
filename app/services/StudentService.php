<?php

namespace App\Services;

use App\Models\Student;

class StudentService {

    public function __construct() {}

    public function all() {
        $students = Student::all();
        return $students;
    }

    public function create(
        string $name,
        string $middleName,
        string $lastName,
        string $birthDate
    ) {
        $student = new Student();
        $student->name = $name;
        $student->middle_name = $middleName;
        $student->last_name = $lastName;
        $student->birth_date = $birthDate;
        $student->save();
        return $student;
    }


    public function find(int $studentId) {
        $student = Student::findOrFail($studentId);
        return $student;
    }

}

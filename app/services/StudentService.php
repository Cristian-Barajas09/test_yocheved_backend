<?php

namespace App\Services;

use App\Lib\Msdocx\Cursor;
use App\Models\Student;

class StudentService
{

    public function __construct(
        private MSDocxFilesService $msDocxFilesService
    ) {}

    public function all()
    {
        $students = Student::all();
        return $students;
    }

    public function create(
        string $name,
        string $middleName,
        string $lastName,
        string $birthDate,
        string $email
    ) {
        $student = new Student();
        $student->name = $name;
        $student->middle_name = $middleName;
        $student->last_name = $lastName;
        $student->birth_date = $birthDate;
        $student->email = $email;
        $student->save();
        return $student;
    }


    public function find($studentId)
    {
        $student = Student::findOrFail($studentId);
        return $student;
    }

    public function saveSessionInformationFromDocx(Student $student, $file)
    {



        $docxContent = $this->msDocxFilesService->readDocxFile($file);

        $matches = $this->getMatches($docxContent);

        dd($matches);

        return $student;
    }

    private function getMatches($docxContent)
        {
            $cursor = new Cursor();
            $cursor->setContent($docxContent);

            $tables = $cursor->getTables();

            $result = [];

            foreach ($tables as $table) {
                $cursor->setContent($table);
                $rows = $cursor->getRows();

                $isHeader = true;

                for ($i = 1; $i < count($rows); $i++) {
                    $cursor->setContent($rows[$i]);

                    $cells = $cursor->getCells();
                    $resultByCell = [];

                    foreach ($cells as $cellIndex => $cell) {
                        if (strpos(strtolower($cell['tableCell']), "start") !== false || strpos(strtolower($cell['tableCell']), "date")  !== false) {
                            $value = $rows[$i + 1][$cellIndex]["tableCell"];

                            if(strpos(strtolower($value), "to") !== false) {
                                $date = explode('to',$value);

                                $resultByCell['start date'] = $date[0] ;
                                $resultByCell['end date'] = $date[1];
                            } else {
                                $resultByCell[$cell['tableCell']] = $value;
                            }
                        }

                        if (strpos(strtolower($cell['tableCell']), "end")  !== false) {
                            $resultByCell[$cell['tableCell']] = $rows[$i + 1][$cellIndex]["tableCell"];
                        }

                        if (strpos(strtolower($cell['tableCell']), "target") !== false) {
                            $resultByCell[$cell['tableCell']] = $rows[$i + 1][$cellIndex]["tableCell"];
                        }
                    }

                    if (!empty($resultByCell)) {
                        $result[] = $resultByCell;
                    }
                }
            }

            return $result;
        }

}

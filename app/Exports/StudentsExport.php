<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $courseId, $semesterId, $lecturerId;

    public function __construct($courseId, $semesterId, $lecturerId)
    {
        $this->courseId = $courseId;
        $this->semesterId = $semesterId;
        $this->lecturerId = $lecturerId;
    }

    public function collection()
    {
        return collect(DB::select("
            SELECT 
                b.id as student_id, 
                b.mat_number, 
                b.firstname, 
                b.middlename, 
                b.lastname, 
                c.course_name 
            FROM student_registered_courses a 
            JOIN students b ON a.student_id = b.id 
            JOIN courses c ON a.course_id = c.id 
            WHERE a.course_id = ? AND a.semester_id = ? AND a.lecturer_id = ?",
            [$this->courseId, $this->semesterId, $this->lecturerId]
        ));
    }

    public function headings(): array
    {
        $id = trim('student_id');
        $mat = trim('Mat Number');
        $student = trim('Student');
        $course = trim('Course');
        $test = trim('test');
        $ass = trim('assignment');
        $exam = trim('exam');

        return [$id, $mat, $student, $course, $test, $ass, $exam];
    }

    public function map($row): array
    {
        return [
            $row->student_id,
            $row->mat_number,
            trim("{$row->firstname} {$row->middlename} {$row->lastname}"), // Handles cases where middlename might be empty
            $row->course_name,
            "", // Empty placeholder for Test (25%)
            "", // Empty placeholder for Assignment (25%)
            ""  // Empty placeholder for Exam (50%)
        ];
    }

}

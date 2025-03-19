<?php

namespace App\Imports;

use App\Models\GradingSystem;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Student; // Assuming you have a Student model
use App\Models\Course; // Assuming you have a Course model
use App\Models\StudentRegisteredCourse;
use Exception;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel,WithHeadingRow
{
    protected $courseId;
    protected $gradeType;

    protected $lecturerId;

    protected $semesterId;

    public function __construct($courseId, $gradeType, $lecturerid, $semesterid)
    {
        $this->courseId = $courseId;
        $this->gradeType = $gradeType;
        $this->lecturerId = $lecturerid;
        $this->semesterId = $semesterid;
    }

    public function model(array $row)
    {
        // Get the test, assignment, and exam marks
       
        $test = $row['test'];
        $assignment = $row['assignment'];
        $exam = $row['exam'];

        // Validate the marks
        if ($test < 0 || $test > 25 || $assignment < 0 || $assignment > 25 || $exam < 0 || $exam > 100) {
            // Throw a normal exception if the marks are invalid
            throw new Exception("Marks for student {$row['Student']} are invalid. Test: {$test}, Assignment: {$assignment}, Exam: {$exam}");
        }

        // Calculate the total
        $total = $test + $assignment + $exam;
        
        // Fetch the grading system based on the total marks
        $gradingSystem = GradingSystem::where('mark_from', '<=', $total)
            ->where('mark_to', '>=', $total)
            ->where('grade_type', $this->gradeType)
            ->first();  // Use first() to get the first matching grading system

        if ($gradingSystem) {
            // If a grading system is found, proceed to insert the record
            StudentRegisteredCourse::where([
                'student_id' => $row['student_id'],  // The condition for uniqueness (e.g., check by student_id, course_id)
                'course_id' => $this->courseId,
                'semester_id' => $this->semesterId,
                'lecturer_id' => $this->lecturerId,
            ])->update(
                    [
                        'test_mark' => $assignment + $test,
                        'test'=>$test,
                        'assignment' => $assignment,
                        'exam_mark' => $exam,
                        'grade_point' => $gradingSystem->grade_point, // Assuming grade_point is a column
                        'letter_grade' => $gradingSystem->grade, // Assuming grade is a column
                        'total_mark' => $total,
                    ]
                );

        } else {
            // If no grading system is found, throw an exception
            throw new Exception("No matching grading system found for student {$row['Student']}. Total marks: {$total}");
        }
    }

}


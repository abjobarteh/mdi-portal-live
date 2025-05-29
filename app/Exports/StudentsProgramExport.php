<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class StudentsProgramExport implements FromView
{
    protected $programId;

    public function __construct($programId)
    {
        $this->programId = $programId;
    }

    public function view(): View
    {
        $students = DB::select("select a.mat_number,concat(a.firstname,' ',IFNULL(a.middlename,''),' ',a.lastname) as Name,b.name as Program,c.name as Department,a.semester_name as Semester from students a join programs b on (a.program_id=b.id) join departments c on (b.department_id=c.id) where a.accepted ='accepted' and b.id = ? order by a.semester_name", [$this->programId]);

        return view('exports.studentprogram', [
            'students' => $students
        ]);
    }
}

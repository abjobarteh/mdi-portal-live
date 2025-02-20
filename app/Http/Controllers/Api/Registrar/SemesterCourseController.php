<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\SemesterCourse;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\TeachableCourse;
class SemesterCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lecturerId)
    {
        $teachableAvailableSemesterCourses = SemesterCourse::whereHas('semester', function ($query) {
            $query->where('is_current_semester', 1);
        })
            ->whereIn('course_id', function ($query) use ($lecturerId) {
                $query->select('teachable_course_id')
                    ->from('lecturer_teachable_course')
                    ->where('lecturer_id', $lecturerId);
            })
            ->with('course')
            ->paginate(1000);

        return response()->json([
            'status' => 200,
            'result' => $teachableAvailableSemesterCourses
        ]);

        // $lecturerCourses = SemesterCourse::whereHas('semester', function ($query) {
        //     $query->where('is_current_semester', 1);
        // })
        //     ->whereNotNull('lecturer_id')
        //     ->with('course')
        //     ->paginate(13);

        // return response()->json([
        //     'status' => 200,
        //     'result' => $lecturerCourses
        // ]);
    }


    public function deallocateLecturerCourses(Request $request)
    {
        $validatedData = $request->validate([
            'courseId' => 'required',
        ]);

        $currentSemsterId = Semester::where('is_current_semester', 1)->value('id');
        SemesterCourse::where('course_id', $validatedData['courseId'])->where('semester_id', $currentSemsterId)
            ->update(['lecturer_id' => null]);

        return response()->json(['message' => 'Courses allocated successfully.']);
    }
    public function addlectcourse(Request $request){
        
        $lecturerId = $request->input('lecturer_id');
        $courseIds = $request->input('courseids');
        
        $lecturer = Lecturer::find($lecturerId);
    
        if (!$lecturer) {
            return response()->json(['status' => 'error', 'message' => 'Lecturer not found',  'lecturer_id' => $lecturerId,'course_ids' => $courseIds], 404);
        }
    
      
        // Attach courses to the lecturer
       // TeachableCourse
       foreach ($courseIds as $courseId) {
        TeachableCourse::updateOrCreate(
            [
                'lecturer_id' => $lecturerId, // Condition to match existing records
                'teachable_course_id' => $courseId
            ],
            [
                'lecturer_id' => $lecturerId, // Data to insert/update
                'teachable_course_id' => $courseId
            ]
        );
    
        SemesterCourse::where('course_id', $courseId)
            ->update(['lecturer_id' => $lecturerId]); // Use `where` instead of `whereIn`
    }
    
    
    
    
       // $lecturer->teachables()->sync($courseIds); // Use sync instead of attach if you want to replace existing records
    
        return response()->json(['status' => 'success', 'message' => 'Courses allocated successfully','lecturer_id' => $lecturerId,'course_ids' => $courseIds]);
    }
    public function removelectcourse(Request $request){
        
        $lecturerId = $request->input('lecturer_id');
        $courseIds = $request->input('courseids');
    
        $lecturer = Lecturer::find($lecturerId);
    
        if (!$lecturer) {
            return response()->json(['status' => 'error', 'message' => 'Lecturer not found',  'lecturer_id' => $lecturerId,'course_ids' => $courseIds], 404);
        }
    
        // Attach courses to the lecturer
        $lecturer->teachables()->detach($courseIds); // Use sync instead of attach if you want to replace existing records
    
        return response()->json(['status' => 'success', 'message' => 'Courses removed successfully','lecturer_id' => $lecturerId,'course_ids' => $courseIds]);
    }
    public function getcourses($lecturerId)
    {

        $lecturer = Lecturer::find($lecturerId);
        if ($lecturer) {
            $departmentCourses = Department::with('courses')
                ->where('id', $lecturer->department_id)
                ->paginate(13);
        
            return response()->json([
                'status' => 200,
                'lecturer_id' => $lecturerId,
                'result' => $departmentCourses
            ]);
        } 


        // $lecturerCourses = SemesterCourse::whereHas('semester', function ($query) {
        //     $query->where('is_current_semester', 1);
        // })
        //     ->whereNotNull('lecturer_id')
        //     ->with('course')
        //     ->paginate(13);

        // return response()->json([
        //     'status' => 200,
        //     'result' => $lecturerCourses
        // ]);
    }
    // i have to update the lecturer_id to be the comming lecturer_id where the course_id
    // is the courses_id coming
    public function allocateSemesterCourses(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'lecturer_id' => 'required',
                'semester_courses_ids' => 'required',
            ]);

            SemesterCourse::whereIn('course_id', $validatedData['semester_courses_ids'])
                ->update(['lecturer_id' => $validatedData['lecturer_id']]);

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => auth()->user()])
                ->log(auth()->user()->firstname . '  has allocated a course');

            return response()->json(['message' => 'Courses allocated successfully.']);
        } catch (\Exception $e) {
            // Handle the exception here
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

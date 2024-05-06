<?php

namespace App\Http\Controllers\Api\Hod;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Hod;
use App\Models\Lecturer;
use App\Models\Semester;
use App\Models\SemesterCourse;
use App\Models\Student;
use App\Models\StudentRegisteredCourse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class HodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = auth()->user()->id;
        $departmentId = Hod::where('user_id', $userId)->value('department_id');
        $query = Course::whereHas('program.department', function ($query) use ($departmentId) {
            $query->where('id', $departmentId);
        })->with(['program.department']);

        if ($request->has('selectedItem') && $request->has('advanceSearch')) {
            $selectedItem = $request->input('selectedItem');
            $advanceSearch = $request->input('advanceSearch');

            switch ($selectedItem) {
                case 1:
                    $query->where('course_name', 'like', '%' . $advanceSearch . '%');
                    break;
                case 2:
                    $query->where('course_code', 'like', '%' . $advanceSearch . '%');
                    break;

                default:
                    break;
            }
        }
        $courses = $query->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $courses
        ]);
    }


    public function coursesToApproveByHod()
    {
        $userId = auth()->user()->id;
        $departmentId = Hod::where('user_id', $userId)->value('department_id');

        $currentSemesterId = Semester::where('is_current_semester', 1)->value('id');
        // i have to add approved later
        $activeSemesterCourses = SemesterCourse::whereHas('course.program.department', function ($query) use ($departmentId) {
            $query->where('id', $departmentId);
        })->where('semester_id', $currentSemesterId)->where('submitted', 1)->with('course')->paginate(13);
        foreach ($activeSemesterCourses as $activeSemesterCourse) {
            $activeSemesterCourse['marks'] = StudentRegisteredCourse::with('student')->where('course_id', $activeSemesterCourse['course_id'])->get();
        }

        return response()->json([
            'status' => 200,
            'result' => $activeSemesterCourses
        ]);
    }

    public function approveCoursesByHod(Request $request)
    {
        SemesterCourse::where('course_id', $request->get('course_id'))
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->update(['approved' =>  1]);

        StudentRegisteredCourse::where('course_id', $request->get('course_id'))
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->update(['approved' => 1]);
    }

    public function hodLecturers()
    {
        $userId = auth()->user()->id;
        $departmentId = Hod::where('user_id', $userId)->value('department_id');
        $lecturers = Lecturer::where('department_id', $departmentId)->with('semesterCourses')->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $lecturers
        ]);
    }
    public function hodStudents(Request $request)
    {
        $userId = auth()->user()->id;
        $departmentId = Hod::where('user_id', $userId)->value('department_id');

        $query = Student::where('department_id', $departmentId)->with('payments.semester', 'department')->where('accepted', 'accepted');

        if ($request->has('sponsored')) {
            $query->where('is_sponsored', 1);
        } else if ($request->has('notsponsored')) {
            $query->where('is_sponsored', 0);
        }

        if ($request->has('selectedItem') && $request->has('advanceSearch')) {

            $selectedItem = $request->input('selectedItem');
            $advanceSearch = $request->input('advanceSearch');

            switch ($selectedItem) {
                case 1:
                    $query->where('mat_number', 'like', '%' . $advanceSearch . '%');
                    break;
                case 2:
                    $query->where('email', 'like', '%' . $advanceSearch . '%');
                    break;

                default:
                    break;
            }
        }
        $courses = $query->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $courses
        ]);
    }

    public function approveCoursesHod(Request $request)
    {
        SemesterCourse::where('course_id', $request->get('course_id'))
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->update(['approved' =>  1]);

        StudentRegisteredCourse::where('course_id', $request->get('course_id'))
            ->where('semester_id', Semester::where('is_current_semester', 1)->value('id'))
            ->update(['approved' => 1]);
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
    // public function update(Request $request, $id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }

    //     $validatedData = $request->validate([
    //         'firstname' => 'string|max:255',
    //         'lastname' => 'string|max:255',
    //         'username' => 'string|max:255',
    //         'email' => 'string|email|max:255|unique:users,email,' . $id,
    //     ]);

    //     $user->firstname = $validatedData['firstname'] ?? $user->firstname;
    //     $user->lastname = $validatedData['lastname'] ?? $user->lastname;
    //     $user->username = $validatedData['username'] ?? $user->username;
    //     $user->email = $validatedData['email'] ?? $user->email;
    //     $user->save();

    //     return response()->json(['message' => 'User updated', 'user' => $user]);
    // }

    public function updatePassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'oldPassword' => 'string|min:8',
            'newPassword' => 'string|min:8',
        ]);

        // return $validatedData['oldPassword'] . ' ' . Hash::make($validatedData['oldPassword']) . " " . $user->password;
        if (!Hash::check($validatedData['oldPassword'], $user->password)) {
            return response()->json(['message' => 'wrong old password'], 422);
        } else {
            $user->password = Hash::make($validatedData['oldPassword']);
            $user->save();
            return response()->json(['message' => 'User password updated'], 201);
        }
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'string|min:7',
            'confirmPassword' => 'string|min:7',
        ]);

        User::where('id', auth()->user()->id)->update([
            'password' => Hash::make($validatedData['password']),
            'password_reset' => 1,
        ]);
        return response()->json(['message' => 'User password updated'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = User::find($id);
        if ($user->id == auth()->user()->id) {
            return response()->json(['error' => 'You cannot delete yourself'], 422);
        }
        $user->delete();


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has deleted a user');
    }
}

<?php

namespace App\Http\Controllers\Api\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\Semester;
use App\Models\SemesterCourse;
use App\Models\SemesterCourseFile;
use App\Models\StudentRegisteredCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MyCoursesController extends Controller
{
    public function courses(Request $request)
    {
        $currrentSemesterId = Semester::where('is_current_semester', 1)->value('id');
        $myCurrentCourses = SemesterCourse::with('course')->where('semester_id', $currrentSemesterId)
            ->where('lecturer_id', Lecturer::where('user_id', auth()->user()->id)->value('id'))->paginate(10);
        return response()->json([
            'status' => 200,
            'result' => $myCurrentCourses
        ]);
    }

    public function uploadLecturerFiles(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx', // Adjust the allowed file types as needed
            'file_title' => 'required'
        ]);

        // Store the uploaded file
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension(); // Get the file extension
        $fileName = time() . '_' . uniqid() . '.' . $extension; // Append timestamp and unique ID to the file name
        $filePath = $file->storeAs('public/lecturer-files', $fileName);

        // You may need to adjust the field name according to your table structure
        SemesterCourseFile::create([
            'semester_course_id' => $request->semester_course_id,
            'file_title' => $request->file_title,
            'file_name' => $fileName
        ]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has uploaded course materials');

        // Return a response as needed (e.g., success message)
        return response()->json(['message' => 'File uploaded successfully']);
    }

    public function index(Request $request)
    {
        $files = SemesterCourseFile::where('semester_course_id', $request->get('semester_course_id'))->paginate(13);


        // Format the created_at field in the desired format
        foreach ($files as $file) {
            $file['uploaded_date'] = Carbon::parse($file->created_at)->format('jS F Y');
        }

        return response()->json([
            'status' => 200,
            'result' => $files,
        ]);
    }
}

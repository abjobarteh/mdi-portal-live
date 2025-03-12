<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApplicantCertificate;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentPrograms;
use App\Models\StudentRegisteredCourse;
use Illuminate\Validation\ValidationException;
class NewProgramController extends Controller
{
    //

    public function store(Request $request)
    {

        try {

            $validatedData = $request->validate([
                'certificates.*.certificateName' => 'required|string|max:255',
                'certificates.*.certificate' => 'required|file|max:10240|mimes:pdf',
                'semester_name' => 'required',
                'program_id' => 'required'
            ]);

            $student = Student::where('user_id', auth()->user()->id)->first();

            if (StudentPrograms::where('student_id', $student->id)->where('program_id', $request->get('program_id'))->exists()) {
                return response()->json([
                    'success' => false,
                    'errors' => 'You Have Already Applied For This Program',
                ], 422);
            }
            // Delete existing certificates for the user
            //  ApplicantCertificate::where('user_id', auth()->user()->id)->delete();

            // Loop through all the certificates in the request
            foreach ($request->certificates as $certificate) {
                // Get the certificate name and file
                $certificateName = $certificate['certificateName'];
                $certificateFile = $certificate['certificate'];

                // Generate a unique filename
                $originalFilename = $certificateFile->getClientOriginalName();
                $sanitizedFilename = time() . '_' . trim($originalFilename);

                // Save the certificate file
                $certificateFile->move(public_path('certificates'), $sanitizedFilename);

                // Create a new Certificate model and save it to the database
                $certificateModel = new ApplicantCertificate();
                $certificateModel->user_id = auth()->user()->id;
                $certificateModel->certificate_name = $certificateName;
                $certificateModel->certificate = $sanitizedFilename;
                $certificateModel->save();
            }

            Student::where('user_id', auth()->user()->id)->update([
                'is_applicant' => 1,
                'application_completed' => 1,
                'apply_new_course' => 1,
                'accepted' => 'pending',
                'program_id' => $request->get('program_id'),
                'department_id' => Program::where('id', $request->get('program_id'))->value('department_id'),
                'semester_name' => $request->get('semester_name')
            ]);


            StudentPrograms::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'program_id' => $request->get('program_id'),
                     // Condition to check existing record
                ],
                [
                    'program_id' => $request->get('program_id'),
                    'semester_name' => $request->get('semester_name'),
                ]
            );

            return response()->json(['success' => true]);

        } catch (ValidationException $e) {
            // Return validation error messages
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        }
    }
}

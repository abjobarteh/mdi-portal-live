<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\ApplicantCertificate;
use App\Models\ApplicantEducation;
use Illuminate\Http\Request;

class ApplicantCertificateController extends Controller
{

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'certificates.*.certificateName' => 'required|string|max:255',
            'certificates.*.certificate' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png',
        ]);

        // Loop through all the certificates in the request
        foreach ($request->certificates as $certificate) {
            // Get the certificate name and file
            $certificateName = $certificate['certificateName'];
            $certificateFile = $certificate['certificate'];

            // Generate a unique filename
            $filename = time() . '_' . $certificateFile->getClientOriginalName();

            // Save the certificate file
            $certificateFile->move(public_path('certificates'), $filename);

            // Create a new Certificate model and save it to the database
            $certificateModel = new ApplicantCertificate();
            $certificateModel->user_id = auth()->user()->id;
            $certificateModel->certificate_name = $certificateName;
            $certificateModel->certificate = $filename;
            $certificateModel->save();
        }

        return response()->json(['success' => true]);
    }
}

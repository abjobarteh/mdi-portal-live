<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\AdmissionCodeMail;
use Exception;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;

use Illuminate\Support\Facades\Mail;


class EmailController extends Controller
{
    // public function sendEmail(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'admission_code' => 'required|max:255',
    //         'email' => 'required|email',
    //     ]);

    //     try {
    //         Mail::to($validatedData['email'])->send(new AdmissionCodeMail($validatedData['admission_code']));
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Email sending failed'], 422);
    //     }

    //     return response()->json(['message' => 'Email sent successfully']);
    // }

    public function sendEmail(Request $request)
    {
        $validatedData = $request->validate([
            'admission_code' => 'required|max:255',
            'email' => 'required|email',
        ]);

        $validator = new EmailValidator();
        if (!$validator->isValid($validatedData['email'], new DNSCheckValidation())) {
            return response()->json(['error' => 'Invalid email address'], 422);
        }
        try {
            Mail::to($validatedData['email'])->send(new AdmissionCodeMail($validatedData['admission_code']));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Email sending failed'], 422);
        }

        return response()->json(['message' => 'Email sent successfully']);
    }
}

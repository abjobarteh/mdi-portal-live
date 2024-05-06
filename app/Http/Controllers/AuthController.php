<?php

namespace App\Http\Controllers;

use App\Mail\VerificationMail;
use App\Models\RegistrationVerificationToken;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;


use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',     // the email can either be 'email or username here'
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['errors' => "sorry invalid credentials"], 422);
        } else if (!$user->is_active) {
            return response()->json(['errors' => "sorry you are blocked"], 422);
        }

        // Log the successful login with the user's name
        activity()
            ->causedBy($user)
            ->withProperties(['attributes' => $user])
            ->log($user->firstname . '  logged in');

        return $user->createToken($request->device_name)->plainTextToken;
    }

    // public function register(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'firstname' => 'required|max:255',
    //         'lastname' => 'required|max:255',
    //         'username' => 'required|unique:users,username',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|max:255',
    //     ]);

    //     // $validator = new EmailValidator();
    //     // if (!$validator->isValid($validatedData['email'], new DNSCheckValidation())) {
    //     //     return response()->json(['error' => 'Invalid email address'], 422);
    //     // } else {
    //     DB::beginTransaction();

    //     try {
    //         $user = User::create([
    //             'firstname' => $validatedData['firstname'],
    //             'lastname' => $validatedData['lastname'],
    //             'username' => $validatedData['username'],
    //             'email' => $validatedData['email'],
    //             'password' => Hash::make($validatedData['password']),
    //             'role_id' => 4,
    //             'is_active' => 1,
    //         ]);

    //         $student = Student::create([
    //             'firstname' => $user->firstname,
    //             'lastname' => $user->lastname,
    //             'username' => $user->username,
    //             'email' => $user->email,
    //             'user_id' => $user->id,
    //             'is_applicant' => 1,
    //             'application_completed' => 0,
    //             'accepted' => 'pending'
    //         ]);

    //         // Generate a random 5-digit token
    //         $token = mt_rand(10000, 99999);

    //         // Hash the token
    //         $hashedToken = Hash::make($token);

    //         // Update user record with the hashed token
    //         $user->update(['registration_token' => $hashedToken]);

    //         RegistrationVerificationToken::create(['user_id' => $user->id]);

    //         // Send the verification email
    //         // Mail::to($user->email)->send(new VerificationMail($token));
    //         Mail::to($user->email)->send(new VerificationMail($token));


    //         DB::commit();

    //         return response()->json(['message' => 'User created successfully.']);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         // return $e->getMessage();
    //         return response()->json(['message' => 'Error creating user.' . ' ' . $e->getMessage()], 500);
    //     }
    //     // }



    //     // return $user->createToken($request->device_name)->plainTextToken;
    // }

    public function register(Request $request)
    {
        // $validatedData = $request->validate([
        //     'firstname' => 'required|max:255',
        //     'lastname' => 'required|max:255',
        //     'username' => 'required|unique:users,username',
        //     'email' => 'required|email|unique:users,email',
        //     'password' => 'required|max:255',
        // ]);
        $validatedData = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'username' => [
                'required',
                Rule::unique('users', 'username'), // Unique in 'users' table
                Rule::unique('students', 'username'), // Unique in 'students' table
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'), // Unique in 'users' table
                Rule::unique('students', 'email'), // Unique in 'students' table
            ],
            'password' => 'required|max:255',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'firstname' => $validatedData['firstname'],
                'lastname' => $validatedData['lastname'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => 4,
                'is_active' => 1,
            ]);

            $student = Student::create([
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'username' => $user->username,
                'email' => $user->email,
                'user_id' => $user->id,
                'is_applicant' => 1,
                'application_completed' => 0,
                'accepted' => 'pending',
            ]);

            // Generate a random 5-digit token
            $token = mt_rand(10000, 99999);

            // Hash the token
            $hashedToken = Hash::make($token);

            // Update user record with the hashed token
            $user->update(['registration_token' => $hashedToken]);

            // Send the token to the user via email
            // Modify this part based on your email sending logic
            // Mail::to($user->email)->send(new YourCustomMailClass($token));

            RegistrationVerificationToken::create(['user_id' => $user->id]);
            Mail::to($user->email)->send(new VerificationMail($token));


            DB::commit();

            return response()->json(['message' => 'User created successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error creating user.' . ' ' . $e->getMessage()], 500);
        }
    }


    public function verifyRegistrationToken(Request $request)
    {
        $validatedData = $request->validate([
            'registration_verification_token' => 'required|max:255',
        ]);
        // Assuming $validatedData contains the plain token
        $plainToken = $validatedData['registration_verification_token'];

        // Retrieve the hashed token from the user record
        $hashedToken = Auth::user()->registration_token;

        // Compare the plain token with the hashed token

        if (Hash::check($plainToken, $hashedToken)) {
            // Tokens match
            // Perform the desired action
            RegistrationVerificationToken::where('user_id', auth()->user()->id)->update([
                'student_email_verified_at' => Carbon::now(),
            ]);
        } else {
            return response()->json(['message' => 'Wrong Verification code.'], 422);
            // Tokens don't match
            // Handle the error
        }
        // AdmissionCodeVerification::create(['user_id' => auth()->user()->id, 'verified_at' => Carbon::now()]);

    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  logged out');
        return [
            'message' => 'Logged out'
        ];
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not found in our records'], 404);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent successfully'])
            : response()->json(['error' => 'Unable to send reset link'], 500);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => bcrypt($password)])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'])
            : response()->json(['error' => 'Unable to reset password'], 500);
    }
}

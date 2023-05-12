<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Egulias\EmailValidator\EmailValidator;


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

        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        $user = User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => 4,
            'is_active' => 1,
        ]);

        $student = Student::create([
            'username' => $user->username,
            'email' => $user->email,
            'user_id' => $user->id,
            'is_applicant' => 1,
            'application_completed' => 0,
            'accepted' => 'pending'
        ]);

        return response()->json(['message' => 'User created successfully.']);

        // return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}

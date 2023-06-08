<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Egulias\EmailValidator\EmailValidator;
use Illuminate\Support\Facades\DB;

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
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
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
                'accepted' => 'pending'
            ]);

            DB::commit();

            return response()->json(['message' => 'User created successfully.']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Error creating user.'], 500);
        }

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

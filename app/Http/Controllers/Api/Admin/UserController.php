<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('role')->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $users
        ]);
    }

    public function blockUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->is_active = 0;
        $user->save();

        return response()->json(['message' => 'User blocked', 'user' => $user]);
    }

    public function unBlockUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->is_active = 1;
        $user->save();

        return response()->json(['message' => 'User unblocked', 'user' => $user]);
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
        $validatedData = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required',
            'middlename' => 'nullable',
            'username' => 'required',
            'email' => 'required',
            'address' => 'required',
            'role_id' => 'required',
            'phonenumber' => 'required',
            'password' => 'required|confirmed|min:6'


        ]);
        User::create([
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname'],
            'middlename' => $validatedData['middlename'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'address' => $validatedData['address'],
            'role_id' => $validatedData['role_id'],
            'phonenumber' => $validatedData['phonenumber'],
            'password' => Hash::make($validatedData['password'])

        ]);

        return response()->json(['message' => 'User created successfully.']);
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
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'firstname' => 'string|max:255',
            'lastname' => 'string|max:255',
            'username' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
        ]);

        $user->firstname = $validatedData['firstname'] ?? $user->firstname;
        $user->lastname = $validatedData['lastname'] ?? $user->lastname;
        $user->username = $validatedData['username'] ?? $user->username;
        $user->email = $validatedData['email'] ?? $user->email;
        $user->save();

        return response()->json(['message' => 'User updated', 'user' => $user]);
    }

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

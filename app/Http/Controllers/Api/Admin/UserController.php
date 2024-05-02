<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


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
        // Format the created_at field in the desired format
        foreach ($users as $user) {
            $user['registered_at'] = Carbon::parse($user->created_at)->format('jS F Y');
        }
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

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has blocked a user');

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

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has unblocked a user');

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
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'address' => 'required',
            'role_id' => 'required',
            'phonenumber' =>   ['required', 'regex:/^[2-7,9]\d{6}$/'],
            'password' => 'required|confirmed|min:6'
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
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

            if ($validatedData['role_id'] == 3) {


                if ($request->has('lecturer_type')) {
                    $lecturer = Lecturer::create([
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'email' => $user->email,
                        'address' => $user->address,
                        'phonenumber' => $user->phonenumber,
                        'username' => $user->username,
                        'user_id' => $user->id,
                        'lecturer_type' => $request->get('lecturer_type'),
                        'department_id' => $request->get('main_department_id')
                    ]);
                } else {
                    $lecturer = Lecturer::create([
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'email' => $user->email,
                        'address' => $user->address,
                        'phonenumber' => $user->phonenumber,
                        'username' => $user->username,
                        'user_id' => $user->id,
                        'department_id' => $request->get('main_department_id')

                    ]);
                }


                $lecturer->teachables()->attach($request->course_ids);
            }
            activity()
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => auth()->user()])
                ->log(auth()->user()->firstname . '  has created a user');
            DB::commit();

            return response()->json(['message' => 'User created successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required',
            'middlename' => 'nullable',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'address' => 'required',
            'role_id' => 'required',
            'phonenumber' => ['required', 'regex:/^[2-7,9]\d{6}$/'],
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->fill([
                'firstname' => $validatedData['firstname'],
                'lastname' => $validatedData['lastname'],
                'middlename' => $validatedData['middlename'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'address' => $validatedData['address'],
                'role_id' => $validatedData['role_id'],
                'phonenumber' => $validatedData['phonenumber'],
            ]);


            $user->save();

            if ($validatedData['role_id'] == 3) {
                $lecturer = Lecturer::where('user_id', $id)->first();

                if (!$lecturer) {
                    $lecturer = new Lecturer([
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'email' => $user->email,
                        'address' => $user->address,
                        'phonenumber' => $user->phonenumber,
                        'username' => $user->username,
                        'user_id' => $user->id,
                    ]);
                }

                if ($request->has('lecturer_type')) {
                    $lecturer->lecturer_type = $request->get('lecturer_type');
                }

                $lecturer->save();

                $lecturer->teachables()->sync($request->course_ids);
            }

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => auth()->user()])
                ->log(auth()->user()->firstname . ' has updated a user');

            DB::commit();

            return response()->json(['message' => 'User updated successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()]);
        }
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

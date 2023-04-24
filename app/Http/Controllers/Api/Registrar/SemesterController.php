<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $semesters = Semester::with('session')->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $semesters
        ]);
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
            'semester_name' => 'required|max:255',
            'is_current_semester' => 'required',
            'session_id' => 'required',
            'next_semester' => 'required',
        ]);

        // Check if the incoming session is set to current
        if ($validatedData['is_current_semester'] == 1) {
            // Set all other sessions to not current
            Semester::where('is_current_semester', 1)->update(['is_current_semester' => 0]);
        }

        Semester::create([
            'semester_name' => $validatedData['semester_name'],
            'is_current_semester' => $validatedData['is_current_semester'],
            'session_id' => $validatedData['session_id'],
            'next_semester' => $validatedData['next_semester'],
        ]);

        return response()->json(['message' => 'Semester created successfully.']);
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
        //
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

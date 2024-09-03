<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramDuration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $programs = Program::with(['department', 'duration'])->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $programs
        ]);
    }

    public function getprograms()
    {
        $programs = Program::with(['department', 'duration'])->paginate(45);
        return response()->json([
            'status' => 200,
            'result' => $programs
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
            'program_name' => 'required|max:255',
            'program_abbreviation' => 'required',
            'fee' => 'required',
            'department_id' => 'required',
            'program_duration_id' => 'required',
            'min_payable_per_semester' => 'required',

        ]);
        Program::create([
            'name' => $validatedData['program_name'],
            'program_abbreviation' => $validatedData['program_abbreviation'],
            'fee' => $validatedData['fee'],
            'department_id' => $validatedData['department_id'],
            'program_duration_id' => $validatedData['program_duration_id'],
            'per_semester_fee' => $validatedData['fee'] / ((ProgramDuration::where('id', $validatedData['program_duration_id'])->value('duration')) * 2),
            'min_payable_per_semester' => $validatedData['min_payable_per_semester'],
        ]);



        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has created a program');

        return response()->json(['message' => 'Program created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Program::find($id);

        return response()->json([
            'status' => 200,
            'result' => $employee
        ]);
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
        $validatedData = $request->validate([
            'program_name' => 'required|max:255',
            'program_abbreviation' => 'required',
            'fee' => 'required',
            'department_id' => 'required',
            'program_duration_id' => 'required',
            'min_payable_per_semester' => 'required',
        ]);

        $program = Program::find($id);
        if (!$program) {
            return response()->json(['message' => 'Program not found.'], 404);
        }

        $program->update([
            'name' => $validatedData['program_name'],
            'program_abbreviation' => $validatedData['program_abbreviation'],
            'fee' => $validatedData['fee'],
            'department_id' => $validatedData['department_id'],
            'program_duration_id' => $validatedData['program_duration_id'],
            'min_payable_per_semester' => $validatedData['min_payable_per_semester'],
        ]);


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has updated the program');

        return response()->json(['message' => 'Program updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has deleted program');
        $department = Program::find($id);
        $department->delete();
    }
}

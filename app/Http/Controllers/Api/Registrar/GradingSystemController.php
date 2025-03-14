<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\GradingSystem;
use Illuminate\Http\Request;

class GradingSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gradings = GradingSystem::paginate(11);
        return response()->json([
            'status' => 200,
            'result' => $gradings
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
            'mark_from' => 'required|max:255',
            'mark_to' => 'required',
            'grade' => 'required',
            'interpretation' => 'required|min:4',
            'grade_type' => 'required',
            'gpa' => 'required'
        ]);

        GradingSystem::create([
            'mark_from' => $validatedData['mark_from'],
            'mark_to' => $validatedData['mark_to'],
            'grade' => $validatedData['grade'],
            'interpretation' => $validatedData['interpretation'],
            'grade_type' => $validatedData['grade_type'],
            'grade_point' => $validatedData['gpa']
        ]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has created a grading system');

        return response()->json(['message' => 'GradingSystem created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = GradingSystem::find($id);

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
            'mark_from' => 'required|max:255',
            'mark_to' => 'required',
            'grade' => 'required',
            'interpretation' => 'required|min:4',
            'grade_type' => 'required',
            'grade_point' => 'required'
        ]);

        $gradingSystem = GradingSystem::find($id);
        if (!$gradingSystem) {
            return response()->json(['message' => 'GradingSystem not found.'], 404);
        }

        $gradingSystem->update([
            'mark_from' => $validatedData['mark_from'],
            'mark_to' => $validatedData['mark_to'],
            'grade' => $validatedData['grade'],
            'interpretation' => $validatedData['interpretation'],
            'grade_type' => $validatedData['grade_type'],
            'grade_point' => $validatedData['grade_point']
        ]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has updated a grading system');

        return response()->json(['message' => 'GradingSystem updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = GradingSystem::find($id);
        $department->delete();
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has deleted a grading system');
    }
}

<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\ProgramDuration;
use Illuminate\Http\Request;

class ProgramDurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programDuration = ProgramDuration::paginate(13);


        return response()->json([
            'status' => 200,
            'result' => $programDuration
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
            'duration' => 'required|max:255',
            'description' => 'required',

        ]);
        ProgramDuration::create([
            'duration' => $validatedData['duration'],
            'description' => $validatedData['description'],


        ]);


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has created the program duration');

        return response()->json(['message' => 'ProgramDuration created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = ProgramDuration::find($id);

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
            'duration' => 'required|max:255',
            'description' => 'required',
        ]);

        $programDuration = ProgramDuration::find($id);
        if (!$programDuration) {
            return response()->json(['message' => 'Program duration not found.'], 404);
        }

        $programDuration->update([
            'duration' => $validatedData['duration'],
            'description' => $validatedData['description'],
        ]);


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has updated the program duration');

        return response()->json(['message' => 'Program duration updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $department = ProgramDuration::find($id);
            $department->delete();

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => auth()->user()])
                ->log(auth()->user()->firstname . '  has deleted program duration');
            return response()->json([
                'message' => 'Record deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1451) {
                // The error is a foreign key constraint violation
                return response()->json([
                    'error' => 'You must delete the program associated with this program duration first.'
                ], 400);
            } else {
                // The error is something else
                return response()->json([
                    'error' => 'Error deleting record: ' . $e->getMessage()
                ], 500);
            }
        }
    }
}

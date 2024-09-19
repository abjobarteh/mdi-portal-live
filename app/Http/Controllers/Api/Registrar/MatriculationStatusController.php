<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Models\MatriculationStatus;
use App\Http\Controllers\Controller;
use App\Models\Student;

use Illuminate\Http\Request;

class MatriculationStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admissionStatus = MatriculationStatus::get();
        return response()->json([
            'status' => 200,
            'result' => $admissionStatus
        ]);
    }

    public function getmat_number($id)
    {
        try {
            // Querying the Student model to get the mat_number
            $mat_number = Student::where('user_id', $id)->get();

            // Return success response
            return response()->json([
                'status' => 200,
                'result' => $mat_number
            ]);
        } catch (\Exception $error) {
            // In case of an error, return an error response
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    public function updatemat_number(Request $request, $id)
    {

        $checkmat = MatriculationStatus::where('matriculation_status', '0')->exists();
        $exist = Student::where('mat_number', $request->get('mat_number'))->exists();
        $checknochange = Student::where('mat_number', $request->get('mat_number'))->where('user_id', $id)->exists();

        if (!$checkmat) {
            if (!$exist) {
                if (!$checknochange) {
                    $mat = Student::where('user_id', $id)
                        ->update(['mat_number' => $request->get('mat_number')]);

                    if ($mat) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Matriculation Number updated successfully',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 500,
                            'message' => 'Failed to update Matriculation Number',
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Matriculation Already Belongs To You!!!'
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Matriculation Already Exists'
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Matriculation is Closed'
            ], 400);
        }
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
    public function updateMatriculationStatus(Request $request)
    {


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has updated the matriculation status');

        $status = $request->get('status');

        if ($status == 'Open') {
            MatriculationStatus::first()->update(['matriculation_status' => 1]);
            // Assuming the 'status' column is boolean (1 for open, 0 for closed)
            // Adjust the column name and value based on your database structure
            return response()->json(['message' => 'Matriculation opened successfully']);
        } elseif ($status == 'Close') {
            MatriculationStatus::first()->update(['matriculation_status' => 0]);
            // Assuming the 'status' column is boolean (1 for open, 0 for closed)
            // Adjust the column name and value based on your database structure
            return response()->json(['message' => 'Matriculation closed successfully']);
        } else {
            return response()->json(['error' => 'Invalid matriculation status'], 400);
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

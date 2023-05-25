<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Models\AdmissionStatus;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdmissionStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admissionStatus = AdmissionStatus::get();
        return response()->json([
            'status' => 200,
            'result' => $admissionStatus
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
    public function updateAdmissionStatus(Request $request)
    {
        $status = $request->get('status');

        if ($status == 'Open') {
            AdmissionStatus::first()->update(['admission_status' => 1]);
            // Assuming the 'status' column is boolean (1 for open, 0 for closed)
            // Adjust the column name and value based on your database structure
            return response()->json(['message' => 'Admission opened successfully']);
        } elseif ($status == 'Close') {
            AdmissionStatus::first()->update(['admission_status' => 0]);
            // Assuming the 'status' column is boolean (1 for open, 0 for closed)
            // Adjust the column name and value based on your database structure
            return response()->json(['message' => 'Admission closed successfully']);
        } else {
            return response()->json(['error' => 'Invalid admission status'], 400);
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

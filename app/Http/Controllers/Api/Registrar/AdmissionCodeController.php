<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmissionCode;
use App\Models\AdmissionCodeVerification;
use Carbon\Carbon;

class AdmissionCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function sellCode($id)
    {
        $admissionCode = AdmissionCode::find($id);
        if (!$admissionCode) {
            return response()->json(['message' => 'Code not found.'], 404);
        }

        $admissionCode->admissionCodeLocation()->update([
            'total_sold' => $admissionCode->admissionCodeLocation->total_sold + 1,
            'total_remains' => $admissionCode->admissionCodeLocation->total_remains - 1
        ]);

        $admissionCode->update([
            'is_sold' => 1,
        ]);

        return response()->json(['message' => 'Code sold successfully.']);
    }

    public function redeemAdmissionCode(Request $request)
    {
        $validatedData = $request->validate([
            'admission_code' => 'required|max:255',
        ]);

        $admissionCodeExist = AdmissionCode::where('admission_code', $validatedData['admission_code'])->first();
        if (!is_null($admissionCodeExist)) {
            if ($admissionCodeExist->expired === 0) {
                $admissionCodeExist->update(['is_sold' => 1, 'expired' => 1]);
                AdmissionCodeVerification::create(['user_id' => auth()->user()->id, 'verified_at' => Carbon::now()]);
                return response()->json(['message' => 'Code Redeemed successfully.'], 200);
            } else {
                return response()->json(['message' => 'Code already taken.'], 409);
            }
        } else {
            return response()->json(['message' => 'Code does not exist.'], 422);
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
    public function store(Request $request)
    {
        //
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

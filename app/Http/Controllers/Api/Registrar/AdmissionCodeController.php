<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmissionCode;
use App\Models\Student;
use App\Models\AdmissionCodeLocation;
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
    public function getottal($id)
    {
        $admissionCode = AdmissionCode::find($id);
        if (!$admissionCode) {
            return response()->json(['message' => 'Code not found.'], 404);
        }

        // Check if the related AdmissionCodeLocation exists
        $admissionCodeLocation = $admissionCode->admissionCodeLocation;
        if (!$admissionCodeLocation) {
            return response()->json(['message' => 'Admission code location not found.'], 404);
        }
        return response()->json([
            'total_sold' => $admissionCodeLocation->total_sold,
            'total_remains' => $admissionCodeLocation->total_remains,
            'total_number' => $admissionCodeLocation->total_number
        ], 200);
    }
    public function sellCode(Request $request, $id)
    {
        $request->input('total_sold');
        $admissionCode = AdmissionCode::find($id);

        if ($request->input('total_remain') == -1) {
            return response()->json(['message' => 'Code Has Been Exhausted'], 404);
        }

        if (!$admissionCode) {
            return response()->json(['message' => 'Code not found.'], 404);
        }

        $admissionCode->admissionCodeLocation()->update([
            'total_sold' => $request->input('total_sold'),
            'total_remains' => $request->input('total_remain')
        ]);

        $admissionCode->update([
            'is_sold' => 1,
        ]);


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has sold an admission code');

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
                // i should also work on the admission code location
               /* AdmissionCodeLocation::where('id', $admissionCodeExist->admission_code_location_id)
                    ->decrement('total_remains');

                AdmissionCodeLocation::where('id', $admissionCodeExist->admission_code_location_id)
                    ->increment('total_sold'); */

                AdmissionCodeVerification::create(['user_id' => auth()->user()->id, 'verified_at' => Carbon::now(),'admission_code'=>$validatedData['admission_code']]);
                
               // Student::where('user_id',auth()->user()->id)->update(['apply_new_course'=>1]);
                
                activity()
                    ->causedBy(auth()->user())
                    ->withProperties(['attributes' => auth()->user()])
                    ->log(auth()->user()->firstname . '  admission code redeemed');

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

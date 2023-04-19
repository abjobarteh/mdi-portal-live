<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCode;
use App\Models\AdmissionCodeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AdmissionCodeLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admissionCodeLocations = AdmissionCodeLocation::with(['admissionCodes'])->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $admissionCodeLocations
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
        try {
            $validatedData = $request->validate([
                'location_name' => 'required|max:255',
                'semester' => 'required|max:255',
                'total_number' => 'required|max:255',
                'price' => 'required|numeric',
            ]);
            $validatedData['total_remains'] = $request->get('total_number');


            // Generate the random strings before inserting the records
            $randomStrings = [];
            for ($count = 0; $count < $request->get('total_number'); $count++) {
                $randomStrings[] = Str::random(10);
            }

            // Start a transaction
            DB::beginTransaction();

            // Create the AdmissionCodeLocation model and save it
            $admissionCodeLocation = AdmissionCodeLocation::create($validatedData);

            // Create AdmissionCode models and set attributes using the fill method
            $admissionCodes = [];
            foreach ($randomStrings as $randomString) {
                $admissionCode = new AdmissionCode([
                    'admission_code' => $randomString,
                    'is_sold' => 0,
                    'price' => $validatedData['price']
                ]);
                $admissionCodes[] = $admissionCode;
            }

            // Associate the AdmissionCodeLocation model with the AdmissionCode models and save them
            $admissionCodeLocation->admissionCodes()->saveMany($admissionCodes);

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json(['message' => 'create successfully']);
        } catch (\Exception $e) {
            // Roll back the transaction
            DB::rollBack();

            // Return an error response
            return response()->json(['message' => $e->getMessage()], 500);
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

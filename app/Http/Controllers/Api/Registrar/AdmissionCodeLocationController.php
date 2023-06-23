<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCode;
use App\Models\AdmissionCodeLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        // try to know if the login user is an agent then, then fetch the admissioncodelocation where the location
        // is my location
        $admissionCodeLocations = null;
        if (auth()->user()->role_id == 6) {

            $admissionCodeLocations = AdmissionCodeLocation::where('user_id', auth()->user()->id)->with(['admissionCodes', 'semester.session'])->paginate(13);
        } else {
            $admissionCodeLocations = AdmissionCodeLocation::with(['admissionCodes', 'semester.session'])->paginate(13);
        }
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
                'semester_id' => 'required|max:255',
                'total_number' => 'required|max:255',
                'price' => 'required|numeric',
                'username' => 'required',
                'email' => 'required',
                'password' => 'required'
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

            // i want to create a user also who will be able to sell the codes
            $user = User::create([
                'firstname' => $request->username,
                'lastname' => $request->username,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => 1,
                'role_id' => 6, // role_id 6 is agents
            ]);

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

    public function addAdmissionCodes(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'total_number' => 'required|max:255',
                'admission_code_location_id' => 'required',
                'price' => 'required',
            ]);

            // Generate the random strings for the new admission codes
            $randomStrings = [];
            for ($count = 0; $count < $request->get('total_number'); $count++) {
                $randomStrings[] = Str::random(10);
            }

            // Retrieve the existing admission codes from the database with the same admission_location_id
            $existingAdmissionCodes = AdmissionCode::where('admission_code_location_id', $validatedData['admission_code_location_id'])->get();

            // Create AdmissionCode models for the new admission codes and append them to the existing admission codes collection
            foreach ($randomStrings as $randomString) {
                $admissionCode = new AdmissionCode([
                    'admission_code' => $randomString,
                    'is_sold' => 0,
                    'price' => $validatedData['price'],
                    'admission_code_location_id' => $validatedData['admission_code_location_id']
                ]);
                $existingAdmissionCodes->push($admissionCode);
            }

            // Save all the admission codes back to the database
            foreach ($existingAdmissionCodes as $admissionCode) {
                $admissionCode->save();
            }

            // then update the total admission codes, and the total remains
            $admissionCodeLocation = AdmissionCodeLocation::find($validatedData['admission_code_location_id']);
            $admissionCodeLocation->increment('total_number', $validatedData['total_number']);
            $admissionCodeLocation->increment('total_remains', $validatedData['total_number']);


            // Return a success response
            return response()->json(['message' => 'Admission codes added successfully']);
        } catch (\Exception $e) {
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
        $admissionCodeLocation = AdmissionCodeLocation::find($id);
        $admissionCodeLocation->delete();
    }
}

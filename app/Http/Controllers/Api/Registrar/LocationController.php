<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Location; // Ensure the Location model is properly imported
use Illuminate\Http\Request;
use App\Models\CourseLocation;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all locations, paginate if necessary
        $location = Location::paginate(100); // or use ->get() if you don't need pagination

        return response()->json([
            'status' => 200,
            'result' => $location
        ]);
    }
public function getlocation(){
    $location = Location::paginate(100); // or use ->get() if you don't need pagination

    return response()->json([
        'status' => 200,
        'result' => $location
    ]);
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // This method can be used to show a form for creating a location, if needed
    }


 public function deallocate(Request $request){

    $day = $request->input('day');
    $course=$request->input('course');
    $sdt = $request->input('start_dt');
    $edt = $request->input('end_dt');
    
    $location = CourseLocation::join('courses as b', 'course_locations.course_id', '=', 'b.id')
    ->join('locations as c', 'course_locations.location_id', '=', 'c.location_id')
    ->select(
        'course_locations.id',
        'course_locations.location_id',
        'course_locations.day',
        'course_locations.start_time',
        'course_locations.end_time',
        'b.course_name',
        'c.location_name'
    )
    ->where('b.course_name', $course)
    ->where('course_locations.start_time', $sdt)
    ->where('course_locations.end_time', $edt)
    ->where('course_locations.day', $day)
    ->first(); // or get() if expecting multiple results


    if ($location) {
        $location->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Location deallocated deleted successfully'
        ]);
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'Location Allocation not found'
        ]);
    }
} 
    public function   getlocations($id){
        $results = CourseLocation::join('courses as b', 'course_locations.course_id', '=', 'b.id')
        ->join('locations as c', 'course_locations.location_id', '=', 'c.location_id')
        ->select(
            'course_locations.id',
            'course_locations.location_id',
            'course_locations.day',
            'course_locations.start_time',
            'course_locations.end_time',
            'b.course_name',
            'c.location_name'
        )
        ->where('course_locations.location_id', $id)
        ->get();
    
        
        return response()->json([
            'status' => 201,
            'result' => $results
        ], 200);
    }
    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'locations_id' => 'required|int',
            'course' => 'required|int',
            'day' => 'required|string|max:255',
            'start' => 'required|string|max:255',
            'end' => 'required|string|max:255',
        ]);


        if($validated['start'] < $validated['end'] ){
            $existsCode = CourseLocation::where('day', $validated['day'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start'], $validated['end']])
                      ->orWhereBetween('end_time', [$validated['start'], $validated['end']])
                      ->orWhere(function ($query) use ($validated) {
                          $query->where('start_time', '<=', $validated['start'])
                                ->where('end_time', '>=', $validated['end']);
                      });
            })
            ->exists();
    
            if ($existsCode) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Location Already Allocated For Day And Time'
                ], 400);
            }
    
            $location = CourseLocation::create([
                'location_id' => $validated['locations_id'],
                'course_id' => $validated['course'],
                'day' => $validated['day'],
                'start_time' => $validated['start'],
                'end_time' => $validated['end'],
            ]);
    
            return response()->json([
                'status' => 201,
                'result' => $location
            ], 200);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Start Time Should Less Than End Time '
            ], 400);
        }
     
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'location_code' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
        ]);

        // Check if the location code already exists
        $existsCode = Location::where('location_code', $validated['location_code'])->exists();

        // Check if the location name already exists
        $existsName = Location::where('location_name', $validated['location_name'])->exists();

        if ($existsCode) {
            return response()->json([
                'status' => 400,
                'message' => 'The location code already exists.'
            ], 400);
        }

        if ($existsName) {
            return response()->json([
                'status' => 400,
                'message' => 'The location name already exists.'
            ], 400);
        }

        // Create a new location if it does not already exist
        $location = Location::create([
            'location_code' => $validated['location_code'],
            'location_name' => $validated['location_name'],
        ]);

        return response()->json([
            'status' => 201,
            'result' => $location
        ], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = Location::find($id);

        if ($location) {
            return response()->json([
                'status' => 200,
                'result' => $location
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Location not found'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // This method can be used to show a form for editing a location, if needed
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
        // Validate incoming request data
        $name = $request->input('location_name');
        $code = $request->input('location_code');

        // Find the location by ID
        $location = Location::where('location_id', $id);


        $existsCode = Location::where('location_code', $code)->exists();

        // Check if the location name already exists
        $existsName = Location::where('location_name', $name)->exists();

      

        if ($location) {
            // Update the location record
            $location->update([
                'location_code' => $code,
                'location_name' => $name
            ]);

            return response()->json([
                'status' => 200,
                'result' => $location
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Location not found'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = Location::where('location_id', $id);

        if ($location) {
            $location->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Location deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Location not found'
            ]);
        }
    }
}

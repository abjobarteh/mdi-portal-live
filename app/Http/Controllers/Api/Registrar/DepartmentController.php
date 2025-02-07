<?php

namespace App\Http\Controllers\Api\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::with('courses')->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $departments
        ]);
    }
    public function getdept()
    {
        $departments = Department::with('courses')->get();

        // Return the response as JSON
        return response()->json([
            'status' => 200,
            'result' => $departments
        ]);
    }
    public function deparmentCourses(Request $request)
    {
        $departmentCourses = Department::with('courses')->where('id', $request->get('department_id'))->paginate(13);
        return response()->json([
            'status' => 200,
            'result' => $departmentCourses
        ]);
    }


    public function getstudentdepartmentcount()
    {


        $departmentCount = DB::select("
        SELECT b.name AS department_name, COUNT(a.id) AS student_count
        FROM students a
        JOIN departments b ON a.department_id = b.id
        WHERE a.accepted = 'accepted'
        GROUP BY b.name
        ORDER BY b.name
    ");

        return response()->json([
            'status' => 200,
            'result' => $departmentCount
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
            'name' => 'required|max:255',
        ]);
        Department::create([
            'name' => $validatedData['name'],
        ]);


        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has created a department');

        return response()->json(['message' => 'Department created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $department = Department::find($id);

        return response()->json([
            'status' => 200,
            'result' => $department
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
            'name' => 'required|max:255',
        ]);

        $department = Department::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        $department->update([
            'name' => $validatedData['name'],
        ]);

        return response()->json(['message' => 'Department updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $department = Department::find($id);
        $department->delete();

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => auth()->user()])
            ->log(auth()->user()->firstname . '  has deleted department');
    }
}

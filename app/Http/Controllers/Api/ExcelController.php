<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\MyImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExcelController extends Controller
{
    public function uploadExcelFile(Request $request)
    {
        $file = $request->file('file');

        Excel::import(new MyImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'Data imported successfully!'
        ]);
    }
}

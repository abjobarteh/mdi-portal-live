<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;

class MyImport implements ToModel
{
    public function model(array $row)
    {
        return new Employee([
            'firstname' => $row[0],
            'lastname' => $row[1],
            'address' => $row[2],
            'telephone' => $row[3],
        ]);
    }
}

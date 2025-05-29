<table>
    <thead>
        <tr>
            <th>Mat Number</th>
            <th>Student Name</th>
            <th>Program</th>
            <th>Department</th>
            <th>Semester</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)
            <tr>
                <td>{{ $student->mat_number ?? 'N/A' }}</td>
                <td>{{ $student->Name ?? 'N/A' }}</td>
                <td>{{ $student->Program ?? 'N/A' }}</td>
                <td>{{ $student->Department ?? 'N/A' }}</td>
                <td>{{ $student->Semester ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

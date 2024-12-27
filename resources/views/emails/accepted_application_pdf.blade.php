<?php

use App\Models\Student;
use App\Models\Session;
use App\Models\Program;


$session = Session::where('is_current_session', '1')->first();
$session_start = $session->session_start;
$session_end = $session->session_end;


$studentsWithPrograms = Student::join('programs as b', 'students.program_id', '=', 'b.id')
    ->where('students.mat_number', $matnumber)
    ->select('b.fee', 'b.name')
    ->get();

// Loop through students (although typically we expect one student per mat_number)
foreach ($studentsWithPrograms as $student) {
    $prname = $student->name;
    $prfee = $student->fee;
}

$programdetails = Program::join('program_durations as b', 'b.id', '=', 'programs.program_duration_id')
    ->join('departments as c', 'c.id', '=', 'programs.department_id')
    ->where('programs.name', $prname)
    ->select('programs.name', 'b.duration', 'b.description', 'c.name as department')
    ->get();

foreach ($programdetails as $programdetail) {
    $department = $programdetail->department;
    $tenure = $programdetail->duration . ' ' . $programdetail->description;
    $duration = $programdetail->duration;
}

if ($department == 'DEPARTMENT OF FINANCIAL MANAGEMENT AND ACCOUNTING' || $department == 'DEPARTMENT OF BUSINESS STUDIES') {
    $otherfee = '+ external and other related fees';
} else {
    $otherfee = '';
}

if ($duration == 6) {
    $semesterfee = $prfee;
} else if ($duration == 1) {
    $semesterfee = $prfee / 2;
} else if ($duration == 2) {
    $semesterfee = $prfee / 4;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDI Acceptance Letter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            /* Ensure text alignment for the image container */
        }

        h1 {
            color: #007BFF;
            text-align: center;
            font-size: 13px;
            text-decoration: underline;
        }

        h2 {
            color: black;
            text-align: center;
            font-size: 15px;
        }

        p {
            margin: 10px 0;
            text-align: left;
            font-size: 12px;
            /* Reset text alignment for paragraphs */
        }

        .signature {
            margin-top: 10px;
            text-align: left;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo-container img {
            width: 170px;
        }

        /* Page break style */
        .page-break {
            page-break-before: always;
        }

        /* Terms and conditions style */
        .terms-conditions {
            font-size: 10px;
            text-align: left;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="images/logos/mdi_logo.png" alt="MDI Logo" />
        </div>
        <h2><strong>REPUBLIC OF THE GAMBIA</strong></h2>
        <h2><strong>MANAGEMENT DEVELOPMENT INSTITUTE</strong></h2>
        <h1><strong>FULL OFFER OF ADMISSION FOR {{$session_start}} - {{$session_end}} SEMESTER </strong></h1>
        <p>Dear {{ $fullname }},</p>
        <p>I am pleased to inform you that your application for admission as a full-time student at the Management Development Institute to undertake a course in the <strong>{{$department}}</strong> is successful.</p>
        <p>The course will last for <strong>{{$tenure}}</strong> leading to <strong>{{$prname}}</strong>.</p>

        <p>Lectures will start on <strong>{{$date}}</strong></p>
        <p>The course fee is <strong>D{{ $prfee }} {{$otherfee}}; other costs/fees include an Online Library fee of D1000.00 per annum.</strong> </p>

        @if ($duration == 6)
        <p><strong>The total fee is D{{ $semesterfee + 1000 }}</strong>, payable before the start of the semester</p>
        @elseif ($duration == 1)
        <p>These fees are payable as follow:</strong></p>
        <p><strong>1st Semester, D{{ $semesterfee + 1000 }}</strong>, payable before the start of the semester</p>
        <p><strong>2nd Semester, D{{ $semesterfee }}</strong>, payable within the first two weeks.</p>
        @elseif ($duration == 2)
        <p>These fees are payable as follow:</strong></p>
        <p><strong>1st and 2nd Semester, D{{ $semesterfee + 1000 }}</strong>, payable before the start of the semester</p>
        <p><strong>3rd and 4th Semester, D{{ $semesterfee }}</strong>, payable within the first two weeks.</p>
        @endif

        <p>Note: Your matriculation number is <strong>{{ $matnumber }}</strong>.</p>

        <p>Please read the attached terms and conditions of admission and inform the office in writing if you wish to decline this offer within five (5) days of receipt of this letter</p>
        <p>MDI, however, reserves the right to fill any slots created by declining this offer.</p>
        <p>Please keep in mind that your status as a student of MDI is contingent on your continued satisfactory academic performance and comportment.</p>
        <p>I wish to extend to you a warm welcome and best wishes on your admission to the Management Development Institute.</p>
        <p class="signature">Sincerely,</p>
        <p>Office of the Registrar</p>
        <div class="signature">
            <img src="E:/mdiportal/resources/js/src/assets/images/logos/mdiesign.png" alt="Registrar Signature" width="50px;" height="50px;" />
        </div>

        <div class="page-break"></div>
        <div class="logo-container">
            <img src="images/logos/mdi_logo.png" alt="MDI Logo" />
        </div>
        <h2><strong>REPUBLIC OF THE GAMBIA</strong></h2>
        <h2><strong>MANAGEMENT DEVELOPMENT INSTITUTE</strong></h2>
        <div class="terms-conditions">
            <h1>TERMS AND CONDITIONS OF ADMISSION</h1>
            <p>
                @if ($duration == 6)
                1. The course fee and the Online Library fee must be paid in full before the commencement of the course. Please note that you will not be accepted in class without fulfilling this requirement. You will be told by your department head as to when and how to make the other fees component (books, registration, exams fees).
                @elseif ($duration == 1)
                1. At least 50% of the course fee and the Online library fee must be paid before the commencement of the course. Please note that you will not be accepted in class without fulfilling this requirement. The balance of <strong>50%</strong> of the course fee for one year must be settled in one instalment, failing which you will <strong>NOT</strong> be allowed in class.
                @elseif ($duration == 2)
                1. At least 25% of the course fee and the Online Library fee must be paid before the commencement of the course. Please note that you will not be accepted in class without fulfilling this requirement. The balance of <strong>75%</strong> of the course fee for two years must be settled in three instalments, failing which you will <strong>NOT</strong> be allowed in class.
                @endif
            </p>

            <p>2.All prospective students are duly informed that MDI has introduced an Electronic Bill Payment System in partnership with <strong>Zenith Bank</strong> and <strong>Agib Bank</strong>.
                Account Name: <strong>Management Development Institute</strong>.
                Henceforth, all course fees must be paid at the afore-mentioned Bank Account. No course fee payment will be accepted by the Institute’s Accounts Office.
            </p>
            <p>3.Students need to note that receipts of payments obtained from Zenith Bank or Agib Bank must be presented <u><strong>first to the MDI Registry</strong></u> and then to the <u><strong>Accounts Office</strong></u> for confirmation of payment.</p>
            <p>4.Please note that fees are non-refundable if you withdrew from the course, or upon the termination of your studentship two weeks after the commencement of the course.</p>
            <p>5. The criteria for certification are as follows:
                • Punctuality and Regular class attendance (minimum 75%)
                • Satisfactory performance and conduct in line with MDI’s Rules and Regulations for students.
                You will not be awarded the desired Diploma if you failed to meet the aforementioned conditions.
            </p>
            <p>6.If there is any evidence of absenteeism in class attendance for one month without prior notice to your department or the Registry, persistent lateness, misconduct, or any type of indiscipline, the Institute reserves the right to terminate your studentship.</p>
            <p>7.You will be provided with the option to defer or withdraw if you have valid reasons to defer from the course you have been admitted. The period of deferment must NOT exceed <u><strong>three weeks</strong></u> after the commencement of lectures.</p>
            <p>8.You are required to dress appropriately either formally or traditionally. MDI reserves the right to send you home to be properly dressed before being allowed in the lecture room if found inappropriately dressed.</p>
            <p>9.You are required to respect and obey the instructions from the Trainers and to conform to the Rules and Regulations of the Institute.</p>
            <p>10.At the end of your studies at this particular level, you are required to renew your registration with the Registry for enrolment into the next level upon satisfying the requirements to do so. This must be done within <u><strong>two weeks</strong></u> of completion of your study of the current level.</p>
        </div>
    </div>
</body>

</html>
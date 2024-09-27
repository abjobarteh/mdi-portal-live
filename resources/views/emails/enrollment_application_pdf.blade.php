{{-- <!DOCTYPE html>
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
        }
        h1 {
            color: #007BFF;
            text-align: center;
            font-size: 24px;
        }
        p {
            margin: 10px 0;
        }
        .signature {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: center;">
            <img src="images/logos/mdi_logo.png" style="width: 170px; margin-bottom: 10px;" />
        </div>        
        <h1>MDI Acceptance Letter</h1>
        <p>Dear {{ $fullname }},</p>
<p>Congratulations! We are pleased to inform you that you have been accepted into the Management Development Institute (MDI). Our admissions committee thoroughly reviewed your application and we were highly impressed by your academic achievements and letters of recommendation.</p>
<p>We are excited to have you as a part of our incoming class and look forward to seeing the incredible contributions you will make during your time with us. Please review the enclosed enrollment materials and submit the necessary forms and deposit by the stated deadline to secure your place. Should you have any questions or need any assistance, please do not hesitate to reach out to our office.</p>
<p>Note: Your matriculation number is <strong>{{ $matnumber }}</strong>.</p>
<p>You are invited for an orientation on <strong>{{ $date }}</strong>.</p>
<p>Congratulations again and we cannot wait to welcome you to MDI.</p>
<p class="signature">Sincerely,</p>
<p>Office of the Registrar</p>
</div>
</body>

</html> --}}
<?php

use App\Models\Student;

$studentsWithPrograms = Student::join('programs as b', 'students.program_id', '=', 'b.id')
    ->where('students.user_id', $userid)
    ->select('b.fee', 'b.name')
    ->get();

// Loop through students (although typically we expect one student per mat_number)
foreach ($studentsWithPrograms as $student) {
    $prname = $student->name;
    $prfee = $student->fee;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDI Enrollment Letter</title>
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
            font-size: 24px;
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
      
    </style>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="images/logos/mdi_logo.png" alt="MDI Logo" />
        </div>
        <h1>MDI Enrollment Letter</h1>
        <p>Dear {{ $fullname }},</p>
        <p>We warmly welcome you to the student portal and congratulate you on your successful progression to the next phase of your academic journey.</p>
        <p>As part of our commitment to ensuring a seamless experience, we would like to inform you that portal registration is now open.</p>
        <p>Please take note that during the registration process, it is crucial to update your student number to match the one provided by the office of the registrar.</p>
        <p>This will ensure that your records remain accurate and up-to-date.</p>
        <p>Thank you for your cooperation, and we wish you a productive and successful academic year.</p>
        <p> Program: <strong>{{ $prname }}</strong>.</p>
        <p class="signature">Sincerely,</p>
        <p>Office of the Registrar</p>
        <div class="signature">
            <img src="E:/mdiportal/resources/js/src/assets/images/logos/mdiesign.png" alt="Registrar Signature" width="100px;"  height="100px;"  />
        </div> 
    </div>
</body>

</html>
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
            font-size: 24px;
        }

        p {
            margin: 10px 0;
            text-align: left;
            /* Reset text alignment for paragraphs */
        }

        .signature {
            margin-top: 20px;
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
        <p>Dear {{ $fullname }},</p>
        <p>We are pleased to inform you that your application for admission to the Management Development Institute (MDI) Program has been reviewed by our admissions committee. After careful consideration, we are excited to offer you conditional admission to the program.</p>
        <p>Your academic achievements and qualifications demonstrate great potential, and we believe you will greatly benefit from our program. However, please note that there are certain conditions that need to be met to confirm your admission:</p>
        <ul>
            <li>Completion of the remaining prerequisite courses</li>
            <li>Maintaining a minimum grade requirement in all prerequisite courses</li>
            <li>Submission of official WASSCE scores with four relevant credits in your field of study, required for final confirmation</li>
        </ul>
        <p>Once these conditions are met, your admission will be confirmed, and you will become a full-fledged student of MDI. We are thrilled to have you as part of our incoming class and look forward to your contributions during your time with us.</p>
        <p>Please pay the initial deposit by the stated deadline to secure your place. Should you have any questions or need assistance, please do not hesitate to reach out to our office.</p>
        <p>Note: Your matriculation number is <strong>{{ $matnumber }}</strong>. You are invited to an orientation on <strong>{{ $date }}</strong>.</p>
        <p>Congratulations once again! We look forward to welcoming you to MDI.</p>
        <p class="signature">Sincerely,</p>
        <p>Admissions Committee<br>
            Management Development Institute</p>
    </div>
</body>

</html>
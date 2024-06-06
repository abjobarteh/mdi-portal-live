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
        <h1>MDI Acceptance Letter</h1>
        <p>Dear {{ $fullname }},</p>
        <p>Congratulations! We are pleased to inform you that you have been accepted into the Management Development Institute (MDI). Our admissions committee thoroughly reviewed your application and we were highly impressed by your academic achievements and letters of recommendation.</p>
        <p>We are excited to have you as a part of our incoming class and look forward to seeing the incredible contributions you will make during your time with us. Please review the enclosed enrollment materials and submit the necessary forms and deposit by the stated deadline to secure your place. Should you have any questions or need any assistance, please do not hesitate to reach out to our office.</p>
        <p>Note: Your matriculation number is <strong>{{ $matnumber }}</strong>.</p>
        <p>Congratulations again and we cannot wait to welcome you to MDI.</p>
        <p class="signature">Sincerely,</p>
        <p>Office of the Registrar</p>
    </div>
</body>
</html>
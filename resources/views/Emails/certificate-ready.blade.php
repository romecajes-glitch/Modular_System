<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Ready</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .certificate-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 Certificate Ready!</h1>
        <p>Congratulations on completing your program!</p>
    </div>
    
    <div class="content">
        <h2>Dear {{ $student->name }},</h2>
        
        <p>We are pleased to inform you that your certificate for the <strong>{{ $program->name }}</strong> program is now ready and available for download.</p>
        
        <div class="certificate-info">
            <h3>Certificate Details:</h3>
            <ul>
                <li><strong>Program:</strong> {{ $program->name }}</li>
                <li><strong>Certificate Number:</strong> {{ $certificate->certificate_number }}</li>
                <li><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($certificate->issue_date)->format('F j, Y') }}</li>
                @if($certificate->instructor_name)
                <li><strong>Instructor:</strong> {{ $certificate->instructor_name }}</li>
                @endif
            </ul>
        </div>
        
        <p>You can now view and download your certificate by logging into your student portal and navigating to the Certificates section.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('student.certificate') }}" class="button">View Your Certificate</a>
        </div>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        
        <p>Congratulations once again on your achievement!</p>
        
        <p>Best regards,<br>
        <strong>Bohol Northern Star College (BNSC)</strong><br>
        Education Department</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Bohol Northern Star College. All rights reserved.</p>
    </div>
</body>
</html>

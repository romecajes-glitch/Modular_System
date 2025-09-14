<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate PDF</title>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&family=Satisfy&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inria Serif', serif;
            width: 11in;
            height: 8.5in;
        }
        .certificate-preview-bg {
            background: url('{{ asset("pictures/certificate.png") }}') center/cover no-repeat;
            width: 11in;
            height: 8.5in;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            font-family: 'Inria Serif', serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .certificate-preview-content {
            text-align: center;
            padding: 0px;
            width: 100%;
            max-width: 9in;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .preview-student-name {
            font-family: 'Satisfy', cursive;
            font-size: 38px;
            margin-top: 50px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #b49958;
            text-transform: capitalize;
            line-height: 1.2;
        }
        .preview-completion-text {
            font-size: 16px;
            margin-top: 2px;
            color: #7a0000;
        }
    </style>
</head>
<body>
    <div class="certificate-preview-bg">
        <div class="certificate-preview-content">
            <h1 class="preview-student-name">{{ $studentName }}</h1>
            <p class="preview-completion-text">
                Has successfully completed the <span>{{ $programName }}</span>
            </p>
            <p class="preview-completion-text">
                modular training program at Bohol Northern Star College (BNSC) on
            </p>
            <p class="preview-completion-text">
                {{ $completionDate }}
            </p>
        </div>
    </div>
    
    <!-- Loading System Integration -->
    @include('partials.loading-integration')
</body>
</html>

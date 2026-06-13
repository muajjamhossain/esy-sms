<!DOCTYPE html>
<html>
<head>
    <title>Admit Card - {{ $admitCard->student->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .admit-card {
            border: 2px solid #000;
            padding: 20px;
            width: 400px;
            margin: 0 auto;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #ccc;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            color: #28a745;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .value {
            display: inline-block;
        }
        .photo {
            float: right;
            width: 100px;
            height: 100px;
            border: 1px solid #ccc;
            text-align: center;
            line-height: 100px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }
        .signature {
            margin-top: 20px;
            text-align: right;
        }
        @media print {
            .no-print {
                display: none;
            }
            .admit-card {
                border: 1px solid #000;
            }
        }
        .btn-print {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <button class="btn-print no-print" onclick="window.print()">Print Admit Card</button>
    </div>

    <div class="admit-card">
        <div class="header">
            <h2>School Management System</h2>
            <p>Admit Card {{ date('Y') }}</p>
        </div>

        <div class="photo">
            @if($admitCard->student->photo)
                <img src="{{ asset($admitCard->student->photo) }}" width="100" height="100">
            @else
                No Photo
            @endif
        </div>

        <div class="info-row">
            <span class="label">Student Name:</span>
            <span class="value">{{ $admitCard->student->name ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Father's Name:</span>
            <span class="value">{{ $admitCard->student->fname ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Class:</span>
            <span class="value">{{ $admitCard->class->name ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Roll No:</span>
            <span class="value">{{ $admitCard->roll_no }}</span>
        </div>
        <div class="info-row">
            <span class="label">Registration No:</span>
            <span class="value">{{ $admitCard->registration_no ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Exam Type:</span>
            <span class="value">{{ $admitCard->examType->name ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Exam Center:</span>
            <span class="value">{{ $admitCard->exam_center ?? 'Main Campus' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Room No:</span>
            <span class="value">{{ $admitCard->room_no ?? 'To be announced' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Seat No:</span>
            <span class="value">{{ $admitCard->seat_no ?? 'To be announced' }}</span>
        </div>

        <div class="footer">
            <p>This admit card is valid for the examination. Please bring this card to the exam hall.</p>
            <p>Issued Date: {{ date('d M Y', strtotime($admitCard->issue_date)) }}</p>
        </div>

        <div class="signature">
            _________________<br>
            Principal's Signature
        </div>
    </div>
</body>
</html>

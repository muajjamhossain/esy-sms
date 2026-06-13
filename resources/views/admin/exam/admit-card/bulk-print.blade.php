<!DOCTYPE html>
<html>
<head>
    <title>Bulk Admit Cards - {{ $admitCards->first()->examType->name ?? '' }} - {{ $admitCards->first()->class->name ?? '' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .admit-card {
            border: 2px solid #000;
            padding: 15px;
            width: 380px;
            margin: 10px;
            border-radius: 10px;
            display: inline-block;
            page-break-inside: avoid;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .header h3 {
            margin: 0;
            color: #28a745;
            font-size: 16px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .info-row {
            margin-bottom: 8px;
            font-size: 12px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        .value {
            display: inline-block;
        }
        .photo {
            float: right;
            width: 60px;
            height: 60px;
            border: 1px solid #ccc;
            text-align: center;
            line-height: 60px;
            font-size: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
        }
        .signature {
            margin-top: 10px;
            text-align: right;
            font-size: 10px;
        }
        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
            .admit-card {
                page-break-inside: avoid;
            }
        }
        .btn-print {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <button class="btn-print no-print" onclick="window.print()">Print All Admit Cards</button>
        <button class="btn-print no-print" onclick="window.close()">Close</button>
    </div>

    <div class="page-title">
        <h2>{{ $admitCards->first()->examType->name ?? '' }} Examination - {{ $admitCards->first()->class->name ?? '' }}</h2>
        <p>Total Students: {{ count($admitCards) }}</p>
    </div>

    @foreach($admitCards as $card)
    <div class="admit-card">
        <div class="header">
            <h3>School Management System</h3>
            <p>Admit Card {{ date('Y') }}</p>
            <p><strong>{{ $card->examType->name ?? '' }} Examination</strong></p>
        </div>

        <div class="photo">
            @if($card->student->photo)
                <img src="{{ asset($card->student->photo) }}" width="60" height="60">
            @else
                No Photo
            @endif
        </div>

        <div class="info-row">
            <span class="label">Student Name:</span>
            <span class="value">{{ $card->student->name ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Father's Name:</span>
            <span class="value">{{ $card->student->fname ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Class:</span>
            <span class="value">{{ $card->class->name ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Roll No:</span>
            <span class="value">{{ $card->roll_no }}</span>
        </div>
        <div class="info-row">
            <span class="label">Admit Card No:</span>
            <span class="value">{{ $card->admit_card_no }}</span>
        </div>
        <div class="info-row">
            <span class="label">Exam Center:</span>
            <span class="value">{{ $card->exam_center ?? 'Main Campus' }}</span>
        </div>

        <div class="footer">
            <p>Valid for examination only</p>
            <p>Issued: {{ date('d M Y', strtotime($card->issue_date)) }}</p>
        </div>

        <div class="signature">
            _________________<br>
            Principal's Signature
        </div>
    </div>
    @endforeach
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <link rel="stylesheet" href="{{ asset('backend/css/idCard.css') }}"> --}}
    <title>ID Card</title>
<!--
    So lets start -->


    <style>
        *{
            margin: 00px;
            padding: 00px;
            box-sizing: content-box;
            }

            .container {
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e6ebe0;
            flex-direction: row;
            flex-flow: wrap;

            }

            .font{
            height: 375px;
            width: 250px;
            position: relative;
            border-radius: 10px;
            }

            .top{
            height: 30%;
            width: 100%;
            background-color: #8338ec;
            position: relative;
            z-index: 5;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            }

            .bottom{
            height: 70%;
            width: 100%;
            background-color: white;
            position: absolute;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            }

            .top img{
            height: 100px;
            width: 100px;
            background-color: #e6ebe0;
            border-radius: 10px;
            position: absolute;
            top:60px;
            left: 75px;
            }
            .bottom p{
            position: relative;
            top: 60px;
            text-align: center;
            text-transform: capitalize;
            font-weight: bold;
            font-size: 20px;
            text-emphasis: spacing;
            }
            .bottom .desi{
            font-size:12px;
            color: grey;
            font-weight: normal;
            }
            .bottom .no{
            font-size: 15px;
            font-weight: normal;
            }
            .barcode img
            {
            height: 80px;
            width: 80px;
            text-align: center;
            margin: 5px;
            }
            .barcode{
            text-align: center;
            position: relative;
            top: 70px;
            }

            .back
            {
            height: 375px;
            width: 250px;
            border-radius: 10px;
            background-color: #8338ec;

            }
            .qr img{
            height: 80px;
            width: 100%;
            margin: 20px;
            background-color: white;
            }
            .Details {
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 25px;
            }


            .details-info{
            color: white;
            text-align: left;
            padding: 5px;
            line-height: 20px;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
            line-height: 22px;
            }

            .logo {
            height: 40px;
            width: 150px;
            padding: 40px;
            }

            .logo img{
            height: 100%;
            width: 100%;
            object-fit: contain;
            color: white ;

            }
            .padding{
            padding-right: 20px;
            }

            @media screen and (max-width:400px)
            {
            .container{
                height: 130vh;
            }
            .container .front{
                margin-top: 50px;
            }
            }
            @media screen and (max-width:600px)
            {
            .container{
                height: 130vh;
            }
            .container .front{
                margin-top: 50px;
            }

        }
    </style>
</head>
<body>
        @php
            $student = $details->student;
            $studentPayload = json_encode([
                'institution' => 'Fateha School/Madrasa',
                'student_id' => $student->id_no,
                'name' => $student->name,
                'roll' => $details->roll,
                'session' => optional($details->student_year)->name,
                'class' => optional($details->student_class)->name,
                'mobile' => $student->mobile,
            ], JSON_UNESCAPED_SLASHES);
            $institutionPayload = 'Fateha School/Madrasa | Uttora, Dhaka, Bangladesh | 01911194724 | support@muajjam.com';
            $qrUrl = 'https://quickchart.io/qr?size=180&margin=1&text=' . rawurlencode($studentPayload);
            $barcodeUrl = 'https://bwipjs-api.metafloor.com/?bcid=code128&scale=2&height=12&includetext&text=' . rawurlencode($institutionPayload);
        @endphp
        <div class="container">
            <div class="padding">
                <div class="font">
                    <div class="top">
                        <img src="{{ !empty($student->image) ? url('upload/student_images/'.$student->image) : url('upload/no_image.jpg') }}" alt="{{ $student->name }}">
                    </div>
                    <div class="bottom">
                        <p>{{ $student->name }}</p>
                        <p class="desi">ID No. {{ $student->id_no }}</p>
                        <p class="desi">Roll No. {{ $details->roll }}</p>
                        <p class="desi">Session {{ optional($details->student_year)->name ?: '—' }} = Class {{ optional($details->student_class)->name ?: '—' }}</p>
                        <div class="barcode">
                            <img src="{{ $qrUrl }}" alt="Student information QR code">
                        </div>
                        <br>
                        <p class="no">{{ $student->mobile }}</p>
                        <p class="no">{{ $student->address }}</p>
                    </div>
                </div>
            </div>
            <div class="back">
                <h1 class="Details">information</h1>
                <hr class="hr">
                <div class="details-info">
                    <p><b>Email : </b></p>
                    <p>muajjam.imu@gmail.com</p>
                    <p><b>Mobile No: </b></p>
                    <p>01911194724</p>
                    <p><b>School Address:</b></p>
                    <p>Uttora, Dhaka, Bangladesh</p>
                    </div>
                    <div class="logo">
                        <img src="{{ $barcodeUrl }}" alt="Fateha School/Madrasa institution barcode">
                    </div>


                    <hr>
                </div>
            </div>
        </div>
</body>
</html>

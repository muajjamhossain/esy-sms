@extends('admin.admin_master')
@section('admin')

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="row">
                <div class="col-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Seat Plan View</h3>
                            <div class="float-right">
                                <button onclick="window.print()" class="btn btn-primary">Print Seat Plan</button>
                                <a href="{{ route('seat.plan') }}" class="btn btn-danger">Back</a>
                            </div>
                        </div>
                        <div class="box-body">

                            @foreach($rooms as $room => $students)
                            <div class="room-section" style="margin-bottom: 30px; page-break-after: always;">
                                <h3 style="background: #007bff; color: white; padding: 10px; text-align: center;">
                                    Room No: {{ $room }}
                                </h3>

                                <div class="table-responsive">
                                    <table class="table table-bordered" style="text-align: center;">
                                        <thead>
                                            <tr>
                                                <th colspan="5" style="background: #f0f0f0;">Teacher's Desk</th>
                                            </tr>
                                            <tr>
                                                <th>Column 1</th>
                                                <th>Column 2</th>
                                                <th>Column 3</th>
                                                <th>Column 4</th>
                                                <th>Column 5</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $maxRow = $students->max('row_no') ?? 1;
                                                $groupedByRow = $students->groupBy('row_no');
                                            @endphp

                                            @for($row = 1; $row <= $maxRow; $row++)
                                            <tr>
                                                @for($col = 1; $col <= 5; $col++)
                                                    @php
                                                        $student = $groupedByRow[$row]->firstWhere('column_no', $col);
                                                    @endphp
                                                    <td style="padding: 15px; border: 1px solid #000;">
                                                        @if($student)
                                                            <div style="border: 1px solid #28a745; padding: 10px; border-radius: 5px;">
                                                                <strong>Roll: {{ $student->roll_no }}</strong><br>
                                                                <small>{{ $student->student->name ?? '' }}</small><br>
                                                                <small>Seat: {{ $student->seat_no }}</small>
                                                            </div>
                                                        @else
                                                            <div style="padding: 10px;">Empty</div>
                                                        @endif
                                                    </td>
                                                @endfor
                                             </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                                <div style="margin-top: 20px;">
                                    <h4>Student List for Room {{ $room }}</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr><th>SL</th><th>Roll No</th><th>Student Name</th><th>Seat No</th><th>Row</th><th>Column</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach($students as $key => $student)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $student->roll_no }}</td>
                                                <td>{{ $student->student->name ?? '' }}</td>
                                                <td>{{ $student->seat_no }}</td>
                                                <td>{{ $student->row_no }}</td>
                                                <td>{{ $student->column_no }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<style>
@media print {
    .box-header {
        display: none;
    }
    .room-section {
        page-break-after: always;
    }
    .btn, .float-right {
        display: none;
    }
}
</style>

@endsection

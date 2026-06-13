@extends('admin.admin_master')
@section('admin')

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="row">
                <div class="col-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Seat Plan Management</h3>
                        </div>
                        <div class="box-body">
                            <form method="GET" action="{{ route('seat.plan') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Exam Type</h5>
                                            <select name="exam_type_id" class="form-control" required>
                                                <option value="">Select Exam</option>
                                                @foreach($examTypes as $type)
                                                    <option value="{{ $type->id }}" {{ $exam_type_id == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Class</h5>
                                            <select name="class_id" class="form-control" required>
                                                <option value="">Select Class</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ $class_id == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group pt-25">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            @if($exam_type_id && $class_id)
                                                <button type="button" class="btn btn-success" onclick="generateSeatPlan()">Generate Seat Plan</button>
                                                <a href="{{ route('seat.plan.view', [$exam_type_id, $class_id]) }}" class="btn btn-info">View Seat Plan</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-header">
                            <h3>Seat Plan List</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr><th>Room</th><th>Row</th><th>Seat</th><th>Student Name</th><th>Roll No</th><th>Bench</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($seatPlans as $plan)
                                        <tr>
                                            <td>{{ $plan->room_no }}</td>
                                            <td>{{ $plan->row_no }}</td>
                                            <td>{{ $plan->seat_no }}</td>
                                            <td>{{ $plan->student->name ?? '' }}</td>
                                            <td>{{ $plan->roll_no }}</td>
                                            <td>{{ $plan->bench_no }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<form id="generateForm" method="POST" action="{{ route('seat.plan.generate') }}">
    @csrf
    <input type="hidden" name="exam_type_id" value="{{ $exam_type_id }}">
    <input type="hidden" name="class_id" value="{{ $class_id }}">
</form>



@endsection

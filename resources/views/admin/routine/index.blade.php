@extends('admin.admin_master')
@section('admin')

<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">

                    <!-- Filter Box -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Class Routine Filter</h3>
                        </div>
                        <div class="box-body">
                            <form method="GET" action="{{ route('routine.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Select Class <span class="text-danger">*</span></h5>
                                            <select name="class_id" class="form-control select2" required>
                                                <option value="">Select Class</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ $class_id == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Select Day <span class="text-danger">*</span></h5>
                                            <select name="day_id" class="form-control select2">
                                                <option value="">All Days</option>
                                                @foreach($days as $day)
                                                    <option value="{{ $day->id }}" {{ $day_id == $day->id ? 'selected' : '' }}>
                                                        {{ $day->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group pt-25">
                                            <button type="submit" class="btn btn-primary">Filter Routine</button>
                                            <a href="{{ route('routine.index') }}" class="btn btn-danger">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Add Routine Box -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Class Routine</h3>
                            <button type="button" class="btn btn-rounded btn-success mb-5 float-right" data-toggle="modal" data-target="#addRoutineModal">
                                Add New Routine
                            </button>
                        </div>
                    </div>

                    <!-- Routine Display Box -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                Class Routine
                                @if($class_id)
                                    - {{ $classes->find($class_id)->name ?? '' }}
                                @endif
                                @if($day_id)
                                    - {{ $days->find($day_id)->name ?? '' }}
                                @endif
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">SL</th>
                                            <th>Day</th>
                                            <th>Class</th>
                                            <th>Time Slot</th>
                                            <th>Subject</th>
                                            <th>Teacher</th>
                                            <th>Room No</th>
                                            <th width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($routines as $key => $routine)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $routine->routineDay->name ?? '' }}</td>
                                            <td>{{ $routine->class->name ?? '' }}</td>
                                            <td>
                                                {{ $routine->startTime->time_slot ?? '' }}<br>
                                                <small>{{ $routine->startTime->start_time ?? '' }} - {{ $routine->endTime->end_time ?? '' }}</small>
                                            </td>
                                            <td>{{ $routine->subject->name ?? '' }}</td>

                                            <td>{{ $routine->teacher->name ?? '' }}</td>
                                            <td>{{ $routine->room_no }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm" onclick="editRoutine({{ $routine->id }})">Edit</button>
                                                {{-- <button class="btn btn-danger btn-sm" id="delete_routine" data-url="{{ route('routine.destroy', $routine->id) }}">Delete</button> --}}
                                                <a href="{{ route('routine.destroy', $routine->id) }}" class="btn btn-danger btn-sm" id="delete">Delete</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @if(count($routines) == 0)
                                        <tr>
                                            <td colspan="7" class="text-center">No routine found for this class</td>
                                        </tr>
                                        @endif
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

<!-- Add Routine Modal -->
<div class="modal fade" id="addRoutineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Class Routine</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('routine.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Class <span class="text-danger">*</span></h5>
                                <select name="class_id" class="form-control select2" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Subject <span class="text-danger">*</span></h5>
                                <select name="subject_id" class="form-control select2" required>
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Teacher <span class="text-danger">*</span></h5>
                                <select name="employee_id" class="form-control select2" required>
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Day <span class="text-danger">*</span></h5>
                                <select name="routine_day_id" class="form-control select2" required>
                                    <option value="">Select Day</option>
                                    @foreach($days as $day)
                                        <option value="{{ $day->id }}">{{ $day->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Start Time <span class="text-danger">*</span></h5>
                                <select name="start_time_id" class="form-control select2" required>
                                    <option value="">Select Start Time</option>
                                    @foreach($times as $time)
                                        <option value="{{ $time->id }}">{{ $time->time_slot }} ({{ $time->start_time }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>End Time <span class="text-danger">*</span></h5>
                                <select name="end_time_id" class="form-control select2" required>
                                    <option value="">Select End Time</option>
                                    @foreach($times as $time)
                                        <option value="{{ $time->id }}">{{ $time->time_slot }} ({{ $time->end_time }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Room No</h5>
                                <input type="text" name="room_no" class="form-control" placeholder="Room Number">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h5>Note (Optional)</h5>
                                <textarea name="note" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Routine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Routine Modal -->
<div class="modal fade" id="editRoutineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Class Routine</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Class <span class="text-danger">*</span></h5>
                                <select name="class_id" id="edit_class_id" class="form-control select2" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Subject <span class="text-danger">*</span></h5>
                                <select name="subject_id" id="edit_subject_id" class="form-control select2" required>
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Teacher <span class="text-danger">*</span></h5>
                                <select name="employee_id" id="edit_employee_id" class="form-control select2" required>
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Day <span class="text-danger">*</span></h5>
                                <select name="routine_day_id" id="edit_routine_day_id" class="form-control select2" required>
                                    <option value="">Select Day</option>
                                    @foreach($days as $day)
                                        <option value="{{ $day->id }}">{{ $day->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Start Time <span class="text-danger">*</span></h5>
                                <select name="start_time_id" id="edit_start_time_id" class="form-control select2" required>
                                    <option value="">Select Start Time</option>
                                    @foreach($times as $time)
                                        <option value="{{ $time->id }}">{{ $time->time_slot }} ({{ $time->start_time }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>End Time <span class="text-danger">*</span></h5>
                                <select name="end_time_id" id="edit_end_time_id" class="form-control select2" required>
                                    <option value="">Select End Time</option>
                                    @foreach($times as $time)
                                        <option value="{{ $time->id }}">{{ $time->time_slot }} ({{ $time->end_time }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h5>Room No</h5>
                                <input type="text" name="room_no" id="edit_room_no" class="form-control" placeholder="Room Number">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h5>Note (Optional)</h5>
                                <textarea name="note" id="edit_note" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Routine</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

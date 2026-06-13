@extends('admin.admin_master')
@section('admin')

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="row">
                <div class="col-12">

                    <!-- Filter Box -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Exam Routine Filter</h3>
                        </div>
                        <div class="box-body">
                            <form method="GET" action="{{ route('exam.routine') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Exam Type</h5>
                                            <select name="exam_type_id" class="form-control">
                                                <option value="">All Exams</option>
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
                                            <select name="class_id" class="form-control">
                                                <option value="">All Classes</option>
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
                                            <a href="{{ route('exam.routine') }}" class="btn btn-danger">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Add Routine -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Exam Routine</h3>
                            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addModal">
                                Add New
                            </button>
                        </div>
                    </div>

                    <!-- Routine List -->
                    <div class="box">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Exam</th>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Room</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($routines as $key => $routine)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $routine->examType->name }}</td>
                                            <td>{{ $routine->class->name }}</td>
                                            <td>{{ $routine->subject->name }}</td>
                                            <td>{{ date('d M Y', strtotime($routine->exam_date)) }}</td>
                                            <td>{{ date('h:i A', strtotime($routine->start_time)) }} - {{ date('h:i A', strtotime($routine->end_time)) }}</td>
                                            <td>{{ $routine->room_no }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm" onclick="editRoutine({{ $routine->id }})">Edit</button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteRoutine({{ $routine->id }})">Delete</button>
                                            </td>
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

<!-- Add Modal -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('exam.routine.store') }}">
                @csrf
                <div class="modal-header">
                    <h4>Add Exam Routine</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Exam Type *</label>
                                <select name="exam_type_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($examTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Class *</label>
                                <select name="class_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject *</label>
                                <select name="subject_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Exam Date *</label>
                                <input type="date" name="exam_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start Time *</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>End Time *</label>
                                <input type="time" name="end_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Room No</label>
                                <input type="text" name="room_no" class="form-control" placeholder="Room Number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editRoutine(id) {
    $.get('/exam-routine/' + id + '/edit', function(data) {
        // Populate edit modal
        $('#edit_exam_type_id').val(data.exam_type_id);
        $('#edit_class_id').val(data.class_id);
        $('#edit_subject_id').val(data.subject_id);
        $('#edit_exam_date').val(data.exam_date);
        $('#edit_start_time').val(data.start_time);
        $('#edit_end_time').val(data.end_time);
        $('#edit_room_no').val(data.room_no);
        $('#editForm').attr('action', '/exam-routine/' + id);
        $('#editModal').modal('show');
    });
}

function deleteRoutine(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Delete this exam routine?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/exam-routine/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    location.reload();
                }
            });
        }
    });
}
</script>

@endsection

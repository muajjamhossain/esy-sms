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
                            <h3 class="box-title">Online Classes Management</h3>
                            <a href="{{ route('online-class.create') }}" class="btn btn-success float-right">
                                <i class="ti-video-camera"></i> Create New Class
                            </a>
                        </div>
                        <div class="box-body">
                            <form method="GET" action="{{ route('online-class.index') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Filter by Class</h5>
                                            <select name="class_id" class="form-control" onchange="this.form.submit()">
                                                <option value="">All Classes</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ $class_id == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Online Classes List -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Scheduled Online Classes</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Topic</th>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th>Teacher</th>
                                            <th>Date & Time</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($onlineClasses as $key => $class)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $class->topic }}</td>
                                            <td>{{ $class->class->name ?? '' }}</td>
                                            <td>{{ $class->subject->name ?? '' }}</td>
                                            <td>{{ $class->teacher->name ?? '' }}</td>
                                            <td>{{ date('d M Y, h:i A', strtotime($class->start_time)) }}</td>
                                            <td>{{ $class->duration }} min</td>
                                            <td>
                                                @if($class->status == 1)
                                                    <span class="badge badge-primary">Scheduled</span>
                                                @elseif($class->status == 2)
                                                    <span class="badge badge-warning">Ongoing</span>
                                                @elseif($class->status == 3)
                                                    <span class="badge badge-success">Completed</span>
                                                @else
                                                    <span class="badge badge-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($class->start_time <= now() && $class->status == 1)
                                                    <a href="{{ route('online-class.start', $class->id) }}" class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="ti-control-play"></i> Start
                                                    </a>
                                                @endif
                                                <a href="{{ route('online-class.join', $class->id) }}" class="btn btn-info btn-sm" target="_blank">
                                                    <i class="ti-video-camera"></i> Join
                                                </a>
                                                <a href="{{ route('online-class.edit', $class->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="ti-pencil"></i> Edit
                                                </a>
                                                <button class="btn btn-danger btn-sm" onclick="deleteClass({{ $class->id }})">
                                                    <i class="ti-trash"></i> Delete
                                                </button>
                                                @if($class->status == 2)
                                                    <button class="btn btn-secondary btn-sm" onclick="endMeeting({{ $class->id }})">
                                                        End Class
                                                    </button>

                                                @endif

                                                @if($class->status == 3)
                                                    <a href="{{ route('recordings.fetch', $class->id) }}" class="btn btn-success btn-sm">
                                                        <i class="ti-pencil"></i> Record Class
                                                    </a>
                                                @endif

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

<script>
function deleteClass(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the meeting from Zoom as well!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/online-class/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.fire('Deleted!', 'Class has been deleted.', 'success');
                    location.reload();
                }
            });
        }
    });
}

function endMeeting(id) {
    Swal.fire({
        title: 'End Meeting?',
        text: "This will end the Zoom meeting",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, end meeting'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/online-class/end/' + id;
        }
    });
}
</script>

@endsection

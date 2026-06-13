@extends('admin.admin_master')
@section('admin')

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="row">
                <div class="col-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Class Recordings</h3>
                            <div class="float-right">
                                <a href="{{ route('recordings.fetch-all') }}" class="btn btn-info" onclick="return confirm('This may take a while. Continue?')">
                                    <i class="ti-cloud-down"></i> Fetch All Recordings
                                </a>
                            </div>
                        </div>
                        <div class="box-body">
                            <form method="GET" action="{{ route('recordings.index') }}">
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

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Recording List</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                @foreach($recordings as $recording)
                                <div class="col-md-4 mb-30">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                <video class="embed-responsive-item" controls>
                                                    <source src="{{ $recording->recording_url }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                            <h5 class="card-title">{{ $recording->onlineClass->topic ?? '' }}</h5>
                                            <p class="text-muted mb-1">
                                                <small>
                                                    <i class="ti-book"></i> {{ $recording->onlineClass->class->name ?? '' }} |
                                                    <i class="ti-bookmark"></i> {{ $recording->onlineClass->subject->name ?? '' }}
                                                </small>
                                            </p>
                                            <p class="text-muted mb-1">
                                                <small>
                                                    <i class="ti-user"></i> {{ $recording->onlineClass->teacher->name ?? '' }} |
                                                    <i class="ti-calendar"></i> {{ date('d M Y', strtotime($recording->recording_date)) }}
                                                </small>
                                            </p>
                                            <p class="text-muted">
                                                <small>
                                                    <i class="ti-clipboard"></i> Duration: {{ $recording->duration ?? 'N/A' }} min |
                                                    <i class="ti-harddrives"></i> Size: {{ $recording->formatted_file_size }}
                                                </small>
                                            </p>
                                            <div class="mt-2">
                                                <a href="{{ $recording->recording_url }}" class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="ti-control-play"></i> Watch
                                                </a>
                                                <a href="{{ $recording->recording_url }}" class="btn btn-success btn-sm" download>
                                                    <i class="ti-download"></i> Download
                                                </a>
                                                <button class="btn btn-danger btn-sm" onclick="deleteRecording({{ $recording->id }})">
                                                    <i class="ti-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-3">
                                {{ $recordings->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<script>
function deleteRecording(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This recording will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/recordings/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.fire('Deleted!', 'Recording has been deleted.', 'success');
                    location.reload();
                }
            });
        }
    });
}
</script>

@endsection

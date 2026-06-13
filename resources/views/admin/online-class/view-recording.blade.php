@extends('admin.admin_master')
@section('admin')

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="row">
                <div class="col-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">View Recording</h3>
                            <a href="{{ route('recordings.index') }}" class="btn btn-danger float-right">
                                <i class="ti-arrow-left"></i> Back to Recordings
                            </a>
                        </div>
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-8 offset-md-2">

                                    <!-- Video Player -->
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <video class="embed-responsive-item" controls autoplay>
                                                    <source src="{{ $recording->recording_url }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                    <a href="{{ $recording->recording_url }}">Download Video</a>
                                                </video>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Recording Details -->
                                    <div class="card mt-3">
                                        <div class="card-header bg-info text-white">
                                            <h4 class="mb-0">Recording Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="30%">Class Topic</th>
                                                    <td>{{ $recording->onlineClass->topic ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Class</th>
                                                    <td>{{ $recording->onlineClass->class->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Subject</th>
                                                    <td>{{ $recording->onlineClass->subject->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Teacher</th>
                                                    <td>{{ $recording->onlineClass->teacher->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Recording Date</th>
                                                    <td>{{ date('d M Y, h:i A', strtotime($recording->recording_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Duration</th>
                                                    <td>{{ $recording->duration ?? 'N/A' }} minutes</td>
                                                </tr>
                                                <tr>
                                                    <th>File Size</th>
                                                    <td>{{ $recording->formatted_file_size }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Recording Type</th>
                                                    <td>
                                                        @if($recording->recording_type == 'video')
                                                            <span class="badge badge-success">Video</span>
                                                        @elseif($recording->recording_type == 'audio')
                                                            <span class="badge badge-info">Audio</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ $recording->recording_type }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Description</th>
                                                    <td>{{ $recording->onlineClass->description ?? 'No description available.' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="card mt-3">
                                        <div class="card-body text-center">
                                            <a href="{{ $recording->recording_url }}" class="btn btn-primary btn-lg" download>
                                                <i class="ti-download"></i> Download Recording
                                            </a>
                                            <a href="{{ route('recordings.index') }}" class="btn btn-secondary btn-lg">
                                                <i class="ti-back-left"></i> Back to List
                                            </a>
                                            <button class="btn btn-danger btn-lg" onclick="deleteRecording({{ $recording->id }})">
                                                <i class="ti-trash"></i> Delete Recording
                                            </button>
                                        </div>
                                    </div>

                                </div>
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
                    window.location.href = "{{ route('recordings.index') }}";
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Something went wrong!', 'error');
                }
            });
        }
    });
}
</script>

@endsection

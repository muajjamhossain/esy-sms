@extends('admin.admin_master')
@section('admin')

<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="row">
                <div class="col-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create New Online Class</h3>
                            <a href="{{ route('online-class.index') }}" class="btn btn-danger float-right">
                                <i class="ti-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="box-body">

                            <form method="POST" action="{{ route('online-class.store') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Class <span class="text-danger">*</span></h5>
                                            <select name="class_id" class="form-control select2" required id="class_id">
                                                <option value="">Select Class</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Subject <span class="text-danger">*</span></h5>
                                            <select name="subject_id" class="form-control select2" required id="subject_id">
                                                <option value="">Select Subject</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                        {{ $subject->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('subject_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Teacher <span class="text-danger">*</span></h5>
                                            <select name="teacher_id" class="form-control select2" required>
                                                <option value="">Select Teacher</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                        {{ $teacher->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('teacher_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Topic <span class="text-danger">*</span></h5>
                                            <input type="text" name="topic" class="form-control" value="{{ old('topic') }}" required placeholder="e.g., Math Chapter 1">
                                            @error('topic')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Date & Time <span class="text-danger">*</span></h5>
                                            <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                            @error('start_time')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Duration (minutes) <span class="text-danger">*</span></h5>
                                            <select name="duration" class="form-control" required>
                                                <option value="30">30 minutes</option>
                                                <option value="45">45 minutes</option>
                                                <option value="60" selected>60 minutes</option>
                                                <option value="90">90 minutes</option>
                                                <option value="120">120 minutes</option>
                                                <option value="150">150 minutes</option>
                                                <option value="180">180 minutes</option>
                                            </select>
                                            @error('duration')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h5>Description (Optional)</h5>
                                            <textarea name="description" class="form-control" rows="4" placeholder="Class description, topics to cover, prerequisites...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <i class="ti-info-alt"></i>
                                            <strong>Note:</strong> Zoom meeting will be automatically created.
                                            Meeting link and password will be sent to students.
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-video-camera"></i> Create Class & Zoom Meeting
                                        </button>
                                        <button type="reset" class="btn btn-danger">
                                            <i class="ti-eraser"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Dynamic subject load based on class
        $('#class_id').on('change', function() {
            var class_id = $(this).val();
            if (class_id) {
                $.ajax({
                    url: "{{ route('get.subjects.by.class', '') }}/" + class_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#subject_id').empty();
                        $('#subject_id').append('<option value="">Select Subject</option>');
                        $.each(data, function(key, value) {
                            $('#subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#subject_id').empty();
                $('#subject_id').append('<option value="">Select Subject</option>');
            }
        });
    });
</script>

@endsection

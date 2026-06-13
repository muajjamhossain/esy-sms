@extends('student.master')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>My Class Recordings</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recordings as $recording)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{ $recording->onlineClass->topic ?? '' }}</h5>
                                    <p class="text-muted">
                                        {{ $recording->onlineClass->subject->name ?? '' }} |
                                        {{ date('d M Y', strtotime($recording->recording_date)) }}
                                    </p>
                                    <a href="{{ $recording->recording_url }}" class="btn btn-primary" target="_blank">
                                        Watch Recording
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

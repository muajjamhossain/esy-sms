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
                            <h3 class="box-title">Admit Card Management</h3>
                        </div>
                        <div class="box-body">
                            <form method="GET" action="{{ route('admit.card') }}">
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
                                            @if($exam_type_id && $class_id)
                                                <a href="{{ route('admit.card.generate', [$exam_type_id, $class_id]) }}" class="btn btn-success">Generate Admit Cards</a>
                                                <a href="{{ route('admit.card.bulk.print', ['exam_type_id' => $exam_type_id, 'class_id' => $class_id]) }}" class="btn btn-info">Bulk Print</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Admit Cards List -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Admit Cards List</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Student Name</th>
                                            <th>Roll No</th>
                                            <th>Class</th>
                                            <th>Exam</th>
                                            <th>Admit Card No</th>
                                            <th>Issue Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($admitCards as $key => $card)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $card->student->name ?? '' }}</td>
                                            <td>{{ $card->roll_no }}</td>
                                            <td>{{ $card->class->name ?? '' }}</td>
                                            <td>{{ $card->examType->name ?? '' }}</td>
                                            <td>{{ $card->admit_card_no }}</td>
                                            <td>{{ date('d M Y', strtotime($card->issue_date)) }}</td>
                                            <td>
                                                <a href="{{ route('admit.card.print', $card->id) }}" class="btn btn-info btn-sm" target="_blank">Print</a>
                                                <button class="btn btn-danger btn-sm" onclick="deleteCard({{ $card->id }})">Delete</button>
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
function deleteCard(id) {
    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admit-card/' + id,
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

@extends('admin.admin_master')

@section('admin')
<style>
    .dashboard-stat-card .box-body { padding: 16px; }
    .dashboard-stat-card .stat-icon { width: 42px; height: 42px; }
    .dashboard-stat-card .stat-label { font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dashboard-stat-card h3 { font-size: 22px; }
</style>
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">
            <div class="row">
                @php
                    $statCards = [
                        ['label' => __('messages.total_student'), 'value' => number_format($totalStudents), 'icon' => 'mdi-account-multiple', 'class' => 'primary'],
                        ['label' => __('messages.total_present'), 'value' => number_format($todayAttendance->present ?? 0), 'icon' => 'mdi-calendar-check', 'class' => 'success'],
                        ['label' => __('messages.monthly_income'), 'value' => number_format($monthlyIncome, 2), 'icon' => 'mdi-cash-plus', 'class' => 'warning'],
                        ['label' => __('messages.monthly_expense'), 'value' => number_format($monthlyExpense, 2), 'icon' => 'mdi-cash-minus', 'class' => 'info'],
                    ];
                @endphp
                @foreach($statCards as $card)
                    <div class="col-xl-2 col-lg-4 col-6">
                        <div class="box dashboard-stat-card overflow-hidden pull-up">
                            <div class="box-body">
                                <div class="icon stat-icon bg-{{ $card['class'] }}-light rounded">
                                    <i class="text-{{ $card['class'] }} mr-0 font-size-20 mdi {{ $card['icon'] }}"></i>
                                </div>
                                <p class="text-mute stat-label mt-15 mb-0">{{ $card['label'] }}</p>
                                <h3 class="text-white mb-0 font-weight-500">{{ $card['value'] }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-xl-2 col-lg-4 col-6"><div class="box bg-danger-light"><div class="box-body"><p class="text-mute mb-5">{{ __('messages.fee_due_students') }}</p><h3 class="mb-0">{{ number_format($monthlyFeeStudents->count()) }}</h3><small>{{ number_format($feeDueAmount, 2) }}</small></div></div></div>
                <div class="col-xl-2 col-lg-4 col-6"><div class="box bg-info-light"><div class="box-body"><p class="text-mute mb-5">{{ __('messages.exam_average') }}</p><h3 class="mb-0">{{ number_format($marksSummary->average ?? 0, 1) }}</h3><small>{{ __('messages.pass_rate') }}: {{ $passRate }}%</small></div></div></div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-12">
                    <div class="box">
                        <div class="box-body d-flex flex-wrap align-items-center">
                            <strong class="mr-15 mb-5">{{ __('messages.quick_actions') }}</strong>
                            <a href="{{ route('student.registration.add') }}" class="btn btn-sm btn-primary mr-5 mb-5">{{ __('messages.add_student') }}</a>
                            <a href="{{ route('student.attendance.add') }}" class="btn btn-sm btn-success mr-5 mb-5">{{ __('messages.take_attendance') }}</a>
                            <a href="{{ route('student.fee.add') }}" class="btn btn-sm btn-warning mr-5 mb-5">{{ __('messages.collect_fee') }}</a>
                            <a href="{{ route('marks.entry.add') }}" class="btn btn-sm btn-info mb-5">{{ __('messages.enter_marks') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-7 col-12">
                    <div class="box">
                        <div class="box-header d-flex justify-content-between">
                            <h4 class="box-title">{{ __('messages.upcoming_exams') }}</h4>
                            <a href="{{ route('exam.routine') }}" class="text-primary">{{ __('messages.view_all') }}</a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead><tr><th>{{ __('messages.date') }}</th><th>{{ __('messages.exam') }}</th><th>{{ __('messages.class') }}</th><th>{{ __('messages.subject') }}</th></tr></thead>
                                    <tbody>
                                    @forelse($upcomingExams as $exam)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M') }}</td>
                                            <td>{{ $exam->examType->name ?? '-' }}</td>
                                            <td>{{ $exam->class->name ?? '-' }}</td>
                                            <td>{{ $exam->subject->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-fade">{{ __('messages.no_upcoming_exams') }}</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-12">
                    <div class="box">
                        <div class="box-header"><h4 class="box-title">{{ __('messages.fee_due_summary') }}</h4></div>
                        <div class="box-body">
                            <p class="text-fade">{{ __('messages.students_without_monthly_payment') }}</p>
                            <h2 class="text-danger">{{ number_format($monthlyFeeStudents->count()) }}</h2>
                            <p class="mb-0">{{ __('messages.estimated_due') }}: <strong>{{ number_format($feeDueAmount, 2) }}</strong></p>
                            <a href="{{ route('monthly.fee.view') }}" class="btn btn-sm btn-outline-primary mt-15">{{ __('messages.open_fee_management') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">{{ __('messages.income_expense_overview') }}</h4>
                        </div>
                        <div class="box-body">
                            <div id="incomeExpenseChart" style="min-height: 330px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">{{ __('messages.students_by_class') }}</h4>
                        </div>
                        <div class="box-body">
                            <div id="classDistributionChart" style="min-height: 330px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">{{ __('messages.attendance_last_seven_days') }}</h4>
                        </div>
                        <div class="box-body">
                            <div id="attendanceChart" style="min-height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">{{ __('messages.today_attendance') }}</h4>
                        </div>
                        <div class="box-body text-center">
                            <div id="todayAttendanceChart" style="min-height: 220px;"></div>
                            <p class="text-fade mb-0">
                                {{ number_format($todayAttendance->present ?? 0) }} /
                                {{ number_format($todayAttendance->total ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        if (typeof ApexCharts === 'undefined') {
            return;
        }
        var chartOptions = {
            chart: { type: 'area', height: 330, toolbar: { show: false } },
            theme: { mode: 'dark' },
            stroke: { curve: 'smooth', width: 3 },
            dataLabels: { enabled: false },
            xaxis: { categories: @json($months->pluck('label')->values()) },
            series: [
                { name: @json(__('messages.income')), data: @json($months->map(function ($month) use ($incomeByMonth) { return (float) ($incomeByMonth[$month['key']] ?? 0); })->values()) },
                { name: @json(__('messages.expense')), data: @json($months->map(function ($month) use ($expenseByMonth) { return (float) ($expenseByMonth[$month['key']] ?? 0); })->values()) }
            ]
        };
        new ApexCharts(document.querySelector('#incomeExpenseChart'), chartOptions).render();

        new ApexCharts(document.querySelector('#classDistributionChart'), {
            chart: { type: 'donut', height: 330 },
            theme: { mode: 'dark' },
            labels: @json($classDistribution->pluck('name')->values()),
            series: @json($classDistribution->pluck('total')->map(function ($value) { return (int) $value; })->values()),
            legend: { position: 'bottom' }
        }).render();

        new ApexCharts(document.querySelector('#attendanceChart'), {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            theme: { mode: 'dark' },
            plotOptions: { bar: { borderRadius: 4, columnWidth: '45%' } },
            dataLabels: { enabled: false },
            xaxis: { categories: @json($attendanceTrend->pluck('label')->values()) },
            series: [
                { name: @json(__('messages.present')), data: @json($attendanceTrend->pluck('present')->values()) },
                { name: @json(__('messages.absent')), data: @json($attendanceTrend->pluck('absent')->values()) }
            ]
        }).render();

        new ApexCharts(document.querySelector('#todayAttendanceChart'), {
            chart: { type: 'radialBar', height: 220 },
            theme: { mode: 'dark' },
            series: [{{ ($todayAttendance->total ?? 0) > 0 ? round((($todayAttendance->present ?? 0) / $todayAttendance->total) * 100, 1) : 0 }}],
            labels: [@json(__('messages.attendance_rate'))],
            plotOptions: { radialBar: { dataLabels: { value: { formatter: function (value) { return value + '%'; } } } } }
        }).render();
    });
</script>
@endpush
@endsection

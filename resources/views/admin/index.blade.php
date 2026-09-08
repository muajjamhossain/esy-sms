@extends('admin.admin_master')

@section('admin')
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
                    <div class="col-xl-3 col-6">
                        <div class="box overflow-hidden pull-up">
                            <div class="box-body">
                                <div class="icon bg-{{ $card['class'] }}-light rounded w-60 h-60">
                                    <i class="text-{{ $card['class'] }} mr-0 font-size-24 mdi {{ $card['icon'] }}"></i>
                                </div>
                                <p class="text-mute mt-20 mb-0 font-size-16">{{ $card['label'] }}</p>
                                <h3 class="text-white mb-0 font-weight-500">{{ $card['value'] }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
@endsection

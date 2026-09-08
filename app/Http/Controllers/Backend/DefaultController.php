<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\User;
use App\Models\DiscountStudent;

use App\Models\StudentYear;
use App\Models\StudentClass;
use App\Models\StudentGroup;
use App\Models\StudentShift;
use DB;
use PDF;

use App\Models\AssignSubject;
use App\Models\StudentMarks;
use App\Models\ExamType;
use App\Models\AccountStudentFee;
use App\Models\AccountOtherCost;
use App\Models\AccountEmployeeSalary;
use App\Models\ExamRoutine;
use App\Models\FeeCategoryAmount;
use Carbon\Carbon;

class DefaultController extends Controller
{
    public function Dashboard()
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        $totalStudents = AssignStudent::count();
        $todayAttendance = DB::table('student_attendances')
            ->whereDate('date', $today)
            ->selectRaw('COUNT(DISTINCT student_id) as total')
            ->selectRaw("COUNT(DISTINCT CASE WHEN LOWER(attend_status) IN ('present', 'p') THEN student_id END) as present")
            ->first();

        $monthlyIncome = AccountStudentFee::where('date', 'like', $today->format('Y-m') . '%')
            ->sum('amount');
        $monthlyExpense = AccountOtherCost::whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])->sum('amount')
            + AccountEmployeeSalary::where('date', 'like', $today->format('Y-m') . '%')->sum('amount');

        $months = collect(range(5, 0))->map(function ($monthsAgo) use ($today) {
            $date = $today->copy()->subMonths($monthsAgo);
            return [
                'key' => $date->format('Y-m'),
                'label' => $date->format('M Y'),
            ];
        });

        $incomeByMonth = AccountStudentFee::where('date', '>=', $months->first()['key'])
            ->where('date', '<=', $today->format('Y-m'))
            ->selectRaw("LEFT(date, 7) as month, SUM(amount) as total")
            ->groupBy('month')->pluck('total', 'month');
        $expenseByMonth = AccountOtherCost::whereBetween('date', [
            $months->first()['key'] . '-01',
            $today->copy()->endOfMonth()->toDateString(),
        ])->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')->pluck('total', 'month')->toArray();
        $salaryByMonth = AccountEmployeeSalary::where('date', '>=', $months->first()['key'])
            ->where('date', '<=', $today->format('Y-m'))
            ->selectRaw("LEFT(date, 7) as month, SUM(amount) as total")
            ->groupBy('month')->pluck('total', 'month');
        $expenseByMonth = collect($expenseByMonth)->union($salaryByMonth)->mapWithKeys(function ($amount, $month) use ($salaryByMonth) {
            return [$month => (float) $amount + (float) ($salaryByMonth[$month] ?? 0)];
        });

        $attendanceTrend = collect(range(6, 0))->map(function ($daysAgo) use ($today) {
            $date = $today->copy()->subDays($daysAgo);
            $attendance = DB::table('student_attendances')
                ->whereDate('date', $date)
                ->selectRaw("COUNT(DISTINCT CASE WHEN LOWER(attend_status) IN ('present', 'p') THEN student_id END) as present")
                ->selectRaw("COUNT(DISTINCT student_id) as total")
                ->first();

            return [
                'label' => $date->format('D'),
                'present' => (int) ($attendance->present ?? 0),
                'absent' => max(0, (int) ($attendance->total ?? 0) - (int) ($attendance->present ?? 0)),
            ];
        });

        $classDistribution = AssignStudent::join('student_classes', 'student_classes.id', '=', 'assign_students.class_id')
            ->select('student_classes.name', DB::raw('COUNT(assign_students.id) as total'))
            ->groupBy('student_classes.id', 'student_classes.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $monthlyPaidStudents = AccountStudentFee::where('date', 'like', $today->format('Y-m') . '%')
            ->whereNotNull('student_id')
            ->pluck('student_id')
            ->unique();
        $monthlyFeeStudents = AssignStudent::whereNotIn('student_id', $monthlyPaidStudents)->get(['student_id', 'class_id']);
        $monthlyFeeAmounts = FeeCategoryAmount::where('fee_category_id', 2)
            ->whereIn('class_id', $monthlyFeeStudents->pluck('class_id')->unique())
            ->pluck('amount', 'class_id');
        $feeDueAmount = $monthlyFeeStudents->sum(function ($student) use ($monthlyFeeAmounts) {
            return (float) ($monthlyFeeAmounts[$student->class_id] ?? 0);
        });

        $upcomingExams = ExamRoutine::with(['examType', 'class', 'subject'])
            ->whereDate('exam_date', '>=', $today)
            ->whereDate('exam_date', '<=', $today->copy()->addDays(7))
            ->where('status', 1)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->limit(6)
            ->get();

        $marksSummary = StudentMarks::whereNotNull('marks')
            ->selectRaw('COUNT(*) as total, AVG(marks) as average')
            ->selectRaw('SUM(CASE WHEN marks >= 33 THEN 1 ELSE 0 END) as passed')
            ->first();
        $passRate = ($marksSummary->total ?? 0) > 0
            ? round(($marksSummary->passed / $marksSummary->total) * 100, 1)
            : 0;

        return view('admin.index', compact(
            'totalStudents',
            'todayAttendance',
            'monthlyIncome',
            'monthlyExpense',
            'months',
            'incomeByMonth',
            'expenseByMonth',
            'attendanceTrend',
            'classDistribution',
            'monthlyFeeStudents',
            'feeDueAmount',
            'upcomingExams',
            'marksSummary',
            'passRate'
        ));
    }

    public function GetSubject(Request $request){
    	$class_id = $request->class_id;
    	$allData = AssignSubject::with(['school_subject'])->where('class_id',$class_id)->get();
    	return response()->json($allData);

    }


    public function GetStudents(Request $request){
    	$year_id = $request->year_id;
    	$class_id = $request->class_id;
    	$allData = AssignStudent::with(['student'])->where('year_id',$year_id)->where('class_id',$class_id)->get();
    	return response()->json($allData);

    }




}
 
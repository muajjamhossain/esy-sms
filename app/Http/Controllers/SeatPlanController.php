<?php

namespace App\Http\Controllers;

use App\Models\ExamSeatPlan;
use App\Models\ExamAdmitCard;
use App\Models\ExamType;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class SeatPlanController extends Controller
{
    public function index(Request $request)
    {
        $exam_type_id = $request->exam_type_id;
        $class_id = $request->class_id;

        $seatPlans = ExamSeatPlan::with(['student', 'class', 'examType'])
            ->when($exam_type_id, function ($q) use ($exam_type_id) {
                return $q->where('exam_type_id', $exam_type_id);
            })
            ->when($class_id, function ($q) use ($class_id) {
                return $q->where('class_id', $class_id);
            })
            ->orderBy('room_no')
            ->orderBy('row_no')
            ->orderBy('seat_no')
            ->get();

        $examTypes = ExamType::all();
        $classes = StudentClass::all();

        return view('admin.exam.seat-plan.index', compact('seatPlans', 'examTypes', 'classes', 'exam_type_id', 'class_id'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'exam_type_id' => 'required',
            'class_id' => 'required',
        ]);

        $exam_type_id = $request->exam_type_id;
        $class_id = $request->class_id;

        // Get students with admit cards
        $students = ExamAdmitCard::where('exam_type_id', $exam_type_id)
            ->where('class_id', $class_id)
            ->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'No students found! Please generate admit cards first.');
        }

        // Clear existing seat plan
        ExamSeatPlan::where('exam_type_id', $exam_type_id)
            ->where('class_id', $class_id)
            ->delete();

        // Generate seat plan
        $rooms = ['101', '102', '103', '201', '202', '203', '301', '302'];
        $columnsPerRoom = 5;
        $rowsPerRoom = ceil($students->count() / count($rooms) / $columnsPerRoom);

        $roomIndex = 0;
        $currentRow = 1;
        $currentCol = 1;
        $currentSeat = 1;

        foreach ($students as $index => $student) {
            $room = $rooms[$roomIndex % count($rooms)];

            ExamSeatPlan::create([
                'exam_type_id' => $exam_type_id,
                'class_id' => $class_id,
                'room_no' => $room,
                'row_no' => $currentRow,
                'column_no' => $currentCol,
                'seat_no' => $currentSeat,
                'student_id' => $student->student_id,
                'roll_no' => $student->roll_no,
                'bench_no' => ceil($currentSeat / 2)
            ]);

            $currentSeat++;
            $currentCol++;

            if ($currentCol > $columnsPerRoom) {
                $currentCol = 1;
                $currentRow++;
            }

            if ($currentRow > $rowsPerRoom) {
                $currentRow = 1;
                $roomIndex++;
                $currentSeat = 1;
            }
        }

        return redirect()->back()->with('success', 'Seat plan generated successfully!');
    }

    public function view($exam_id, $class_id)
    {
        $seatPlans = ExamSeatPlan::with(['student', 'examType', 'class'])
            ->where('exam_type_id', $exam_id)
            ->where('class_id', $class_id)
            ->orderBy('room_no')
            ->orderBy('row_no')
            ->orderBy('column_no')
            ->get();

        if ($seatPlans->isEmpty()) {
            return redirect()->back()->with('error', 'No seat plan found! Please generate seat plan first.');
        }

        $rooms = $seatPlans->groupBy('room_no');

        return view('admin.exam.seat-plan.view', compact('rooms', 'seatPlans'));
    }

    public function destroy($id)
    {
        ExamSeatPlan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Seat plan deleted successfully!');
    }
}

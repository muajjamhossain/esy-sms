<?php

namespace App\Http\Controllers;

use App\Models\AssignStudent;
use App\Models\ExamAdmitCard;
use App\Models\ExamType;
use App\Models\StudentClass;
use App\Models\StudentYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdmitCardController extends Controller
{
    public function index(Request $request)
    {
        $exam_type_id = $request->exam_type_id;
        $class_id = $request->class_id;

        $admitCards = ExamAdmitCard::with(['student', 'class', 'examType'])
            ->when($exam_type_id, function ($q) use ($exam_type_id) {
                return $q->where('exam_type_id', $exam_type_id);
            })
            ->when($class_id, function ($q) use ($class_id) {
                return $q->where('class_id', $class_id);
            })
            ->orderBy('id', 'desc')
            ->get();

        $examTypes = ExamType::all();
        $classes = StudentClass::all();

        return view('admin.exam.admit-card.index', compact('admitCards', 'examTypes', 'classes', 'exam_type_id', 'class_id'));
    }

    public function generate($exam_id, $class_id)
    {
        // $year = StudentYear::where('name', date('Y'))->get();
        $year = StudentYear::where('name', 2025)->firstOrFail();

        $students = AssignStudent::with(['student'])
            ->where('class_id', $class_id)
            ->where('year_id', $year->id)
            ->get();

        $examType = ExamType::find($exam_id);
        $class = StudentClass::find($class_id);

        foreach ($students as $key => $student) {
            $existing = ExamAdmitCard::where('student_id', $student->student_id)
                ->where('exam_type_id', $exam_id)
                ->where('class_id', $class_id)
                ->first();

            if (!$existing) {
                ExamAdmitCard::create([
                    'student_id' => $student->student_id,
                    'class_id' => $class_id,
                    'exam_type_id' => $exam_id,
                    'roll_no' => $student->roll ?? ($key + 1),
                    'admit_card_no' => 'ADM-' . date('Y') . '-' . $exam_id . '-' . $student->student_id,
                    'issue_date' => date('Y-m-d'),
                    'status' => 1
                ]);
            }
        }

        return redirect()->back()->with('success', 'Admit cards generated successfully for ' . $class->name . ' - ' . $examType->name);
    }

    public function print($id)
    {
        $admitCard = ExamAdmitCard::with(['student', 'class', 'examType'])->findOrFail($id);
        return view('admin.exam.admit-card.print', compact('admitCard'));
    }


    public function bulkPrint(Request $request)
    {
        $exam_type_id = $request->exam_type_id;
        $class_id = $request->class_id;

        $admitCards = ExamAdmitCard::with(['student', 'class', 'examType'])
            ->where('exam_type_id', $exam_type_id)
            ->where('class_id', $class_id)
            ->get();

        if ($admitCards->isEmpty()) {
            return redirect()->back()->with('error', 'No admit cards found! Please generate admit cards first.');
        }

        return view('admin.exam.admit-card.bulk-print', compact('admitCards'));
    }

    public function destroy($id)
    {
        ExamAdmitCard::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Admit card deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ExamRoutine;
use App\Models\ExamType;
use App\Models\StudentClass;
use App\Models\SchoolSubject;
use Illuminate\Http\Request;

class ExamRoutineController extends Controller
{
    public function index(Request $request)
    {
        $exam_type_id = $request->exam_type_id;
        $class_id = $request->class_id;

        $routines = ExamRoutine::with(['examType', 'class', 'subject'])
            ->when($exam_type_id, function ($q) use ($exam_type_id) {
                return $q->where('exam_type_id', $exam_type_id);
            })
            ->when($class_id, function ($q) use ($class_id) {
                return $q->where('class_id', $class_id);
            })
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        $examTypes = ExamType::all();
        $classes = StudentClass::all();
        $subjects = SchoolSubject::all();

        return view('admin.exam.routine.index', compact('routines', 'examTypes', 'classes', 'subjects', 'exam_type_id', 'class_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_type_id' => 'required',
            'class_id' => 'required',
            'subject_id' => 'required',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        ExamRoutine::create($request->all());
        return redirect()->back()->with('success', 'Exam routine added successfully!');
    }

    public function edit($id)
    {
        return response()->json(ExamRoutine::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $routine = ExamRoutine::findOrFail($id);
        $routine->update($request->all());
        return redirect()->back()->with('success', 'Exam routine updated successfully!');
    }

    public function destroy($id)
    {
        ExamRoutine::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Exam routine deleted successfully!');
    }
}

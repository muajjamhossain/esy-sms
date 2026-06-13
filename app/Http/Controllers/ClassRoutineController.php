<?php

namespace App\Http\Controllers;

use App\Models\ClassRoutine;
use App\Models\StudentClass;
use App\Models\SchoolSubject;
use App\Models\User;
use App\Models\RoutineDay;
use App\Models\ClassTime;
use Illuminate\Http\Request;

class ClassRoutineController extends Controller
{
    public function index(Request $request)
    {
        $class_id = $request->class_id;
        $day_id = $request->day_id;

        $routines = ClassRoutine::with(['class', 'subject', 'teacher', 'routineDay', 'startTime', 'endTime'])
            ->when($class_id, function ($query) use ($class_id) {
                return $query->where('class_id', $class_id);
            })
            ->when($day_id, function ($query) use ($day_id) {
                return $query->where('routine_day_id', $day_id);
            })
            ->orderBy('routine_day_id')
            ->orderBy('start_time_id')
            ->get();

        $classes = StudentClass::all();
        $subjects = SchoolSubject::all();
        $teachers = User::where('usertype', 'Employee')->get();
        $days = RoutineDay::orderBy('day_order')->get();
        $times = ClassTime::orderBy('time_order')->get();

        return view('admin.routine.index', compact(
            'routines',
            'classes',
            'subjects',
            'teachers',
            'days',
            'times',
            'class_id',
            'day_id'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'employee_id' => 'required',
            'routine_day_id' => 'required',
            'start_time_id' => 'required',
            'end_time_id' => 'required',
        ]);

        // Check if time slot already exists
        $exists = ClassRoutine::where('class_id', $request->class_id)
            ->where('routine_day_id', $request->routine_day_id)
            ->where('start_time_id', $request->start_time_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This time slot already has a class!');
        }

        ClassRoutine::create($request->all());
        return redirect()->back()->with('success', 'Class routine added successfully!');
    }

    public function edit($id)
    {
        $routine = ClassRoutine::findOrFail($id);
        return response()->json($routine);
    }

    public function update(Request $request, $id)
    {
        $routine = ClassRoutine::findOrFail($id);
        $routine->update($request->all());
        return redirect()->back()->with('success', 'Class routine updated successfully!');
    }

    public function show($id)
    {
        // return redirect()->route('routine.index');
        $notification = array(
            'message' => 'Class Routine Deleted Successfully',
            'alert-type' => 'info'
        );


        ClassRoutine::findOrFail($id)->delete();
        return redirect()->back()->with($notification);
    }

    public function destroy($id)
    {
        $notification = array(
            'message' => 'Class Routine Deleted Successfully',
            'alert-type' => 'info'
        );


        ClassRoutine::findOrFail($id)->delete();
        return redirect()->back()->with($notification);
    }
}

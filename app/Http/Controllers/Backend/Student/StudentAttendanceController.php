<?php

namespace App\Http\Controllers\Backend\Student;

use PDF;
use App\Models\User;
use App\Models\Designation;
use App\Models\StudentYear;
use App\Models\StudentClass;
use App\Models\StudentGroup;
use App\Models\StudentShift;
use Illuminate\Http\Request;

use App\Models\AssignStudent;

use App\Models\StudentAttendance;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StudentAttendanceController extends Controller
{

    public function AttendanceView(){
        $data['allData'] = StudentAttendance::select('date')->groupBy('date')->get();
    	// $data['allData'] = StudentAttendance::orderBy('id','DESC')->get();
    	return view('backend.student.student_attendance.student_attendance_view',$data);
    }


    public function AttendanceAdd(){
    	$data['classes'] = StudentClass::all();
    	$data['year_id'] = StudentYear::orderBy('id','desc')->first()->id;
    	$data['class_id'] = StudentClass::orderBy('id','asc')->first()->id;
    	$data['students'] = AssignStudent::where('year_id',$data['year_id'])->where('class_id',$data['class_id'])->get();
    	return view('backend.student.student_attendance.student_attendance_add',$data);

    }

    public function StudentClassYearWise(Request $request){
    	$data['classes'] = StudentClass::all();
    	$data['year_id'] = StudentYear::orderBy('id','desc')->first()->id;
    	$data['class_id'] = $request->class_id;
    	$data['students'] = AssignStudent::where('year_id',$data['year_id'])->where('class_id',$request->class_id)->get();
    	return view('backend.student.student_attendance.student_attendance_add',$data);

    }


    public function AttendanceStore(Request $request){
    	StudentAttendance::where('date', date('Y-m-d', strtotime($request->date)))->where('class_id',$request->class_id)->delete();
    	$countstudent = count($request->student_id);
    	for ($i=0; $i <$countstudent ; $i++) {
    		$attend_status = 'attend_status'.$i;
    		$attend = new StudentAttendance();
    		$attend->date = date('Y-m-d',strtotime($request->date));
    		$attend->student_id = $request->student_id[$i];
    		$attend->attend_status = $request->$attend_status;
    		$attend->class_id = $request->class_id;
    		$attend->save();
    	} // end For Loop

 		$notification = array(
    		'message' => 'student Attendace Data Update Successfully',
    		'alert-type' => 'success'
    	);

    	return redirect()->route('student.attendance.view')->with($notification);

    } // end Method



    public function AttendanceEdit($date){
    	$data['editData'] = StudentAttendance::where('date',$date)->get();
    	return view('backend.student.student_attendance.student_attendance_edit',$data);
    }


    public function AttendanceDetails($date){
    	$data['details'] = StudentAttendance::where('date',$date)->get();
    	return view('backend.student.student_attendance.student_attendance_details',$data);

    }



}

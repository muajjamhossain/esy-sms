<?php

namespace App\Http\Controllers;

use App\Models\OnlineClass;
use App\Models\StudentClass;
use App\Models\SchoolSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Zoom;

class OnlineClassController extends Controller
{
    public function index(Request $request)
    {
        $class_id = $request->class_id;

        $onlineClasses = OnlineClass::with(['class', 'subject', 'teacher'])
            ->when($class_id, function ($q) use ($class_id) {
                return $q->where('class_id', $class_id);
            })
            ->orderBy('start_time', 'desc')
            ->get();

        $classes = StudentClass::all();
        $subjects = SchoolSubject::all();
        $teachers = User::where('usertype', 'Employee')->get();

        return view('admin.online-class.index', compact('onlineClasses', 'classes', 'subjects', 'teachers', 'class_id'));
    }

    public function create()
    {
        $classes = StudentClass::all();
        $subjects = SchoolSubject::all();
        $teachers = User::where('usertype', 'Employee')->get();

        return view('admin.online-class.create', compact('classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'topic' => 'required',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:15|max:240',
        ]);

        try {
            // Create meeting via Zoom API
            $meetingData = [
                "topic" => $request->topic,
                "type" => 2,
                "duration" => (int)$request->duration,
                "timezone" => 'Asia/Dhaka',
                "start_time" => date('Y-m-d\TH:i:s', strtotime($request->start_time)),
                "agenda" => $request->description ?? 'Online Class - ' . $request->topic,
                "settings" => [
                    'join_before_host' => true,
                    'host_video' => true,
                    'participant_video' => true,
                    'mute_upon_entry' => true,
                    'waiting_room' => false,
                    'audio' => 'both',
                    'auto_recording' => 'cloud',
                    'approval_type' => 0,
                ],
            ];

            $response = Zoom::createMeeting($meetingData);


            // dd($response);

            if (isset($response['data'])) {
                // Response has 'data' key (like your package returns)
                $meeting = $response['data'];
            } else {
                // Response is direct meeting object
                $meeting = $response;
            }

            // Save to database with correct array keys
            $onlineClass = OnlineClass::create([
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'topic' => $request->topic,
                'description' => $request->description,
                'zoom_meeting_id' => $meeting['id'],
                'join_url' => $meeting['join_url'],
                'start_url' => $meeting['start_url'],
                'password' => $meeting['password'] ?? rand(100000, 999999),
                'start_time' => $request->start_time,
                'duration' => $request->duration,
                'status' => 1
            ]);

            return redirect()->route('online-class.index')->with('success', 'Online class created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Zoom API Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $onlineClass = OnlineClass::findOrFail($id);
        $classes = StudentClass::all();
        $subjects = SchoolSubject::all();
        $teachers = User::where('usertype', 'Employee')->get();

        return view('admin.online-class.edit', compact('onlineClass', 'classes', 'subjects', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $onlineClass = OnlineClass::findOrFail($id);

        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'topic' => 'required',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:15|max:240',
        ]);

        try {
            // Update meeting in Zoom API[citation:1]
            $updateData = [
                "topic" => $request->topic,
                "duration" => $request->duration,
                "start_time" => date('Y-m-d\TH:i:s', strtotime($request->start_time)),
                "agenda" => $request->description,
                "settings" => [
                    'join_before_host' => true,
                    'mute_upon_entry' => true,
                ],
            ];

            Zoom::updateMeeting($onlineClass->zoom_meeting_id, $updateData);

            // Update database
            $onlineClass->update([
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'topic' => $request->topic,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'duration' => $request->duration,
            ]);

            return redirect()->route('online-class.index')->with('success', 'Online class updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Zoom API Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $onlineClass = OnlineClass::findOrFail($id);

        try {
            // Delete meeting from Zoom API[citation:1][citation:6]
            Zoom::deleteMeeting($onlineClass->zoom_meeting_id);

            // Delete from database
            $onlineClass->delete();

            return redirect()->back()->with('success', 'Online class deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Zoom API Error: ' . $e->getMessage());
        }
    }

    public function start($id)
    {
        $onlineClass = OnlineClass::findOrFail($id);
        return redirect($onlineClass->start_url);
    }

    public function join($id)
    {
        $onlineClass = OnlineClass::findOrFail($id);
        return redirect($onlineClass->join_url);
    }

    public function endMeeting($id)
    {
        $onlineClass = OnlineClass::findOrFail($id);

        try {
            Zoom::endMeeting($onlineClass->zoom_meeting_id);
            $onlineClass->update(['status' => 3]); // Completed

            // Auto fetch recordings after ending meeting
            return redirect()->route('recordings.fetch', $onlineClass->id)->with('success', 'Meeting ended and recordings are being fetched!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Zoom API Error: ' . $e->getMessage());
        }
    }

    public function getUpcomingClasses()
    {
        $upcomingClasses = OnlineClass::with(['class', 'subject', 'teacher'])
            ->where('start_time', '>', now())
            ->where('status', 1)
            ->orderBy('start_time', 'asc')
            ->take(10)
            ->get();

        return response()->json($upcomingClasses);
    }

    public function getSubjectsByClass($class_id)
    {
        $subjects = SchoolSubject::whereHas('assignSubjects', function ($q) use ($class_id) {
            $q->where('class_id', $class_id);
        })->get();

        return response()->json($subjects);
    }
}

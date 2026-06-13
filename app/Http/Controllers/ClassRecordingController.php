<?php

namespace App\Http\Controllers;

use App\Models\ClassRecording;
use App\Models\OnlineClass;
use Illuminate\Http\Request;
use Zoom;

class ClassRecordingController extends Controller
{
    // Display all recordings
    public function index(Request $request)
    {
        $class_id = $request->class_id;

        $recordings = ClassRecording::with(['onlineClass.class', 'onlineClass.subject', 'onlineClass.teacher'])
            ->when($class_id, function ($q) use ($class_id) {
                return $q->whereHas('onlineClass', function ($query) use ($class_id) {
                    $query->where('class_id', $class_id);
                });
            })
            ->orderBy('recording_date', 'desc')
            ->paginate(20);

        $classes = \App\Models\StudentClass::all();

        return view('admin.online-class.recordings', compact('recordings', 'classes', 'class_id'));
    }

    // Fetch recordings from Zoom API
    public function fetchRecordings($online_class_id)
    {
        $onlineClass = OnlineClass::findOrFail($online_class_id);

        try {
            $result = Zoom::getMeetingRecordings($onlineClass->zoom_meeting_id);

            if ($result['status'] && isset($result['data']['recording_files']) && count($result['data']['recording_files']) > 0) {

                $response = $result['data'];

                foreach ($response['recording_files'] as $recording) {
                    ClassRecording::create([
                        'online_class_id' => $online_class_id,
                        'recording_url' => $recording['download_url'],
                        'recording_type' => $recording['recording_type'] ?? 'video',
                        'file_size' => $recording['file_size'] ?? null,
                        'duration' => $recording['duration'] ?? null,
                        'recording_date' => now(),
                    ]);
                }

                return redirect()->back()->with('success', 'Recordings fetched successfully!');
            } else {
                return redirect()->back()->with('info', 'No recordings found. Make sure cloud recording is enabled.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Zoom API Error: ' . $e->getMessage());
        }
    }

    // Auto fetch recordings for all completed classes
    public function fetchAllRecordings()
    {
        $completedClasses = OnlineClass::where('status', 3) // Completed
            ->whereDoesntHave('recordings')
            ->get();

        $fetchedCount = 0;

        foreach ($completedClasses as $class) {
            try {
                $result = Zoom::getMeetingRecordings($class->zoom_meeting_id);

                if ($result['status'] && isset($result['data']['recording_files']) && count($result['data']['recording_files']) > 0) {

                    $recordings = $result['data']; // আসল জুম ডাটা

                    foreach ($recordings['recording_files'] as $recording) {
                        ClassRecording::create([
                            'online_class_id' => $class->id,
                            'recording_url' => $recording['download_url'],
                            'recording_type' => $recording['recording_type'] ?? 'video',
                            'file_size' => $recording['file_size'] ?? null,
                            'duration' => $recording['duration'] ?? null,
                            'recording_date' => date('Y-m-d H:i:s', strtotime($recording['recording_start'])),
                        ]);
                    }
                    $fetchedCount++;
                }
            } catch (\Exception $e) {
                // Log error and continue
                continue;
            }
        }

        return redirect()->back()->with('success', "Recordings fetched for {$fetchedCount} classes!");
    }

    // View single recording
    public function show($id)
    {
        $recording = ClassRecording::with(['onlineClass.class', 'onlineClass.subject', 'onlineClass.teacher'])
            ->findOrFail($id);

        return view('admin.online-class.view-recording', compact('recording'));
    }

    // Delete recording
    public function destroy($id)
    {
        $recording = ClassRecording::findOrFail($id);
        $recording->delete();

        return redirect()->back()->with('success', 'Recording deleted successfully!');
    }

    // Get class recordings API for students
    public function getClassRecordings($class_id = null)
    {
        $query = ClassRecording::with(['onlineClass.class', 'onlineClass.subject'])
            ->orderBy('recording_date', 'desc');

        if ($class_id) {
            $query->whereHas('onlineClass', function ($q) use ($class_id) {
                $q->where('class_id', $class_id);
            });
        }

        $recordings = $query->paginate(15);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $recordings
            ]);
        }

        return view('student.recordings', compact('recordings'));
    }
}

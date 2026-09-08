<?php

namespace App\Http\Controllers;

use App\Models\PortalConversation;
use App\Models\PortalMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PortalController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $conversations = PortalConversation::with(['creator', 'participant', 'messages' => function ($query) {
            $query->latest()->limit(1);
        }])
            ->where(function ($query) use ($userId) {
                $query->where('created_by', $userId)->orWhere('participant_id', $userId);
            })
            ->latest('updated_at')
            ->get();

        $unreadCount = PortalMessage::whereNull('read_at')
            ->where('user_id', '!=', $userId)
            ->whereHas('conversation', function ($query) use ($userId) {
                $query->where(function ($participantQuery) use ($userId) {
                    $participantQuery->where('created_by', $userId)->orWhere('participant_id', $userId);
                });
            })
            ->count();

        return view('portal.index', compact('conversations', 'unreadCount'));
    }

    public function create()
    {
        $contacts = User::where('id', '!=', Auth::id())
            ->where(function ($query) {
                $query->whereIn('role', ['Admin', 'Teacher', 'Parent', 'Student', 'Staff', 'Operator'])
                    ->orWhereIn('usertype', ['Admin', 'Teacher', 'Parent', 'Student', 'Staff']);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role', 'usertype']);

        return view('portal.create', compact('contacts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'participant_id' => ['required', 'integer', 'exists:users,id', 'different:' . Auth::id()],
            'subject' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = DB::transaction(function () use ($data) {
            $conversation = PortalConversation::create([
                'created_by' => Auth::id(),
                'participant_id' => $data['participant_id'],
                'subject' => $data['subject'],
            ]);

            $conversation->messages()->create([
                'user_id' => Auth::id(),
                'body' => $data['body'],
            ]);

            return $conversation;
        });

        return redirect()->route('portal.conversations.show', $conversation)->with('message', __('messages.question_sent'));
    }

    public function show(PortalConversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $conversation->load(['creator', 'participant', 'messages.user']);
        PortalMessage::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('portal.show', compact('conversation'));
    }

    public function reply(Request $request, PortalConversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);
        $conversation->messages()->create(['user_id' => Auth::id(), 'body' => $data['body']]);
        $conversation->update(['status' => 'open']);

        return redirect()->route('portal.conversations.show', $conversation)->with('message', __('messages.reply_sent'));
    }

    public function close(PortalConversation $conversation)
    {
        $this->authorizeConversation($conversation);
        $conversation->update(['status' => 'closed']);

        return redirect()->route('portal.index')->with('message', __('messages.conversation_closed'));
    }

    private function authorizeConversation(PortalConversation $conversation)
    {
        abort_unless(
            $conversation->created_by === Auth::id() || $conversation->participant_id === Auth::id(),
            403
        );
    }
}

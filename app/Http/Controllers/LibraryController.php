<?php

namespace App\Http\Controllers;

use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $books = LibraryBook::orderBy('title')->get();
        $loans = LibraryLoan::with(['book', 'borrower'])
            ->when($this->isStudent($user), function ($query) use ($user) {
                $query->where('borrower_id', $user->id);
            })
            ->whereNull('returned_at')
            ->orderBy('due_at')
            ->get();

        return view('library.index', compact('books', 'loans'));
    }

    public function create()
    {
        abort_unless($this->canManage(Auth::user()), 403);
        $borrowers = User::whereIn('usertype', ['Student', 'Employee', 'Teacher', 'Staff'])
            ->orderBy('name')->get(['id', 'name', 'email']);

        return view('library.create', compact('borrowers'));
    }

    public function store(Request $request)
    {
        abort_unless($this->canManage(Auth::user()), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'author' => ['nullable', 'string', 'max:180'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'total_copies' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);
        $data['available_copies'] = $data['total_copies'];
        LibraryBook::create($data);

        return redirect()->route('library.index')->with('message', __('messages.book_created'));
    }

    public function issue(Request $request)
    {
        abort_unless($this->canManage(Auth::user()), 403);

        $data = $request->validate([
            'book_id' => ['required', 'exists:library_books,id'],
            'borrower_id' => ['required', 'exists:users,id'],
            'due_at' => ['required', 'date', 'after_or_equal:today'],
        ]);

        DB::transaction(function () use ($data) {
            $book = LibraryBook::lockForUpdate()->findOrFail($data['book_id']);
            abort_if($book->available_copies < 1, 422, __('messages.book_unavailable'));
            $book->decrement('available_copies');
            LibraryLoan::create([
                'book_id' => $book->id,
                'borrower_id' => $data['borrower_id'],
                'issued_by' => Auth::id(),
                'issued_at' => today(),
                'due_at' => $data['due_at'],
            ]);
        });

        return redirect()->route('library.index')->with('message', __('messages.book_issued'));
    }

    public function return(LibraryLoan $loan)
    {
        abort_unless($this->canManage(Auth::user()), 403);
        abort_if($loan->returned_at, 422, __('messages.book_already_returned'));

        DB::transaction(function () use ($loan) {
            $loan->update(['returned_at' => today()]);
            $loan->book()->increment('available_copies');
        });

        return redirect()->route('library.index')->with('message', __('messages.book_returned'));
    }

    private function isStudent($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'student' || strtolower((string) ($user->role ?? '')) === 'student';
    }

    private function canManage($user)
    {
        return in_array(strtolower((string) ($user->usertype ?? '')), ['admin', 'employee', 'teacher', 'staff'], true)
            || in_array(strtolower((string) ($user->role ?? '')), ['admin', 'employee', 'teacher', 'staff'], true);
    }
}

@extends('library.layout')

@section('library')
<div class="mb-20"><h2 class="text-white mb-5">{{ __('messages.library') }}</h2><p class="text-fade">{{ __('messages.library_help') }}</p></div>
<div class="row">
    <div class="col-lg-7">
        <div class="library-card p-20 mb-20">
            <h4 class="text-white">{{ __('messages.book_catalog') }}</h4>
            <div class="table-responsive"><table class="table table-dark table-hover mb-0"><thead><tr><th>{{ __('messages.title') }}</th><th>{{ __('messages.author') }}</th><th>{{ __('messages.available') }}</th></tr></thead><tbody>
            @forelse($books as $book)<tr><td>{{ $book->title }} @if($book->category)<small class="text-fade d-block">{{ $book->category }}</small>@endif</td><td>{{ $book->author ?: '-' }}</td><td>{{ $book->available_copies }}/{{ $book->total_copies }}</td></tr>@empty<tr><td colspan="3">{{ __('messages.no_books') }}</td></tr>@endforelse
            </tbody></table></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="library-card p-20 mb-20"><h4 class="text-white">{{ __('messages.active_loans') }}</h4>
            @forelse($loans as $loan)<div class="border-bottom border-secondary py-10"><strong>{{ $loan->book->title }}</strong><small class="d-block text-fade">{{ $loan->borrower->name }} · {{ __('messages.due') }}: {{ $loan->due_at->format('d M Y') }}</small>@if(!auth()->user()->id || !($loan->borrower_id === auth()->id() && strtolower((string)auth()->user()->usertype) === 'student'))<form method="POST" action="{{ route('library.loans.return', $loan) }}" class="mt-5">@csrf<button class="btn btn-xs btn-outline-success">{{ __('messages.return_book') }}</button></form>@endif</div>@empty<p class="text-fade">{{ __('messages.no_active_loans') }}</p>@endforelse
        </div>
    </div>
</div>
@endsection

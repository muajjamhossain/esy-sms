@extends('library.layout')

@section('library')
<div class="row">
    <div class="col-lg-6"><div class="library-card p-25 mb-20"><h3 class="text-white mb-20">{{ __('messages.add_book') }}</h3><form method="POST" action="{{ route('library.books.store') }}">@csrf
        <div class="form-group"><label>{{ __('messages.title') }}</label><input name="title" class="form-control" required></div>
        <div class="form-group"><label>{{ __('messages.author') }}</label><input name="author" class="form-control"></div>
        <div class="form-group"><label>{{ __('messages.isbn') }}</label><input name="isbn" class="form-control"></div>
        <div class="form-group"><label>{{ __('messages.category') }}</label><input name="category" class="form-control"></div>
        <div class="form-group"><label>{{ __('messages.total_copies') }}</label><input type="number" min="1" name="total_copies" class="form-control" value="1" required></div>
        <button class="btn btn-primary">{{ __('messages.save_book') }}</button>
    </form></div></div>
    <div class="col-lg-6"><div class="library-card p-25 mb-20"><h3 class="text-white mb-20">{{ __('messages.issue_book') }}</h3><form method="POST" action="{{ route('library.loans.issue') }}">@csrf
        <div class="form-group"><label>{{ __('messages.book') }}</label><select name="book_id" class="form-control" required>@foreach(\App\Models\LibraryBook::where('available_copies','>',0)->orderBy('title')->get() as $book)<option value="{{ $book->id }}">{{ $book->title }} ({{ $book->available_copies }})</option>@endforeach</select></div>
        <div class="form-group"><label>{{ __('messages.borrower') }}</label><select name="borrower_id" class="form-control" required>@foreach($borrowers as $borrower)<option value="{{ $borrower->id }}">{{ $borrower->name }} ({{ $borrower->email }})</option>@endforeach</select></div>
        <div class="form-group"><label>{{ __('messages.due_date') }}</label><input type="date" name="due_at" class="form-control" required></div>
        <button class="btn btn-primary">{{ __('messages.issue_book') }}</button>
    </form></div></div>
</div>
@endsection

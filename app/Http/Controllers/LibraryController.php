<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LibraryController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('title')->get();
        $students = Student::orderBy('full_name')->get();
        $records = BorrowRecord::with(['book', 'student'])->whereNull('returned_at')->latest()->get();

        return view('library.index', compact('books', 'students', 'records'));
    }

    // Issue a book to a student
    public function issueBook(Request $request)
    {
        $book = Book::findOrFail($request->book_id);

        // 1. Check availability
        if ($book->available_copies <= 0) {
            return back()->with('error', 'Book is currently unavailable.');
        }

        // 2. Create borrow record
        BorrowRecord::create([
            'student_id' => $request->student_id,
            'book_id' => $book->id,
            'borrowed_at' => Carbon::now(),
            'due_at' => Carbon::now()->addWeeks(2), // 2-week loan period
        ]);

        // 3. Decrease available copies
        $book->decrement('available_copies');

        return back()->with('success', 'Book issued successfully.');
    }

    // Return a book
    public function returnBook($recordId)
    {
        $record = BorrowRecord::findOrFail($recordId);
        $book = Book::find($record->book_id);

        // 1. Mark as returned
        $record->update(['returned_at' => Carbon::now()]);

        // 2. Increase available copies
        $book->increment('available_copies');

        return back()->with('success', 'Book returned successfully.');
    }
}

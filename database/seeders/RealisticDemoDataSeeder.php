<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RealisticDemoDataSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $password = Hash::make('password123');

        $classes = [];
        foreach (['Play', 'One', 'Two', 'Three', 'Four', 'Five'] as $name) {
            $classes[$name] = DB::table('student_classes')->updateOrInsert(
                ['name' => $name],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }

        $classIds = DB::table('student_classes')->whereIn('name', array_keys($classes))->pluck('id', 'name');
        $yearId = DB::table('student_years')->where('name', '2026')->value('id');
        if (! $yearId) {
            $yearId = DB::table('student_years')->insertGetId(['name' => '2026', 'created_at' => $now]);
        }

        $subjectIds = [];
        foreach (['Bangla', 'English', 'Mathematics', 'Science', 'Social Science'] as $name) {
            DB::table('school_subjects')->updateOrInsert(
                ['name' => $name],
                ['created_at' => $now]
            );
            $subjectIds[$name] = DB::table('school_subjects')->where('name', $name)->value('id');
        }

        $users = [
            ['name' => 'Nusrat Jahan', 'email' => 'principal@demo.school', 'usertype' => 'Admin', 'role' => 'admin'],
            ['name' => 'Tanvir Hossain', 'email' => 'tanvir.hossain@demo.school', 'usertype' => 'Employee', 'role' => 'teacher'],
            ['name' => 'Samia Rahman', 'email' => 'samia.rahman@demo.school', 'usertype' => 'Employee', 'role' => 'teacher'],
            ['name' => 'Imran Kabir', 'email' => 'imran.kabir@demo.school', 'usertype' => 'Employee', 'role' => 'staff'],
            ['name' => 'Arif Mahmud', 'email' => 'arif.mahmud@student.demo.school', 'usertype' => 'Student', 'role' => 'student'],
            ['name' => 'Maliha Chowdhury', 'email' => 'maliha.chowdhury@student.demo.school', 'usertype' => 'Student', 'role' => 'student'],
            ['name' => 'Rafi Ahmed', 'email' => 'rafi.ahmed@student.demo.school', 'usertype' => 'Student', 'role' => 'student'],
            ['name' => 'Farzana Akter', 'email' => 'farzana.akter@student.demo.school', 'usertype' => 'Student', 'role' => 'student'],
            ['name' => 'Abdullah Al Mamun', 'email' => 'abdullah.mamun@parent.demo.school', 'usertype' => 'Parent', 'role' => 'parent'],
        ];

        $userIds = [];
        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $password,
                    'usertype' => $user['usertype'],
                    'role' => $user['role'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
            $userIds[$user['email']] = DB::table('users')->where('email', $user['email'])->value('id');
        }

        $studentEmails = [
            'arif.mahmud@student.demo.school',
            'maliha.chowdhury@student.demo.school',
            'rafi.ahmed@student.demo.school',
            'farzana.akter@student.demo.school',
        ];
        foreach ($studentEmails as $index => $email) {
            $studentId = $userIds[$email];
            $classId = $classIds[['Three', 'Four', 'Five', 'Three'][$index]];
            DB::table('assign_students')->updateOrInsert(
                ['student_id' => $studentId, 'year_id' => $yearId],
                ['roll' => $index + 1, 'class_id' => $classId, 'shift_id' => null, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $adminId = $userIds['principal@demo.school'];
        $teacherId = $userIds['tanvir.hossain@demo.school'];
        $secondTeacherId = $userIds['samia.rahman@demo.school'];
        $studentId = $userIds['arif.mahmud@student.demo.school'];

        $assignmentId = DB::table('assignments')->where('title', 'Fractions in Everyday Life')->value('id');
        if (! $assignmentId) {
            $assignmentId = DB::table('assignments')->insertGetId([
                'created_by' => $teacherId,
                'class_id' => $classIds['Three'],
                'subject_id' => $subjectIds['Mathematics'],
                'title' => 'Fractions in Everyday Life',
                'instructions' => 'Find three examples of fractions at home and explain each example in your own words.',
                'due_date' => now()->addDays(10)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        DB::table('assignment_submissions')->updateOrInsert(
            ['assignment_id' => $assignmentId, 'student_id' => $studentId],
            [
                'answer' => 'A half glass of water, quarter kilo of rice, and three-fourths of a chocolate bar.',
                'marks' => 8.50,
                'feedback' => 'Good examples. Add a small drawing next time.',
                'submitted_at' => now()->subDay(),
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $notices = [
            ['title' => 'Parent-Teacher Meeting: October Schedule', 'body' => 'Parents are invited to meet class teachers on 10 October from 10:00 AM to 1:00 PM.', 'audience' => 'everyone'],
            ['title' => 'Science Fair Registration Open', 'body' => 'Students of Classes Three to Five can register their projects with the Science Department by 25 September.', 'audience' => 'student'],
        ];
        foreach ($notices as $notice) {
            DB::table('notices')->updateOrInsert(
                ['title' => $notice['title']],
                array_merge($notice, ['created_by' => $adminId, 'published_at' => now()->subDay(), 'updated_at' => $now, 'created_at' => $now])
            );
        }

        $events = [
            ['title' => 'Annual Sports Day', 'type' => 'event', 'description' => 'A day of athletics, team games and cultural performances.', 'location' => 'School Playground', 'starts_at' => now()->addDays(14)->setTime(9, 0), 'ends_at' => now()->addDays(14)->setTime(15, 0)],
            ['title' => 'Mid-Term Assessment', 'type' => 'exam', 'description' => 'Mid-term assessment week for all primary classes.', 'location' => 'Respective Classrooms', 'starts_at' => now()->addDays(25)->setTime(10, 0), 'ends_at' => now()->addDays(29)->setTime(13, 0)],
        ];
        foreach ($events as $event) {
            DB::table('institution_events')->updateOrInsert(
                ['title' => $event['title']],
                array_merge($event, ['created_by' => $adminId, 'audience' => 'everyone', 'updated_at' => $now, 'created_at' => $now])
            );
        }

        $books = [
            ['title' => 'The Story of Bangladesh', 'author' => 'M. A. Hasan', 'isbn' => '9789840001001', 'category' => 'History', 'total_copies' => 4],
            ['title' => 'Oxford Primary Mathematics 3', 'author' => 'Emma Low', 'isbn' => '9780190002032', 'category' => 'Mathematics', 'total_copies' => 6],
            ['title' => 'Our Green Planet', 'author' => 'Sabrina Karim', 'isbn' => '9789840003040', 'category' => 'Science', 'total_copies' => 3],
        ];
        foreach ($books as $book) {
            $existingBook = DB::table('library_books')->where('isbn', $book['isbn'])->first();
            if (! $existingBook) {
                DB::table('library_books')->insert(array_merge($book, [
                    'available_copies' => $book['total_copies'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]));
            } else {
                DB::table('library_books')->where('id', $existingBook->id)->update(array_merge($book, ['updated_at' => $now]));
            }
        }
        $bookId = DB::table('library_books')->where('isbn', '9780190002032')->value('id');
        if (! DB::table('library_loans')->where('book_id', $bookId)->where('borrower_id', $studentId)->whereNull('returned_at')->exists()) {
            DB::table('library_books')->where('id', $bookId)->decrement('available_copies');
            DB::table('library_loans')->insert([
                'book_id' => $bookId,
                'borrower_id' => $studentId,
                'issued_by' => $adminId,
                'issued_at' => now()->subDays(3)->toDateString(),
                'due_at' => now()->addDays(11)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $conversationId = DB::table('portal_conversations')->where('subject', 'Request for extra reading materials')->value('id');
        if (! $conversationId) {
            $conversationId = DB::table('portal_conversations')->insertGetId([
                'created_by' => $studentId,
                'participant_id' => $secondTeacherId,
                'subject' => 'Request for extra reading materials',
                'status' => 'open',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('portal_messages')->insert([
                [
                    'conversation_id' => $conversationId,
                    'user_id'         => $studentId,
                    'body'            => 'Could you recommend a few story books for our reading practice?',
                    'read_at'         => null,
                    'created_at'      => now()->subHours(4),
                    'updated_at'      => now()->subHours(4),
                ],
                [
                    'conversation_id' => $conversationId,
                    'user_id'         => $secondTeacherId,
                    'body'            => 'Please start with the three books listed in the library. I will share more titles tomorrow.',
                    'read_at'         => now()->subHours(2),
                    'created_at'      => now()->subHours(2),
                    'updated_at'      => now()->subHours(2),
                ],
            ]);
        }

        $this->command->info('Fateha School/Madrasa demo data seeded successfully.');
        $this->command->info('Demo login password for all accounts: password123');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SchoolDemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Student Classes
        $classes = ['Play', 'One', 'Two', 'Three', 'Four', 'Five'];
        foreach ($classes as $class) {
            DB::table('student_classes')->insert(['name' => $class, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 2. Years
        DB::table('student_years')->insert([
            ['name' => '2024', 'created_at' => now()],
            ['name' => '2025', 'created_at' => now()],
        ]);

        // 3. Groups
        DB::table('student_groups')->insert([
            ['name' => 'Science', 'created_at' => now()],
            ['name' => 'Arts', 'created_at' => now()],
            ['name' => 'Commerce', 'created_at' => now()],
        ]);

        // 4. Shifts
        DB::table('student_shifts')->insert([
            ['name' => 'Morning', 'created_at' => now()],
            ['name' => 'Day', 'created_at' => now()],
        ]);

        // 5. Designations
        DB::table('designations')->insert([
            ['name' => 'Teacher', 'created_at' => now()],
            ['name' => 'Accountant', 'created_at' => now()],
            ['name' => 'Librarian', 'created_at' => now()],
            ['name' => 'Receptionist', 'created_at' => now()],
            ['name' => 'Principal', 'created_at' => now()],
            ['name' => 'Assistant Teacher', 'created_at' => now()],
        ]);

        // 6. Fee Categories
        DB::table('fee_categories')->insert([
            ['name' => 'Monthly Fee', 'created_at' => now()],
            ['name' => 'Exam Fee', 'created_at' => now()],
            ['name' => 'Annual Fee', 'created_at' => now()],
            ['name' => 'Sports Fee', 'created_at' => now()],
            ['name' => 'Library Fee', 'created_at' => now()],
        ]);

        // 7. Exam Types
        DB::table('exam_types')->insert([
            ['name' => 'First Term', 'created_at' => now()],
            ['name' => 'Second Term', 'created_at' => now()],
            ['name' => 'Final Exam', 'created_at' => now()],
        ]);

        // 8. Subjects
        DB::table('school_subjects')->insert([
            ['name' => 'Bangla', 'created_at' => now()],
            ['name' => 'English', 'created_at' => now()],
            ['name' => 'Mathematics', 'created_at' => now()],
            ['name' => 'Science', 'created_at' => now()],
            ['name' => 'Social Science', 'created_at' => now()],
            ['name' => 'Religion', 'created_at' => now()],
        ]);

        // 9. Marks Grade
        DB::table('marks_grades')->insert([
            ['grade_name' => 'A+', 'grade_point' => '5.00', 'start_marks' => '80', 'end_marks' => '100', 'start_point' => '5.00', 'end_point' => '5.00', 'remarks' => 'Excellent', 'created_at' => now()],
            ['grade_name' => 'A', 'grade_point' => '4.00', 'start_marks' => '70', 'end_marks' => '79', 'start_point' => '4.00', 'end_point' => '4.99', 'remarks' => 'Very Good', 'created_at' => now()],
            ['grade_name' => 'B', 'grade_point' => '3.00', 'start_marks' => '60', 'end_marks' => '69', 'start_point' => '3.00', 'end_point' => '3.99', 'remarks' => 'Good', 'created_at' => now()],
            ['grade_name' => 'C', 'grade_point' => '2.00', 'start_marks' => '50', 'end_marks' => '59', 'start_point' => '2.00', 'end_point' => '2.99', 'remarks' => 'Average', 'created_at' => now()],
            ['grade_name' => 'D', 'grade_point' => '1.00', 'start_marks' => '40', 'end_marks' => '49', 'start_point' => '1.00', 'end_point' => '1.99', 'remarks' => 'Pass', 'created_at' => now()],
            ['grade_name' => 'F', 'grade_point' => '0.00', 'start_marks' => '0', 'end_marks' => '39', 'start_point' => '0.00', 'end_point' => '0.99', 'remarks' => 'Fail', 'created_at' => now()],
        ]);

        // 10. Users (Admin, Employees, Students)
        // Admin
        DB::table('users')->insert([
            'usertype' => 'Admin',
            'name' => 'School Admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password123'),
            'mobile' => '01711111111',
            'address' => 'Dhaka',
            'gender' => 'Male',
            'role' => 'admin',
            'status' => 1,
            'created_at' => now(),
        ]);

        // Employees
        DB::table('users')->insert([
            [
                'usertype' => 'Employee',
                'name' => 'Rafiqul Islam',
                'email' => 'teacher1@school.com',
                'password' => Hash::make('password123'),
                'mobile' => '01712222222',
                'address' => 'Dhaka',
                'gender' => 'Male',
                'designation_id' => 1,
                'salary' => 25000,
                'join_date' => '2023-01-01',
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'usertype' => 'Employee',
                'name' => 'Fatema Begum',
                'email' => 'teacher2@school.com',
                'password' => Hash::make('password123'),
                'mobile' => '01713333333',
                'address' => 'Dhaka',
                'gender' => 'Female',
                'designation_id' => 6,
                'salary' => 20000,
                'join_date' => '2023-06-01',
                'status' => 1,
                'created_at' => now(),
            ],
        ]);

        // Students
        DB::table('users')->insert([
            [
                'usertype' => 'Student',
                'name' => 'Rohan Ahmed',
                'email' => 'student1@school.com',
                'password' => Hash::make('password123'),
                'mobile' => '01715555555',
                'address' => 'Dhaka',
                'gender' => 'Male',
                'fname' => 'Mr. Ahmed',
                'mname' => 'Mrs. Ahmed',
                'dob' => '2016-05-10',
                'id_no' => '2024001',
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'usertype' => 'Student',
                'name' => 'Sumaiya Akter',
                'email' => 'student2@school.com',
                'password' => Hash::make('password123'),
                'mobile' => '01716666666',
                'address' => 'Dhaka',
                'gender' => 'Female',
                'fname' => 'Mr. Rahman',
                'mname' => 'Mrs. Rahman',
                'dob' => '2015-08-22',
                'id_no' => '2024002',
                'status' => 1,
                'created_at' => now(),
            ],
        ]);

        // 11. Assign Students (Enrollment)
        DB::table('assign_students')->insert([
            ['student_id' => 5, 'roll' => 1, 'class_id' => 1, 'year_id' => 1, 'shift_id' => 1, 'created_at' => now()],
            ['student_id' => 6, 'roll' => 2, 'class_id' => 1, 'year_id' => 1, 'shift_id' => 1, 'created_at' => now()],
        ]);

        // 12. Assign Subjects
        DB::table('assign_subjects')->insert([
            ['class_id' => 1, 'subject_id' => 1, 'full_mark' => 100, 'pass_mark' => 40, 'subjective_mark' => 30, 'created_at' => now()],
            ['class_id' => 1, 'subject_id' => 2, 'full_mark' => 100, 'pass_mark' => 40, 'subjective_mark' => 30, 'created_at' => now()],
            ['class_id' => 1, 'subject_id' => 3, 'full_mark' => 100, 'pass_mark' => 40, 'subjective_mark' => 30, 'created_at' => now()],
        ]);

        // 13. Student Marks
        DB::table('student_marks')->insert([
            ['student_id' => 5, 'id_no' => '2024001', 'year_id' => 1, 'class_id' => 1, 'assign_subject_id' => 1, 'exam_type_id' => 1, 'marks' => 85, 'created_at' => now()],
            ['student_id' => 5, 'id_no' => '2024001', 'year_id' => 1, 'class_id' => 1, 'assign_subject_id' => 2, 'exam_type_id' => 1, 'marks' => 78, 'created_at' => now()],
            ['student_id' => 5, 'id_no' => '2024001', 'year_id' => 1, 'class_id' => 1, 'assign_subject_id' => 3, 'exam_type_id' => 1, 'marks' => 92, 'created_at' => now()],
        ]);

        $this->command->info('Demo data seeded successfully!');
    }
}

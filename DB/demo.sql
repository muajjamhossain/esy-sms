-- ======================================================
-- 1. প্রথমে Basic Setup (Classes, Years, Groups, Shifts ইত্যাদি)
-- ======================================================

-- Student Classes (শ্রেণি)
INSERT INTO `student_classes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Play', NOW(), NOW()),
(2, 'One', NOW(), NOW()),
(3, 'Two', NOW(), NOW()),
(4, 'Three', NOW(), NOW()),
(5, 'Four', NOW(), NOW()),
(6, 'Five', NOW(), NOW());

-- Student Years (একাডেমিক বছর)
INSERT INTO `student_years` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '2024', NOW(), NOW()),
(2, '2025', NOW(), NOW());

-- Student Groups (শুধু 3-5 শ্রেণির জন্য)
INSERT INTO `student_groups` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Science', NOW(), NOW()),
(2, 'Arts', NOW(), NOW()),
(3, 'Commerce', NOW(), NOW());

-- Student Shifts
INSERT INTO `student_shifts` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Morning', NOW(), NOW()),
(2, 'Day', NOW(), NOW());

-- Designations (পদের নাম)
INSERT INTO `designations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Teacher', NOW(), NOW()),
(2, 'Accountant', NOW(), NOW()),
(3, 'Librarian', NOW(), NOW()),
(4, 'Receptionist', NOW(), NOW()),
(5, 'Principal', NOW(), NOW()),
(6, 'Assistant Teacher', NOW(), NOW());

-- Fee Categories (ফি এর ক্যাটাগরি)
INSERT INTO `fee_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Monthly Fee', NOW(), NOW()),
(2, 'Exam Fee', NOW(), NOW()),
(3, 'Annual Fee', NOW(), NOW()),
(4, 'Sports Fee', NOW(), NOW()),
(5, 'Library Fee', NOW(), NOW());

-- Exam Types (পরীক্ষার ধরন)
INSERT INTO `exam_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'First Term', NOW(), NOW()),
(2, 'Second Term', NOW(), NOW()),
(3, 'Final Exam', NOW(), NOW());

-- School Subjects (বিষয়)
INSERT INTO `school_subjects` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Bangla', NOW(), NOW()),
(2, 'English', NOW(), NOW()),
(3, 'Mathematics', NOW(), NOW()),
(4, 'Science', NOW(), NOW()),
(5, 'Social Science', NOW(), NOW()),
(6, 'Religion', NOW(), NOW());

-- Marks Grade
INSERT INTO `marks_grades` (`id`, `grade_name`, `grade_point`, `start_marks`, `end_marks`, `start_point`, `end_point`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'A+', '5.00', '80', '100', '5.00', '5.00', 'Excellent', NOW(), NOW()),
(2, 'A', '4.00', '70', '79', '4.00', '4.99', 'Very Good', NOW(), NOW()),
(3, 'B', '3.00', '60', '69', '3.00', '3.99', 'Good', NOW(), NOW()),
(4, 'C', '2.00', '50', '59', '2.00', '2.99', 'Average', NOW(), NOW()),
(5, 'D', '1.00', '40', '49', '1.00', '1.99', 'Pass', NOW(), NOW()),
(6, 'F', '0.00', '0', '39', '0.00', '0.99', 'Fail', NOW(), NOW());

-- Leave Purposes
INSERT INTO `leave_purposes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Sick Leave', NOW(), NOW()),
(2, 'Casual Leave', NOW(), NOW()),
(3, 'Emergency Leave', NOW(), NOW());

-- ======================================================
-- 2. Users (Student, Employee, Admin)
-- পাসওয়ার্ড সব জায়গায়: password123
-- ======================================================

-- Admin User
INSERT INTO `users` (`id`, `usertype`, `name`, `email`, `password`, `mobile`, `address`, `gender`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'School Admin', 'admin@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01711111111', 'Dhaka', 'Male', 'admin', 1, NOW(), NOW());

-- Employee Users (পদ অনুযায়ী)
INSERT INTO `users` (`id`, `usertype`, `name`, `email`, `password`, `mobile`, `address`, `gender`, `designation_id`, `salary`, `join_date`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Employee', 'Rafiqul Islam', 'teacher1@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01712222222', 'Dhaka', 'Male', 1, 25000, '2023-01-01', 1, NOW(), NOW()),
(3, 'Employee', 'Fatema Begum', 'teacher2@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01713333333', 'Dhaka', 'Female', 6, 20000, '2023-06-01', 1, NOW(), NOW()),
(4, 'Employee', 'Karim Uddin', 'accountant@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01714444444', 'Dhaka', 'Male', 2, 22000, '2022-09-01', 1, NOW(), NOW());

-- Student Users (ছাত্রছাত্রী)
INSERT INTO `users` (`id`, `usertype`, `name`, `email`, `password`, `mobile`, `address`, `gender`, `fname`, `mname`, `dob`, `id_no`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Student', 'Rohan Ahmed', 'student1@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01715555555', 'Dhaka', 'Male', 'Mr. Ahmed', 'Mrs. Ahmed', '2016-05-10', '2024001', 1, NOW(), NOW()),
(6, 'Student', 'Sumaiya Akter', 'student2@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01716666666', 'Dhaka', 'Female', 'Mr. Rahman', 'Mrs. Rahman', '2015-08-22', '2024002', 1, NOW(), NOW()),
(7, 'Student', 'Tanvir Hasan', 'student3@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01717777777', 'Dhaka', 'Male', 'Mr. Hasan', 'Mrs. Hasan', '2017-01-15', '2024003', 1, NOW(), NOW());

-- ======================================================
-- 3. Student Enrollment (Assign Students)
-- ======================================================

INSERT INTO `assign_students` (`id`, `student_id`, `roll`, `class_id`, `year_id`, `group_id`, `shift_id`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 1, 1, NULL, 1, NOW(), NOW()),  -- Play, Morning
(2, 6, 2, 1, 1, NULL, 1, NOW(), NOW()),
(3, 7, 3, 2, 1, NULL, 2, NOW(), NOW());  -- Class One, Day

-- Discount for Students
INSERT INTO `discount_students` (`id`, `assign_student_id`, `fee_category_id`, `discount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10.00, NOW(), NOW()),  -- Rohan 10% discount on Monthly Fee
(2, 2, 1, 5.00, NOW(), NOW());

-- ======================================================
-- 4. Assign Subjects (কোন শ্রেণিতে কোন বিষয় পড়ানো হয়)
-- ======================================================

INSERT INTO `assign_subjects` (`id`, `class_id`, `subject_id`, `full_mark`, `pass_mark`, `subjective_mark`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100, 40, 30, NOW(), NOW()),
(2, 1, 2, 100, 40, 30, NOW(), NOW()),
(3, 1, 3, 100, 40, 30, NOW(), NOW()),
(4, 2, 1, 100, 40, 30, NOW(), NOW()),
(5, 2, 2, 100, 40, 30, NOW(), NOW()),
(6, 2, 3, 100, 40, 30, NOW(), NOW());

-- ======================================================
-- 5. Employee Salary & Leave Logs
-- ======================================================

-- Employee Salary Logs (ইতিহাস)
INSERT INTO `employee_sallary_logs` (`id`, `employee_id`, `previous_salary`, `present_salary`, `increment_salary`, `effected_salary`, `created_at`, `updated_at`) VALUES
(1, 2, 23000, 25000, 2000, '2024-01-01', NOW(), NOW()),
(2, 3, 18000, 20000, 2000, '2024-01-01', NOW(), NOW()),
(3, 4, 20000, 22000, 2000, '2023-01-01', NOW(), NOW());

-- Employee Leaves
INSERT INTO `employee_leaves` (`id`, `employee_id`, `leave_purpose_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2024-03-10', '2024-03-12', NOW(), NOW()),
(2, 3, 2, '2024-04-05', '2024-04-06', NOW(), NOW());

-- Employee Attendance (আজকের উপস্থিতি)
INSERT INTO `employee_attendances` (`id`, `employee_id`, `date`, `attend_status`, `created_at`, `updated_at`) VALUES
(1, 2, CURDATE(), 'Present', NOW(), NOW()),
(2, 3, CURDATE(), 'Present', NOW(), NOW()),
(3, 4, CURDATE(), 'Absent', NOW(), NOW());

-- ======================================================
-- 6. Student Marks (মার্কশীটের জন্য ডেমো)
-- ======================================================

INSERT INTO `student_marks` (`id`, `student_id`, `id_no`, `year_id`, `class_id`, `assign_subject_id`, `exam_type_id`, `marks`, `created_at`, `updated_at`) VALUES
(1, 5, '2024001', 1, 1, 1, 1, 85, NOW(), NOW()),  -- Rohan: Bangla 85
(2, 5, '2024001', 1, 1, 2, 1, 78, NOW(), NOW()),  -- Rohan: English 78
(3, 5, '2024001', 1, 1, 3, 1, 92, NOW(), NOW()),  -- Rohan: Math 92
(4, 6, '2024002', 1, 1, 1, 1, 65, NOW(), NOW()),
(5, 6, '2024002', 1, 1, 2, 1, 72, NOW(), NOW()),
(6, 7, '2024003', 1, 2, 4, 1, 58, NOW(), NOW());

-- ======================================================
-- 7. Accounting Data (ফি এবং খরচ)
-- ======================================================

-- Fee Category Amount (শ্রেণি ভিত্তিক ফির পরিমাণ)
INSERT INTO `fee_category_amounts` (`id`, `fee_category_id`, `class_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 500, NOW(), NOW()), -- Play Monthly Fee: 500
(2, 1, 2, 600, NOW(), NOW()), -- One Monthly Fee: 600
(3, 2, 1, 200, NOW(), NOW()); -- Exam Fee for Play: 200

-- Student Fees Collected (ছাত্রের ফি জমা)
INSERT INTO `account_student_fees` (`id`, `year_id`, `class_id`, `student_id`, `fee_category_id`, `date`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 1, '2024-01-01', 450, NOW(), NOW()), -- 10% discount applied manually
(2, 1, 1, 6, 1, '2024-01-01', 475, NOW(), NOW()); -- 5% discount

-- Employee Salaries Paid (কর্মচারীদের বেতন পরিশোধ)
INSERT INTO `account_employee_salaries` (`id`, `employee_id`, `date`, `amount`, `created_at`, `updated_at`) VALUES
(1, 2, '2024-01-01', 25000, NOW(), NOW()),
(2, 3, '2024-01-01', 20000, NOW(), NOW());

-- Other Costs (বিদ্যালয়ের অন্যান্য খরচ)
INSERT INTO `account_other_costs` (`id`, `date`, `amount`, `description`, `created_at`, `updated_at`) VALUES
(1, '2024-01-15', 5000, 'Office Stationery Purchase', NOW(), NOW()),
(2, '2024-01-20', 20000, 'Sports Equipment', NOW(), NOW());

COMMIT;

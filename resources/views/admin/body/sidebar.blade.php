@php
$prefix = Request::route()->getPrefix();
$route = Route::current()->getName();
$currentRole = strtolower((string) (Auth::user()->role ?: Auth::user()->usertype));

@endphp


<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar">

        <div class="user-profile">
            <div class="ulogo">
                <a href="{{ url('/dashboard') }}">
                    <!-- logo for regular state and mobile devices -->
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="{{asset('backend/images/logo-dark.png')}}" alt="">
                        <h3><b>SMS</b> Admin</h3>
                    </div>
                </a>
            </div>
        </div>

        <!-- sidebar menu-->
        <ul class="sidebar-menu" data-widget="tree">

            <li class="{{ ($route == 'dashboard')?'active':'' }}">
                <a href="{{ route('dashboard') }}">
                    <i data-feather="pie-chart"></i>
                    <span>{{ __('messages.dashboard') }}</span>
                </a>
            </li>

            <li class="treeview {{ in_array($route, ['portal.index', 'assignments.index', 'assignments.create', 'assignments.show', 'notices.index', 'notices.create', 'notices.show', 'events.index', 'events.create', 'events.show', 'library.index', 'library.create'], true) ? 'active' : '' }}">
                <a href="#">
                    <i data-feather="layers"></i> <span>{{ __('messages.institution_services') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ in_array($route, ['portal.index'], true) ? 'active' : '' }}"><a href="{{ route('portal.index') }}"><i class="ti-more"></i>{{ __('messages.portal') }}</a></li>
                    <li class="{{ in_array($route, ['assignments.index', 'assignments.create', 'assignments.show'], true) ? 'active' : '' }}"><a href="{{ route('assignments.index') }}"><i class="ti-more"></i>{{ __('messages.assignments') }}</a></li>
                    <li class="{{ in_array($route, ['notices.index', 'notices.create', 'notices.show'], true) ? 'active' : '' }}"><a href="{{ route('notices.index') }}"><i class="ti-more"></i>{{ __('messages.notice_board') }}</a></li>
                    <li class="{{ in_array($route, ['events.index', 'events.create', 'events.show'], true) ? 'active' : '' }}"><a href="{{ route('events.index') }}"><i class="ti-more"></i>{{ __('messages.academic_calendar') }}</a></li>
                    <li class="{{ in_array($route, ['library.index', 'library.create'], true) ? 'active' : '' }}"><a href="{{ route('library.index') }}"><i class="ti-more"></i>{{ __('messages.library') }}</a></li>
                </ul>
            </li>

            @if(in_array($currentRole, ['admin', 'administrator'], true))
            <li class="treeview {{ ($prefix == '/users')?'active':'' }} ">
                <a href="#">
                    <i data-feather="message-circle"></i>
                    <span>{{ __('messages.manage_user') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('user.view') }}"><i class="ti-more"></i>{{ __('messages.view_user') }}</a></li>
                    <li><a href="{{ route('users.add') }}"><i class="ti-more"></i>{{ __('messages.add_user') }}</a></li>
                </ul>
            </li>
            @endif

            <li class="treeview {{ ($prefix == '/profile')?'active':'' }}">
                <a href="#">
                    <i data-feather="grid"></i> <span>{{ __('messages.manage_profile') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('profile.view') }}"><i class="ti-more"></i>{{ __('messages.your_profile') }}</a></li>
                    <li><a href="{{ route('password.view') }}"><i class="ti-more"></i>{{ __('messages.change_password') }}</a></li>

                </ul>
            </li>



            <li class="treeview {{ ($prefix == '/setups')?'active':'' }}">
                <a href="#">
                    <i data-feather="credit-card"></i> <span>{{ __('messages.academic_setup') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('student.class.view') }}"><i class="ti-more"></i>{{ __('messages.student_class') }}</a></li>
                    <li><a href="{{ route('student.year.view') }}"><i class="ti-more"></i>{{ __('messages.student_year') }}</a></li>
                    <li><a href="{{ route('student.group.view') }}"><i class="ti-more"></i>{{ __('messages.student_group') }}</a></li>
                    <li><a href="{{ route('student.shift.view') }}"><i class="ti-more"></i>{{ __('messages.student_shift') }}</a></li>
                    <li><a href="{{ route('fee.category.view') }}"><i class="ti-more"></i>{{ __('messages.fee_category') }}</a></li>
                    <li><a href="{{ route('fee.amount.view') }}"><i class="ti-more"></i>{{ __('messages.fee_amount') }}</a></li>
                    <li><a href="{{ route('exam.type.view') }}"><i class="ti-more"></i>{{ __('messages.exam_type') }}</a></li>
                    <li><a href="{{ route('school.subject.view') }}"><i class="ti-more"></i>{{ __('messages.school_subject') }}</a></li>
                    <li><a href="{{ route('assign.subject.view') }}"><i class="ti-more"></i>{{ __('messages.assign_subject') }}</a></li>
                    <li><a href="{{ route('routine.index') }}"><i class="ti-more"></i>{{ __('messages.class_routine') }}</a></li>
                    <li><a href="{{ route('designation.view') }}"><i class="ti-more"></i>{{ __('messages.designation') }}</a></li>

                </ul>
            </li>


            <li class="treeview {{ ($prefix == '/students')?'active':'' }}">
                <a href="#">
                    <i data-feather="hard-drive"></i></i> <span>{{ __('messages.student_management') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('student.registration.view') }}"><i class="ti-more"></i>{{ __('messages.student_registration') }}</a></li>
                    <li><a href="{{ route('roll.generate.view') }}"><i class="ti-more"></i>{{ __('messages.roll_generate') }}</a></li>
                    <li><a href="{{ route('student.attendance.view') }}"><i class="ti-more"></i>{{ __('messages.student_attendance') }}</a>
                    </li>
                    <li><a href="{{ route('registration.fee.view') }}"><i class="ti-more"></i>{{ __('messages.registration_fee') }}</a></li>
                    <li><a href="{{ route('monthly.fee.view') }}"><i class="ti-more"></i>{{ __('messages.monthly_fee') }}</a></li>
                    <li><a href="{{ route('exam.fee.view') }}"><i class="ti-more"></i>{{ __('messages.exam_fee') }}</a></li>



                </ul>
            </li>


            <li class="treeview {{ ($prefix == '/employees')?'active':'' }}">
                <a href="#">
                    <i data-feather="package"></i> <span>{{ __('messages.employee_management') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'employee.registration.view')?'active':'' }}"><a
                            href="{{ route('employee.registration.view') }}"><i class="ti-more"></i>{{ __('messages.employee_registration') }}</a></li>

                    <li class="{{ ($route == 'employee.salary.view')?'active':'' }}"><a
                            href="{{ route('employee.salary.view') }}"><i class="ti-more"></i>{{ __('messages.employee_salary') }}</a></li>

                    <li><a href="{{ route('employee.leave.view') }}"><i class="ti-more"></i>{{ __('messages.employee_leave') }}</a></li>
                    <li><a href="{{ route('employee.attendance.view') }}"><i class="ti-more"></i>{{ __('messages.employee_attendance') }}</a>
                    </li>
                    <li><a href="{{ route('employee.monthly.salary') }}"><i class="ti-more"></i>{{ __('messages.employee_monthly_salary') }}</a></li>

                </ul>
            </li>



            <li class="treeview {{ ($prefix == '/marks')?'active':'' }}">
                <a href="#">
                    <i data-feather="edit-2"></i> <span>{{ __('messages.marks_management') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'marks.entry.add')?'active':'' }}"><a
                            href="{{ route('marks.entry.add') }}"><i class="ti-more"></i>{{ __('messages.marks_entry') }}</a></li>
                    <li class="{{ ($route == 'marks.entry.edit')?'active':'' }}"><a
                            href="{{ route('marks.entry.edit') }}"><i class="ti-more"></i>{{ __('messages.marks_edit') }}</a></li>

                    <li class="{{ ($route == 'marks.entry.grade')?'active':'' }}"><a
                            href="{{ route('marks.entry.grade') }}"><i class="ti-more"></i>{{ __('messages.marks_grade') }}</a></li>


                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="ti-files"></i>
                    <span>{{ __('messages.exam_management') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('exam.routine') }}"><i class="ti-calendar"></i> {{ __('messages.exam_routine') }}</a></li>
                    <li><a href="{{ route('admit.card') }}"><i class="ti-id-card"></i> {{ __('messages.admit_card') }}</a></li>
                    <li><a href="{{ route('seat.plan') }}"><i class="ti-layout-grid"></i> {{ __('messages.seat_plan') }}</a></li>
                </ul>
            </li>


            <li class="treeview {{ ($prefix == '/accounts')?'active':'' }}">
                <a href="#">
                    <i data-feather="inbox"></i> <span>{{ __('messages.accounts_management') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'student.fee.view')?'active':'' }}"><a
                            href="{{ route('student.fee.view') }}"><i class="ti-more"></i>{{ __('messages.student_fee') }}</a></li>
                    <li class="{{ ($route == 'account.salary.view')?'active':'' }}"><a
                            href="{{ route('account.salary.view') }}"><i class="ti-more"></i>{{ __('messages.employee_salary') }}</a></li>

                    <li class="{{ ($route == 'other.cost.view')?'active':'' }}"><a
                            href="{{ route('other.cost.view') }}"><i class="ti-more"></i>{{ __('messages.other_cost') }}</a></li>

                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="ti-video-camera"></i>
                    <span>{{ __('messages.online_class') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('online-class.index') }}"><i class="ti-calendar"></i> {{ __('messages.all_classes') }}</a></li>
                    <li><a href="{{ route('online-class.create') }}"><i class="ti-plus"></i> {{ __('messages.create_class') }}</a></li>
                    <li><a href="{{ route('recordings.index') }}"><i class="ti-video-clapper"></i> {{ __('messages.class_recordings') }}</a></li>
                </ul>
            </li>


            <li class="header nav-small-cap">{{ __('messages.report_interface') }}</li>

            <li class="treeview {{ ($prefix == '/reports')?'active':'' }}">
                <a href="#">
                    <i data-feather="server"></i></i> <span>{{ __('messages.reports_management') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ ($route == 'monthly.profit.view')?'active':'' }}"><a
                            href="{{ route('monthly.profit.view') }}"><i class="ti-more"></i>{{ __('messages.monthly_yearly_profit') }}</a>
                    </li>

                    <li class="{{ ($route == 'marksheet.generate.view')?'active':'' }}"><a
                            href="{{ route('marksheet.generate.view') }}"><i class="ti-more"></i>{{ __('messages.marksheet_generate') }}</a>
                    </li>

                    <li class="{{ ($route == 'employee.attendance.report.view')?'active':'' }}"><a
                            href="{{ route('employee.attendance.report.view') }}"><i class="ti-more"></i>{{ __('messages.employee_attendance_report') }}</a>
                    </li>

                    <li class="{{ ($route == 'student.attendance.report.view')?'active':'' }}"><a
                            href="{{ route('student.attendance.report.view') }}"><i class="ti-more"></i>{{ __('messages.student_attendance_report') }}</a>
                    </li>

                    <li class="{{ ($route == 'student.result.view')?'active':'' }}"><a
                            href="{{ route('student.result.view') }}"><i class="ti-more"></i>{{ __('messages.student_result') }}</a></li>

                    <li class="{{ ($route == 'student.idcard.view')?'active':'' }}"><a
                            href="{{ route('student.idcard.view') }}"><i class="ti-more"></i>{{ __('messages.student_id_card') }}</a></li>

                </ul>
            </li>

        </ul>
    </section>

    <div class="sidebar-footer">
        <!-- item-->
        <a href="javascript:void(0)" class="link" data-toggle="tooltip" title="" data-original-title="{{ __('messages.settings') }}"
            aria-describedby="tooltip92529"><i class="ti-settings"></i></a>
        <!-- item-->
        <a href="mailbox_inbox.html" class="link" data-toggle="tooltip" title="" data-original-title="Email"><i
                class="ti-email"></i></a>
        <!-- item-->
        <a href="{{ route('admin.logout') }}" class="link" data-toggle="tooltip" title="" data-original-title="{{ __('messages.logout') }}"><i
                class="ti-lock"></i></a>
    </div>
</aside>

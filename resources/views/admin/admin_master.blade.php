<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('backend/images/favicon.ico') }}">
    <title>@yield('title', __('messages.dashboard')) - SMS ERP</title>

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('backend/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skin_color.css') }}">
    <style>
        [dir="rtl"] {
            direction: rtl;
            text-align: right;
        }
        [dir="rtl"] .pull-right {
            float: left !important;
        }
        [dir="rtl"] .main-sidebar {
            text-align: right;
        }
        .main-header .navbar-custom-menu > .nav > li > a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 50px;
        }
        .main-header .navbar-custom-menu .language-switcher > a {
            gap: 6px;
            min-width: auto;
            padding: 0 10px;
        }
        .language-current {
            font-size: 12px;
            white-space: nowrap;
        }
        .language-menu {
            min-width: 150px;
        }
        .language-menu a {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .language-menu svg {
            width: 14px;
            height: 14px;
        }
        .main-header .navbar-custom-menu svg,
        .main-header .nav svg {
            width: 18px;
            height: 18px;
            stroke-width: 1.8;
        }
        .notifications-menu .dropdown-menu {
            min-width: 320px;
        }
        .notifications-menu .menu li a {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.4;
            white-space: normal;
        }
        .notifications-menu .menu svg {
            flex: 0 0 auto;
            width: 17px;
            height: 17px;
            margin-top: 2px;
        }
    </style>

    <!-- Toastr CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="hold-transition dark-skin sidebar-mini theme-primary fixed">
    <div class="wrapper">
        @include('admin.body.header')
        @include('admin.body.sidebar')

        @yield('admin')

        @include('admin.body.footer')
        <div class="control-sidebar-bg"></div>
    </div>

    <!-- Vite JS -->
    @vite(['resources/js/app.js'])

    <!-- Vendor JS -->
    <script src="{{ asset('backend/js/vendors.min.js') }}"></script>
    <script src="{{ asset('backend/assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/easypiechart/dist/jquery.easypiechart.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/apexcharts-bundle/irregular-data-series.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor_components/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/js/pages/data-table.js') }}"></script>

    <!-- Sunny Admin App -->
    <script src="{{ asset('backend/js/template.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script type="text/javascript">
        $(function(){
            $(document).on('click', '#delete', function(e){
                e.preventDefault();
                var link = $(this).attr("href");
                Swal.fire({
                    title: @json(__('messages.delete_confirm_title')),
                    text: @json(__('messages.delete_confirm_text')),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: @json(__('messages.delete_confirm_button'))
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link;
                    }
                });
            });
        });

        @if(Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch(type){
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif


        function editRoutine(id) {
            $.ajax({
                url: "/routine/" + id + "/edit",
                type: "GET",
                success: function(data) {
                    $('#edit_class_id').val(data.class_id);
                    $('#edit_subject_id').val(data.subject_id);
                    $('#edit_employee_id').val(data.employee_id);
                    $('#edit_routine_day_id').val(data.routine_day_id);
                    $('#edit_start_time_id').val(data.start_time_id);
                    $('#edit_end_time_id').val(data.end_time_id);
                    $('#edit_room_no').val(data.room_no);
                    $('#edit_note').val(data.note);
                    $('#editForm').attr('action', '/routine/' + id);
                    $('#editRoutineModal').modal('show');
                }
            });
        }


        function generateSeatPlan() {
            // alert('ok')
            Swal.fire({
                title: @json(__('messages.generate_seat_plan_title')),
                text: @json(__('messages.generate_seat_plan_text')),
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: @json(__('messages.generate_seat_plan_button'))
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('generateForm').submit();
                }
            });
        }

    </script>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('backend/images/logo/fateha.jpeg') }}">
    <title>@yield('title', __('messages.dashboard')) - Fateha School/Madrasa</title>

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
        :root {
            --fateha-emerald: #063f3b;
            --fateha-teal: #0b5d57;
            --fateha-gold: #d7ad54;
            --fateha-cream: #f5efe0;
            --fateha-surface: #102f2d;
        }
        body.dark-skin {
            background: #082b29 !important;
            color: #c8d8d1;
        }
        body.dark-skin .main-header,
        body.dark-skin .main-header .navbar {
            background: var(--fateha-emerald) !important;
            border-bottom: 2px solid var(--fateha-gold);
        }
        body.dark-skin .main-header .logo {
            background: var(--fateha-emerald) !important;
        }
        body.dark-skin .main-header .navbar .nav > li > a,
        body.dark-skin .main-header .navbar .nav > li > a svg {
            color: var(--fateha-cream) !important;
        }
        body.dark-skin .main-header .navbar .nav > li > a:hover,
        body.dark-skin .main-header .navbar .nav > li > a:focus {
            background: rgba(215, 173, 84, .16) !important;
            color: #fff !important;
        }
        body.dark-skin .main-header .navbar .nav > li > a svg,
        body.dark-skin .main-header .navbar .nav > li > a:hover svg {
            stroke: var(--fateha-gold) !important;
        }
        .main-header .header-icon {
            color: var(--fateha-gold) !important;
            font-size: 18px;
            line-height: 1;
        }
        .user-profile .ulogo > a > div {
            gap: 8px;
            min-width: 0;
        }
        .user-profile .ulogo img {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--fateha-gold);
        }
        .user-profile .ulogo h3 {
            margin: 0;
            font-size: 16px;
            line-height: 1.15;
            white-space: nowrap;
        }
        .main-header .dropdown-menu .fa {
            min-width: 16px;
            color: var(--fateha-gold);
        }
        body.dark-skin .main-sidebar {
            background: var(--fateha-emerald) !important;
            border-right: 1px solid rgba(215, 173, 84, .35);
        }
        body.dark-skin .user-profile .ulogo h3,
        body.dark-skin .sidebar-menu > li > a,
        body.dark-skin .sidebar-menu > li > a svg {
            color: var(--fateha-cream) !important;
        }
        body.dark-skin .sidebar-menu > li.active > a,
        body.dark-skin .sidebar-menu > li:hover > a,
        body.dark-skin .sidebar-menu > li.menu-open > a {
            background: var(--fateha-teal) !important;
            color: #fff !important;
            border-left: 3px solid var(--fateha-gold);
        }
        body.dark-skin .sidebar-menu .treeview-menu {
            background: #052f2c !important;
        }
        body.dark-skin .content-wrapper,
        body.dark-skin .main-footer {
            background: #082b29 !important;
        }
        body.dark-skin .box,
        body.dark-skin .dropdown-menu {
            background: var(--fateha-surface) !important;
            border: 1px solid rgba(215, 173, 84, .22);
        }
        body.dark-skin .box-header {
            border-bottom-color: rgba(215, 173, 84, .35);
        }
        body.dark-skin .box-title,
        body.dark-skin .main-footer,
        body.dark-skin .main-footer a {
            color: var(--fateha-cream) !important;
        }
        body.dark-skin .btn-primary,
        body.dark-skin .bg-primary {
            background-color: var(--fateha-teal) !important;
            border-color: var(--fateha-gold) !important;
        }
        body.dark-skin .btn-primary:hover,
        body.dark-skin .btn-primary:focus {
            background-color: var(--fateha-gold) !important;
            color: var(--fateha-emerald) !important;
        }
        body.dark-skin .text-primary,
        body.dark-skin a:hover,
        body.dark-skin .main-footer a:hover {
            color: var(--fateha-gold) !important;
        }
        body.dark-skin .form-control {
            background: #0b3b37 !important;
            border-color: rgba(215, 173, 84, .35) !important;
            color: var(--fateha-cream) !important;
        }
        body.dark-skin .form-control:focus {
            border-color: var(--fateha-gold) !important;
            box-shadow: 0 0 0 .15rem rgba(215, 173, 84, .2) !important;
        }
        .global-search {
            position: relative;
            margin: 0 8px;
        }
        .global-search form,
        .global-search .lookup {
            width: 230px;
        }
        .global-search input {
            width: 100%;
            height: 38px;
            padding: 0 42px 0 14px;
            border: 1px solid var(--fateha-gold) !important;
            border-radius: 22px;
            background: #f5efe0 !important;
            color: #063f3b !important;
        }
        .global-search input::placeholder {
            color: #52716c;
        }
        .global-search-results {
            display: none;
            position: absolute;
            top: 48px;
            right: 0;
            z-index: 1100;
            width: 300px;
            padding: 6px 0;
            border: 1px solid var(--fateha-gold);
            border-radius: 8px;
            background: var(--fateha-surface);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .3);
        }
        .global-search-results.is-visible {
            display: block;
        }
        .global-search-results a,
        .global-search-results .empty {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 9px 13px;
            color: var(--fateha-cream);
            line-height: 1.25;
        }
        .global-search-results a:hover {
            background: var(--fateha-teal);
            color: #fff;
            text-decoration: none;
        }
        .global-search-results svg {
            width: 17px;
            color: var(--fateha-gold);
        }
        .global-search-results small {
            display: block;
            color: #9dbbb2;
            font-size: 11px;
        }
        @media (max-width: 767px) {
            .user-profile .ulogo h3 {
                font-size: 13px;
            }
            .user-profile .ulogo img {
                width: 36px;
                height: 36px;
                flex-basis: 36px;
            }
            .global-search form,
            .global-search .lookup {
                width: 150px;
            }
            .fateha-auth-logo {
                display: block;
                width: min(180px, 42vw);
                height: auto;
                max-height: 115px;
                margin: 0 auto;
                object-fit: contain;
                border-radius: 12px;
            }
            .global-search-results {
                right: -70px;
                width: 270px;
            }
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
        $(function () {
            var searchTimer;
            var $input = $('#global-search-input');
            var $results = $('#global-search-results');
            $('.global-search form').on('submit', function (event) {
                event.preventDefault();
                $input.trigger('input');
            });

            function renderSearchResults(results) {
                $results.empty();
                if (!results.length) {
                    $results.append('<div class="empty">{{ __('messages.no_search_results') }}</div>');
                } else {
                    $.each(results, function (_, result) {
                        $results.append(
                            '<a href="' + result.url + '">' +
                            '<i class="fa fa-' + (result.type === 'Student' ? 'user' : (result.type === 'Menu' ? 'th-large' : 'briefcase')) + '"></i>' +
                            '<span><strong>' + result.name + '</strong><small>' + result.type + ' &middot; ' + result.meta + '</small></span>' +
                            '</a>'
                        );
                    });
                }
                $results.addClass('is-visible');
            }

            $input.on('input', function () {
                var query = $.trim($(this).val());
                clearTimeout(searchTimer);
                if (query.length < 2) {
                    $results.removeClass('is-visible').empty();
                    return;
                }
                searchTimer = setTimeout(function () {
                    $.get('{{ route('global.search') }}', { q: query })
                        .done(function (response) { renderSearchResults(response.results || []); });
                }, 250);
            });

            $(document).on('click', function (event) {
                if (!$(event.target).closest('.global-search').length) {
                    $results.removeClass('is-visible');
                }
            });
        });

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

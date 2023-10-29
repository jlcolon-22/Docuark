<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <script src="{{ URL::to('gant/frappe-gantt.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::to('gant/frappe-gantt.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Dashboard
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ URL::to('/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ URL::to('/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ URL::to('/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ URL::to('/assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <style type="text/css">
        .fc-time {
            display: none;
        }

        .fc-title {
            text-wrap: wrap;

        }

        .fc-content {
            text-align: center !important;
        }
    </style>
</head>

<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300  position-absolute w-100" style="background-color: #C70039"></div>
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" # " target="_blank">
                <img src="{{ URL::to('logo-red.png') }}" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                <br />
                <span class="ms-1 font-weight-bold">{{ Auth::user()->department->name }}</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " style="height: fit-content" id="sidenav-collapse-main">
            @include('shared.side')
        </div>

    </aside>
    <main class="main-content position-relative border-radius-lg ">

        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            @role('tasker')
                            <div class="d-flex justify-content-between">
                                <h6>Project Lists</h6>
                                <a href="/tasker/home" class="btn text-white btn-xs" style="background: #000000">Back</a>
                              </div>
                            @endrole
                            <ul class="nav nav-tabs nav-justified">
                                @if (Auth::user()->getRoleNames()[0] == 'admin' || Auth::user()->getRoleNames()[0] == 'manager')
                                    <li class="nav-item">
                                        <a class="nav-link "
                                            href="{{ route('admin_task_list', Request::segment(2)) }}">Task
                                            List</a>
                                    </li>
                                    @if (Auth::user()->getRoleNames()[0] != 'manager')
                                        <li class="nav-item ">
                                            <a class="nav-link "
                                                href="{{ route('admin_task_file_list', Request::segment(2)) }}">Task
                                                Files</a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a class="nav-link "
                                            href="{{ route('admin_task_schedule_list', Request::segment(2)) }}">Task
                                            Schedule</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " style="border-bottom: 1px"
                                            href="{{ route('admin_task_timeline_list', Request::segment(2)) }}">Timeline
                                            of
                                            activities</a>
                                    </li>
                                    <li class="nav-item ">
                                        <a class="nav-link active"
                                            href="{{ route('admin_task_activity_log', Request::segment(2)) }}">
                                            @if (Auth::user()->getRoleNames()[0] == 'admin')
                                                Activity Log
                                            @else
                                                My Activity Log
                                            @endif
                                        </a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link " style="border-bottom: 1px"
                                            href="{{ route('tasker_task_list', Request::segment(2)) }}">Task List</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active"
                                            href="{{ route('tasker_task_activity_log', Request::segment(2)) }}">
                                            @if (Auth::user()->getRoleNames()[0] == 'admin')
                                                Activity Log
                                            @else
                                                My Activity Log
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            </ul>
                            <br>



                            <h3 class="text-center">{{ $find_assign_project->project->project_type }}
                                {{ isset($find_assign_project->department->name) ? 'Report of ' . $find_assign_project->department->name : 'None' }}
                            </h3>
                            @role('admin')
                                <p>Show: </p>
                                <form id="arrangeForm" action="" method="GET">
                                    @csrf
                                    <select id="arrangeSelect" name="arrange_by">
                                        <option>Select Here</option>
                                        <option value="All-Actions">All Actions</option>
                                        <option value="My-Actions">My Actions</option>
                                    </select>
                                </form>
                            @endrole
                            @include('shared.notification')
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive " style="padding: 0px 10px">
                                <table class="table table-striped">
                                    <thead>
                                        <th>Date</th>
                                        @role('tasker')
                                        <th style="text-align: center">logs</th>
                                        @endrole
                                        @role('manager')
                                        <th style="text-align: center">logs</th>
                                        @endrole
                                        @role('admin')
                                        <th>logs</th>
                                            <th>Action</th>
                                        @endrole

                                    </thead>
                                    <tbody>

                                        @foreach ($logs as $log)
                                            @if (Auth::user()->getRoleNames()[0] == 'admin')

                                                <tr>
                                                    <td style="font-size:14px">
                                                        {{ date_format($log->created_at, 'Y/m/d h:i A') }}</td>
                                                    <td style="font-size:14px;font-weight:600">{{ $log->message }}</td>
                                                    <td style="font-size:14px">
                                                        @if (Auth::user()->getRoleNames()[0] == 'admin' && $log->type == 1)
                                                        @if ($log->deleted_at == null)

                                                        <a href="/admin/activity_log/undo/{{ $log->id }}/{{ $log->category }}"
                                                            class="btn btn-info btn-xs">
                                                            Undo
                                                        </a>
                                                        @endif
{{--
                                                                @if (\Carbon\Carbon::now()->diffInMinutes($log->deleted_at) <= 2)
                                                                    <a href="/admin/activity_log/undo/{{ $log->id }}"
                                                                        class="btn btn-info btn-xs">
                                                                        Undo
                                                                    </a>
                                                                @endif --}}


                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (Auth::user()->getRoleNames()[0] == 'manager' && Auth::id() == $log->user_id)

                                                    <tr>
                                                        <td style="font-size:14px">
                                                            {{ date_format($log->created_at, 'Y/m/d h:i A') }}</td>
                                                        <td style="font-size:14px;font-weight:600;text-align: center">{{ $log->message }}
                                                        </td>
                                                        @if (Auth::user()->getRoleNames()[0] == 'admin')
                                                            <td style="font-size:14px">

                                                                <button type="button"
                                                                    class="btn btn-danger btn-xs remove-file"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteFile"
                                                                    value="{{ $log->id }}">
                                                                    Delete
                                                                </button>

                                                            </td>
                                                        @endif
                                                    </tr>

                                            @endif
                                            @if (Auth::user()->getRoleNames()[0] == 'tasker' && Auth::id() == $log->user_id)

                                                <tr>
                                                    <td style="font-size:14px">
                                                        {{ date_format($log->created_at, 'Y/m/d h:i A') }}</td>
                                                    <td style="font-size:14px;font-weight:600;text-align: center">{{ $log->message }}
                                                    </td>
                                                    @if (Auth::user()->getRoleNames()[0] == 'admin')
                                                        <td style="font-size:14px">

                                                            <button type="button"
                                                                class="btn btn-danger btn-xs remove-file"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteFile"
                                                                value="{{ $log->id }}">
                                                                Delete
                                                            </button>

                                                        </td>
                                                    @endif
                                                </tr>

                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>

    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Are you sure to delete?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form role="form" action="{{ route('admin_task_activity_log_delete') }}" method="POST">
                    <!-- Modal body -->
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" name="log_id" id="deleteProjectId">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-danger text-white">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>




    <!--   Core JS Files   -->
    <script src="{{ URL::to('/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ URL::to('/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ URL::to('/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ URL::to('/assets/js/argon-dashboard.min.js?v=2.0.4') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#arrangeSelect").change(function() {
                var arrange = $(this).val();
                $("#arrangeForm").submit();
            });
            $(".remove-log").click(function() {
                var task_id = $(this).val();
                $("#deleteProjectId").val(task_id);

            });

        });
    </script>
</body>

</html>

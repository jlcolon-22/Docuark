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

.fc-title{
text-wrap:wrap;

}
.fc-content{
    text-align: center !important;
}


    </style>
</head>

<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" # " target="_blank">
                <img src="{{ URL::to('/assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                <br />
                <span class="ms-1 font-weight-bold">{{ Auth::user()->department->name }}</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
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
                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link "
                                        href="{{ route('admin_task_list', Request::segment(2)) }}">Task
                                        List</a>
                                </li>
                                @if (Auth::user()->getRoleNames()[0] != 'manager')
                                <li class="nav-item ">
                                    <a class="nav-link "
                                        href="{{ route('admin_task_file_list', Request::segment(2)) }}">Task Files</a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link "
                                        href="{{ route('admin_task_schedule_list', Request::segment(2)) }}">Task
                                        Schedule</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active"
                                        href="{{ route('admin_task_timeline_list', Request::segment(2)) }}">Timeline of activities</a>
                                </li>

                            </ul>
                            <br>



                            <h3 class="text-center">{{ $find_assign_project->project->project_type }} to
                                {{ isset($find_assign_project->department->name) ? 'Report of ' . $find_assign_project->department->name : 'None' }}
                            </h3>

                            @include('shared.notification')
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">

                                <div id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>






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

    <script>
        $('#calendar').fullCalendar({

            events: [

                @foreach ($comments as $com)
                    @if (count($com) > 0)

                        @foreach ($com as $value)
                            {
                                title: '{{ $value->user->first_name }} commented',
                                start: '{{ explode(' ', $value->created_at)[0] }}',
                                color: 'gray'
                            },
                        @endforeach
                    @endif
                @endforeach
                @foreach ($deleted as $delete)

                    @if ($delete->type == 0)
                    {
                        title: '{{ explode('/',$delete->name)[1] }} File Deleted',
                        start: '{{ explode(' ', $delete->created_at)[0] }}',
                        color: '#dc3545'
                    },
                    @else
                    {
                        title: '{{ $delete->name }} Task Deleted',
                        start: '{{ explode(' ', $delete->created_at)[0] }}',
                        color: '#dc3545'
                    },
                    @endif
                @endforeach
                @foreach ($tasks as $task)
                    {
                        title: '{{ $task->title }} Created',
                        start: '{{ explode(' ', $task->created_at)[0] }}',
                        color: '#ffc107',

                    }, {
                        @if ($task->status_id == 2)
                            title: "COMPLETE",
                        @elseif ($task->status_id == 1)
                            title: 'Active',
                        @else
                            title: 'Inactive',
                        @endif
                        start: '{{ $task->deadline }}',
                        color: 'green'

                    }, {
                        title: '{{ $task->title }} Deadline',
                        start: '{{ $task->deadline }}',
                        color: '#ffc107'

                    },
                @endforeach




                @foreach ($task_files as $file)

                    @if (count($file) > 0)
                        @foreach ($file as $value)
                            {
                                title: '{{ explode('/', $value->file_name)[1] . ' ' . $value->type }} was upload',
                                start: '{{ explode(' ', $value->created_at)[0] }}',

                            },
                        @endforeach
                    @endif
                @endforeach



            ],

        });
    </script>
</body>

</html>

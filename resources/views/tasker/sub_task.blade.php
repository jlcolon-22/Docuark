<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
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
                            <div class="d-flex justify-content-between">
                                <h6>Project Lists</h6>
                                <a href="/tasker/home" class="btn text-white btn-xs"
                                    style="background: #000000">Back</a>
                            </div>
                            <ul class="nav nav-tabs nav-justified">

                                <li class="nav-item">
                                    <a class="nav-link active" style="border-bottom: 1px"
                                        href="{{ route('tasker_task_list', Request::segment(2)) }}">Task List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('tasker_task_activity_log', Request::segment(2)) }}">
                                        @if (Auth::user()->getRoleNames()[0] == 'admin')
                                            Activity Log
                                        @else
                                            My Activity Log
                                        @endif
                                    </a>
                                </li>

                            </ul>
                            <br>
                            <h3 class="text-center">{{ $find_assign_project->project->project_type }}
                                {{ isset($find_assign_project->department->name) ? 'Report of ' . $find_assign_project->department->name : 'None' }}
                            </h3>

                            <p>Arrange By: </p>
                            <form id="arrangeForm" action="" method="GET">
                                @csrf
                                <select id="arrangeSelect" name="arrange_by">
                                    <option>Select Here</option>
                                    <option value="Normal">Main Task Only</option>
                                    <option value="Sub-Task">With SubTask</option>
                                </select>
                            </form>
                            @include('shared.notification')
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Created</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $task)
                                            <tr>

                                                <td>
                                                    <div class="d-flex  ">

                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-0 ">
                                                                {{ $task->title }}</h6>

                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    @if ($task->status_id == 1)
                                                        <span
                                                            class="badge badge-sm text-success font-weight-bold">Active</span>
                                                    @elseif($task->status_id == 0)
                                                        <span class="badge badge-sm text-danger font-weight-bold"
                                                            style="">Inactive</span>
                                                    @elseif($task->status_id == 2)
                                                        <span
                                                            class="badge badge-sm text-secondary font-weight-bold">Completed</span>
                                                    @endif

                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $task->created_at }}</span>
                                                </td>
                                                <td class="align-middle text-center">


                                                    @if ($task->status_id == 1)
                                                        <a href="{{ route('tasker_update_task', $task->id) }}"
                                                            class="btn btn-secondary btn-xs">Finished</a>

                                                        <a href="{{ route('share_view_task', ['task_id' => $task->id, 'project_id' => Request::Segment(2)]) }}"
                                                            class="btn btn-xs text-white"
                                                            style="background: #000000">View Task</a>
                                                    @elseif($task->status_id == 0)
                                                        N/A
                                                    @endif
                                                    @if ($task->status_id == 1 || $task->status_id == 2 || $task->status_id == 0)
                                                        <button class="btn btn-info btn-xs upload"
                                                            data-bs-toggle="modal" data-bs-target="#uploadModal"
                                                            value="{{ $task->id }}"
                                                            style="width: 110px">Upload</button>
                                                    @endif

                                                </td>

                                            </tr>
                                            @foreach ($task->sub as $sub)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex  ">

                                                            <div class="d-flex flex-column">
                                                                <h6 class="mb-0 text-sm"
                                                                    style="padding-left: 70px;text-align: left">
                                                                    {{ $sub->title }}</h6>

                                                            </div>
                                                        </div>
                                                    </td>



                                                    <td>
                                                        @if ($sub->status_id == 1)
                                                            <span
                                                                class="badge badge-sm text-success font-weight-bold">Active</span>
                                                        @elseif($sub->status_id == 0)
                                                            <span class="badge badge-sm text-danger font-weight-bold"
                                                                style="">Inactive</span>
                                                        @elseif($sub->status_id == 2)
                                                            <span
                                                                class="badge badge-sm text-secondary font-weight-bold">Completed</span>
                                                        @endif

                                                    </td>

                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="text-secondary text-xs font-weight-bold">{{ $sub->created_at }}</span>
                                                    </td>




                                                    <td class="align-middle"
                                                        style="display: flex; justify-content: center;">


                                                        @if (Auth::user()->getRoleNames()[0] == 'admin')
                                                            <button class="btn btn-info btn-xs updateSub"
                                                                style="width: 110px" data-bs-toggle="modal"
                                                                data-bs-target="#subModal"
                                                                value="{{ $sub->id }}">Edit</button>
                                                        @endif

                                                        @if ($sub->status_id == 1 || $sub->status_id == 2 || $sub->status_id == 0)
                                                            @if (Auth::user()->getRoleNames()[0] == 'admin' ||
                                                                    Auth::user()->getRoleNames()[0] == 'manager' ||
                                                                    Auth::user()->getRoleNames()[0] == 'tasker')
                                                                <a href="{{ route('tasker_update_sub_task', $sub->id) }}" class="btn btn-secondary btn-xs " >Finished</a>

                                                                @role('admin')
                                                                    <button class="btn btn-danger btn-xs deleteSub"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#deleteSubModal"
                                                                        value="{{ $sub->id }}"
                                                                        style="width: 110px">Delete</button>
                                                                @endrole
                                                            @endif
                                                            {{-- <button class="btn btn-info btn-xs upload" data-bs-toggle="modal" data-bs-target="#uploadModal" value="{{$sub->id}}" style="width: 110px">Upload</button>
                               <a href="{{route('share_view_task',['task_id' => $sub->id, 'project_id'=> Request::Segment(2)])}}" class="btn text-white btn-xs" style="width: 110px;background:#000000">View Task</a> --}}
                                                        @elseif($sub->status_id == 0)
                                                            <button class="btn btn-success btn-xs archive"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                value="{{ $sub->id }}"
                                                                style="width: 110px">Activate</button>
                                                        @endif







                                                    </td>
                                                </tr>
                                            @endforeach
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
                    <h4 class="modal-title">Are you sure you want to delete?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin_delete_user') }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="delete_user_id">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal" id="uploadModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Upload File?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form role="form" action="{{ route('upload_task') }}" method="POST"
                    enctype="multipart/form-data">
                    <!-- Modal body -->
                    <div class="modal-body">

                        @csrf


                        <input type="hidden" name="task_id" id="task_list_id">
                        <div class="mb-3">
                            <label>Task Type </label>
                            <select class="form-control" required name="file_type">
                                <option value="Image">Photo</option>
                                <option value="Document">Document</option>
                                <option value="Physical Task">Physical Task</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Task File</label>
                            <input type="file" name="task_file" class="form-control" required>
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-info text-white">Submit</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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

            $(".upload").click(function() {
                var task_id = $(this).val();
                $("#task_list_id").val(task_id);

            });
        });
    </script>
</body>

</html>

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
    <div class="min-height-300 position-absolute w-100" style="background-color: #C70039"></div>
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
                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" style="border-bottom: 1px"
                                        href="{{ route('admin_task_list', Request::segment(2)) }}">Task List</a>
                                </li>
                                @if (Auth::user()->getRoleNames()[0] != 'manager')
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('admin_task_file_list', Request::segment(2)) }}">Task
                                            Files</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('admin_task_schedule_list', Request::segment(2)) }}">Task
                                        Schedule</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('admin_task_timeline_list', Request::segment(2)) }}">Timeline of
                                        activities</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('admin_task_activity_log',Request::segment(2))}}">
                                        @if (Auth::user()->getRoleNames()[0] == 'admin')
                                        Activity Log
                                        @else
                                        My Activity Log
                                        @endif
                                    </a>
                                  </li>

                            </ul>
                            <br>
                            {{-- @if ($find_project->status_id == 1)
                                <button class="btn btn-info btn-xs edit" data-bs-toggle="modal"
                                    data-bs-target="#createModal">Create</button>
                            @endif --}}
                            @if($find_project->status_id == 1)
                            @if(Auth::user()->getRoleNames()[0] == 'admin')
                             <div class="d-flex justify-content-between">
                                <button class="btn btn-info btn-xs edit" style="width: 90px" data-bs-toggle="modal" data-bs-target="#createModal">Add Task</button>
                                <a href="/admin/projects" class="btn text-white btn-xs" style="background:#000000;width: 90px">Back</a>
                             </div>
                            @endif
                          @endif
                            <h3 class="text-center">{{ $find_assign_project->project->project_type }}
                                {{ isset($find_assign_project->department->name) ? 'Task of ' . $find_assign_project->department->name : 'None' }}
                            </h3>
                            <p>Arrange By: </p>
                            <form id="arrangeForm" action="" method="GET">
                                @csrf
                                <select id="arrangeSelect" name="arrange_by">
                                    <option>Select Here</option>
                                    <option value="Normal">All Task</option>
                                    <option value="Sub-Task">Sub-Task</option>
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
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Created</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Deadline</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Last Edited By</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Date Last Edited</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $task)
                                            <tr>

                                                <td>
                                                    <div class="d-flex px-2 py-1">

                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $task->title }}</h6>

                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="align-middle text-center text-sm">
                                                    @if ($task->status_id == 1)
                                                        <span style="color: #70AD47;font-weight:bold">Active</span>
                                                    @elseif($task->status_id == 0)
                                                        <span style="color: #FF0000;font-weight:bold">Inactive</span>
                                                    @elseif($task->status_id == 2)
                                                        <span class="font-weight-bold text-secondary">Completed</span>
                                                    @endif

                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $task->created_at }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $task->deadline }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $task->user->username }}</span>
                                                </td>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $task->updated_at }}</span>
                                                </td>

                                                <td class="align-middle">


                                                    @if (Auth::user()->getRoleNames()[0] == 'admin')
                                                        <button class="btn btn-info btn-xs updateProject"
                                                            style="width: 110px" data-bs-toggle="modal"
                                                            data-bs-target="#editModal"
                                                            value="{{ $task->id }}">Edit</button>
                                                    @endif

                                                    @if ($task->status_id == 1 || $task->status_id == 2 || $task->status_id == 0)
                                                        @if (Auth::user()->getRoleNames()[0] == 'admin')
                                                            <button class="btn btn-secondary btn-xs archive"
                                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                                value="{{ $task->id . '|' . $task->status_id }}"
                                                                style="width: 110px">Change Status</button>

                                                            <button class="btn btn-danger btn-xs delete"
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                                value="{{ $task->id }}"
                                                                style="width: 110px">Delete</button>
                                                        @endif
                                                        <button class="btn btn-info btn-xs upload"
                                                            data-bs-toggle="modal" data-bs-target="#uploadModal"
                                                            value="{{ $task->id }}"
                                                            style="width: 110px">Upload</button>
                                                        <a href="{{ route('share_view_task', ['task_id' => $task->id, 'project_id' => Request::Segment(2)]) }}"
                                                            class="btn text-white btn-xs"
                                                            style="width: 110px;background:#000000">View Task</a>
                                                    @elseif($task->status_id == 0)
                                                        <button class="btn btn-success btn-xs archive"
                                                            data-bs-toggle="modal" data-bs-target="#statusModal"
                                                            value="{{ $task->id }}"
                                                            style="width: 110px">Activate</button>
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

                                                    <td class="align-middle text-center text-sm">

                                                        @if ($sub->status_id == 1)
                                                            <span style="color: #70AD47;font-weight:bold">Active</span>
                                                        @elseif($sub->status_id == 0)
                                                            <span
                                                                style="color: #FF0000;font-weight:bold">Inactive</span>
                                                        @elseif($sub->status_id == 2)
                                                            <span
                                                                class="font-weight-bold text-secondary">Completed</span>
                                                        @endif

                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="text-secondary text-xs font-weight-bold">{{ $sub->created_at }}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="text-secondary text-xs font-weight-bold">{{ $sub->deadline }}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="text-secondary text-xs font-weight-bold">{{ $sub->user->username }}</span>
                                                    </td>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="text-secondary text-xs font-weight-bold">{{ $sub->updated_at }}</span>
                                                    </td>
                                                    {{-- <td class="align-middle">

                                                        @if ($find_assign_project->project->status_id == 1)
                                                            <button class="btn btn-info btn-xs updateSub"
                                                                data-bs-toggle="modal" data-bs-target="#subModal"
                                                                value="{{ $sub->id }}">Edit</button>

                                                            @if ($sub->status_id == 1)
                                                                <button class="btn btn-primary btn-xs archiveSub"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#statusSubModal"
                                                                    value="{{ $sub->id }}">Archive</button>

                                                                <!-- <a href="{{ route('share_view_task', ['task_id' => $sub->id, 'project_id' => Request::Segment(2)]) }}" class="btn btn-info btn-xs">View Task</a> -->

                                                                <button class="btn btn-danger btn-xs deleteSub"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteSubModal"
                                                                    value="{{ $sub->id }}">Delete</button>
                                                            @elseif($sub->status_id == 0)
                                                                <button class="btn btn-success btn-xs archiveSub"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#statusSubModal"
                                                                    value="{{ $sub->id }}">Activate</button>
                                                            @endif
                                                        @endif



                                                    </td> --}}
                                                    <td class="align-middle">


                                                        @if (Auth::user()->getRoleNames()[0] == 'admin')
                                                            <button class="btn btn-info btn-xs updateSub"
                                                                style="width: 110px" data-bs-toggle="modal"
                                                                data-bs-target="#subModal"
                                                                value="{{ $sub->id }}">Edit</button>
                                                        @endif

                                                        @if ($sub->status_id == 1 || $sub->status_id == 2 || $sub->status_id == 0)
                                                            @if (Auth::user()->getRoleNames()[0] == 'admin' || Auth::user()->getRoleNames()[0] == 'manager' )
                                                                <button class="btn btn-secondary btn-xs archiveSub"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#statusSubModal"
                                                                    value="{{ $sub->id . '|' . $sub->status_id }}"
                                                                    style="width: 110px">Change Status</button>

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
    <div class="modal" id="createModal">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Task Informations</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form role="form" action="{{route('admin_create_task')}}" method="POST" enctype="multipart/form-data">
            <!-- Modal body -->
            <div class="modal-body">

            @csrf
            <input type="hidden" name="project_id" value="{{Request::segment(2)}}">
            <div class="mb-3">
              <label>Task Name <span style="color: red; margin-bottom: -10px;">*</span></label>
              <input type="text" class="form-control" placeholder="Name" aria-label="Name" name="title" required id="name">
            </div>
            <div class="mb-3">
              <label>Task Description <span style="color: red; margin-bottom: -10px;">*</span></label>
              <textarea class="form-control" name="description" required></textarea>
            </div>
            <div class="mb-3">
              <label>Task Deadline <span style="color: red; margin-bottom: -10px;">*</span></label>
              <input type="date" class="form-control" name="deadline" required >
            </div>
            <div class="mb-3">
              <label>Task Type <span style="color: red; margin-bottom: -10px;">*</span></label>
               <select class="form-select" required name="file_type">
                @foreach($file_types as $file)
                  <option value="{{$file->name}}">{{$file->name}}</option>
                @endforeach

               </select>
              </div>
            <div class="mb-3">
              <label>Task File <span style="color: gray; margin-bottom: -10px;">(optional)</span></label>
              <input type="file" name="task_file" class="form-control">
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

    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Task Informations</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form role="form" action="{{ route('admin_update_task') }}" method="POST">
                    <!-- Modal body -->
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" name="task_id" id="updateProjectId">
                        <div class="mb-3">
                            <label>Task Name</label>
                            <input type="text" class="form-control" placeholder="Name" aria-label="Name"
                                name="title" required id="editTitle">
                        </div>
                        <div class="mb-3">
                            <label>Report Deadline</label>
                            <input type="date" class="form-control" name="deadline" required id="deadline_get">
                        </div>
                        <div class="mb-3">
                            <label>Task Description</label>
                            <textarea class="form-control" name="description" required id="editDescription"></textarea>
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

    <div class="modal" id="subModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Sub-Task Informations</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form role="form" action="{{ route('admin_update_sub_task') }}" method="POST">
                    <!-- Modal body -->
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" name="sub_id" id="updateSubId">
                        <div class="mb-3">
                            <label>Task Name</label>
                            <input type="text" class="form-control" placeholder="Name" aria-label="Name"
                                name="title" required id="subTitle">
                        </div>
                        <div class="mb-3">
                            <label>Report Deadline</label>
                            <input type="date" class="form-control" name="deadline" required id="subDeadline">
                        </div>
                        <div class="mb-3">
                            <label>Task Description</label>
                            <textarea class="form-control" name="description" required id="subDescription"></textarea>
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info text-white">Submit</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal" id="statusModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="statatusText">Change Status?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form role="form" action="{{ route('admin_change_task_status') }}" method="POST">
                    <!-- Modal body -->
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" name="task_id" id="statusProjectId">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-secondary text-white" style="width: 135px">Change Status</button>
                        <button type="button" class="btn btn-danger" style="width: 135px" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal" id="statusSubModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="statatusTexts">Change Status?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form role="form" action="{{ route('admin_change_sub_task_status') }}" method="POST">
                    <!-- Modal body -->
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" name="sub_id" id="statusSubId">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-secondary text-white" style="width: 135px">Change Status</button>
                        <button type="button" class="btn btn-danger" style="width: 135px" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Are you sure to delete?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form role="form" action="{{ route('admin_delete_task') }}" method="POST">
                    <!-- Modal body -->
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" name="task_id" id="deleteProjectId">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger text-white">Delete</button>
                        <button type="button" class="btn btn-secondary text-white"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal" id="deleteSubModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="statatusText">Are you sure to delete SubTask?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form role="form" action="{{ route('admin_delete_sub_task') }}" method="POST">
                    <!-- Modal body -->
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" name="sub_id" id="deleteSubId">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-danger text-white">Delete</button>
                        <button type="button" class="btn btn-secondary text-white"
                            data-bs-dismiss="modal">Close</button>
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
            var find_project_url = "{{ route('admin_find_task') }}";
            admin_find_sub_task
            var admin_find_sub_task = "{{ route('admin_find_sub_task') }}";
            var token = "{{ Session::token() }}";

            $(".archive").click(function() {
                var task_id = $(this).val();
                var split = task_id.split('|')[0]
                $("#statusProjectId").val(split);

                if (task_id.split('|')[1] == 1) {
                    $('#statatusText').text("Would you like to change this task's status to Inactive?");
                }
                if (task_id.split('|')[1] == 0) {
                    $('#statatusText').text("Would you like to change this task's status to Active?");
                }

            });

            $(".archiveSub").click(function() {
                var sub_id = $(this).val();

                var split = sub_id.split('|')[0]
                $("#statusSubId").val(split);
                console.log(sub_id)
                if (sub_id.split('|')[1] == 1) {
                    $('#statatusTexts').text(
                    "Would you like to change this sub task's status to Inactive?");
                }
                if (sub_id.split('|')[1] == 0) {
                    $('#statatusTexts').text("Would you like to change this sub task's status to Active?");
                }

            });

            $(".delete").click(function() {
                var task_id = $(this).val();
                $("#deleteProjectId").val(task_id);

            });

            $(".deleteSub").click(function() {
                var sub_id = $(this).val();
                $("#deleteSubId").val(sub_id);

            });

            $(".assign").click(function() {
                var task_id = $(this).val();
                $("#assignTask").val(task_id);

            });

            $(".updateProject").click(function() {
                var task_id = $(this).val();
                $("#updateProjectId").val(task_id);
                $.ajax({
                    type: 'POST',
                    url: find_project_url,
                    data: {
                        _token: token,
                        task_id: task_id
                    },
                    success: function(data) {
                        console.log(data);
                        $("#editTitle").val(data.title);
                        $("#editDescription").val(data.description);
                        $("#deadline_get").val(data.deadline);

                    }
                });


            });

            $(".updateSub").click(function() {
                var sub_id = $(this).val();
                console.log(sub_id);
                $("#updateSubId").val(sub_id);
                $.ajax({
                    type: 'POST',
                    url: admin_find_sub_task,
                    data: {
                        _token: token,
                        sub_id: sub_id
                    },
                    success: function(data) {
                        console.log(data);
                        $("#subTitle").val(data.title);
                        $("#subDeadline").val(data.deadline);
                        $("#subDescription").val(data.description);

                    }
                });


            });
            $(".upload").click(function(){
        var task_id = $(this).val();
        $("#task_list_id").val(task_id);

      });
            $("#arrangeSelect").change(function() {
                var arrange = $(this).val();
                $("#arrangeForm").submit();
            });

        });
    </script>
</body>

</html>

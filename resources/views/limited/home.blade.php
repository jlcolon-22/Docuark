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
  <link href="{{URL::to('/assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{URL::to('/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{URL::to('/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{URL::to('/assets/css/argon-dashboard.css?v=2.0.4')}}" rel="stylesheet" />
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 position-absolute w-100" style="background-color: #C70039"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" # " target="_blank">
        <img src="{{ URL::to('logo-red.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
        <br />
        <span class="ms-1 font-weight-bold" >{{Auth::user()->department->name}}</span>
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
              <h6>Project Lists</h6>
              <button class="btn btn-info btn-xs edit" data-bs-toggle="modal" data-bs-target="#createModal">Create</button>
              @include('shared.notification')
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>

                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created By</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $proj)
                    <tr>

                      <td>
                        <div class="d-flex px-2 py-1">

                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{$proj->title}}</h6>

                          </div>
                        </div>
                      </td>

                      <td class="align-middle text-center text-sm">
                        @if($proj->status_id == 1)
                          <span class="badge badge-sm bg-gradient-success">Active</span>
                        @elseif($proj->status_id == 0)
                          <span class="badge badge-sm bg-gradient-danger">Archived</span>
                        @elseif($proj->status_id == 2)
                          <span class="badge badge-sm bg-gradient-secondary">Completed</span>
                        @endif

                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">{{$proj->user->name}}</span>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">{{$proj->created_at->diffForHumans()}}</span>
                      </td>
                      <td class="align-middle">
                        <button class="btn btn-info btn-xs updateProject" data-bs-toggle="modal" data-bs-target="#editModal" value="{{$proj->id}}">Edit</button>

                        @if($proj->status_id == 1)
                          <button class="btn btn-danger btn-xs archive" data-bs-toggle="modal" data-bs-target="#statusModal" value="{{$proj->id}}">Archive</button>
                          <button class="btn btn-default btn-xs completed" data-bs-toggle="modal" data-bs-target="#completedModal" value="{{$proj->id}}">Completed</button>
                          <a href="{{route('admin_task_list',$proj->id)}}" class="btn btn-warning btn-xs">View</a>
                          <button class="btn btn-primary btn-xs assign" data-bs-toggle="modal" data-bs-target="#assignsModal" value="{{$proj->id}}">Assign Department</button>
                        @elseif($proj->status_id == 0)
                          <button class="btn btn-success btn-xs archive" data-bs-toggle="modal" data-bs-target="#statusModal" value="{{$proj->id}}">Activate</button>
                        @endif

                      </td>

                    </tr>

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
          <h4 class="modal-title">Project Informations</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form role="form" action="{{route('admin_create_projects')}}" method="POST">
        <!-- Modal body -->
        <div class="modal-body">

        @csrf
        <div class="mb-3">
          <label>Project Name</label>
          <input type="text" class="form-control" placeholder="Name" aria-label="Name" name="title" required id="name">
        </div>
        <div class="mb-3">
          <label>Project Description</label>
          <textarea class="form-control" name="description" required></textarea>
        </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn bg-gradient-primary">Submit</button>
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
          <h4 class="modal-title">Project Informations</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form role="form" action="{{route('admin_update_projects')}}" method="POST">
        <!-- Modal body -->
        <div class="modal-body">

        @csrf
        <input type="hidden" name="project_id" id="updateProjectId">
        <div class="mb-3">
          <label>Project Name</label>
          <input type="text" class="form-control" placeholder="Name" aria-label="Name" name="title" required id="editTitle">
        </div>
        <div class="mb-3">
          <label>Project Description</label>
          <textarea class="form-control" name="description" required id="editDescription"></textarea>
        </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn bg-gradient-primary">Submit</button>
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
          <h4 class="modal-title">Change Status?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form role="form" action="{{route('admin_change_projects_status')}}" method="POST">
        <!-- Modal body -->
        <div class="modal-body">

        @csrf
        <input type="hidden" name="project_id" id="statusProjectId">
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn bg-gradient-primary">Yes</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
        </form>

      </div>
    </div>
  </div>

  <div class="modal" id="completedModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Are you sure everything is completed?</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form role="form" action="{{route('admin_completed_project')}}" method="POST">
        <!-- Modal body -->
        <div class="modal-body">

        @csrf
        <input type="hidden" name="project_id" id="completedProject">
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn bg-gradient-primary">Yes</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
        </form>

      </div>
    </div>
  </div>

    <div class="modal" id="assignsModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Assign Project</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <form role="form" action="{{route('admin_assign_project')}}" method="POST">
        <!-- Modal body -->
        <div class="modal-body">

        @csrf
        <input type="hidden" name="project_id" id="assignTask">
        <div class="form-group">
          <label>Select Department</label>
          <select class="form-control" name="department_id" required>
            <option></option>
            @foreach($departments as $dept)
              <option value="{{$dept->id}}">{{$dept->name}}</option>
            @endforeach
          </select>
        </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn bg-gradient-primary">Yes</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
        </form>

      </div>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script src="{{URL::to('/assets/js/core/popper.min.js')}}"></script>
  <script src="{{URL::to('/assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{URL::to('/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{URL::to('/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
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
  <script src="{{URL::to('/assets/js/argon-dashboard.min.js?v=2.0.4')}}"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      var find_project_url = "{{route('admin_find_projects')}}";
      var token = "{{Session::token()}}";

      $(".archive").click(function(){
        var project_id = $(this).val();
        $("#statusProjectId").val(project_id);

      });

      $(".completed").click(function(){
        var project_id = $(this).val();
        $("#completedProject").val(project_id);

      });

      $(".assign").click(function(){
        var project_id = $(this).val();
        $("#assignTask").val(project_id);

      });

      $(".updateProject").click(function(){
          var project_id = $(this).val();
          $("#updateProjectId").val(project_id);
          $.ajax({
           type:'POST',
           url:find_project_url,
           data:{_token: token, project_id : project_id},
           success:function(data) {
              console.log(data);
              $("#editTitle").val(data.title);
              $("#editDescription").val(data.description);

           }
        });


      });

    });
  </script>
</body>

</html>

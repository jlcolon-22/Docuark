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
                  <a class="nav-link " href="{{route('admin_task_list',Request::segment(2))}}">Task List</a>
                </li>
                @if (Auth::user()->getRoleNames()[0] != 'manager')
                <li class="nav-item ">
                    <a class="nav-link active" href="{{route('admin_task_file_list',Request::segment(2))}}">Task Files</a>
                  </li>
                  @endif
                <li class="nav-item">
                  <a class="nav-link" href="{{route('admin_task_schedule_list',Request::segment(2))}}">Task Schedule</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('admin_task_timeline_list',Request::segment(2))}}">Timeline of activities</a>
                </li>

              </ul>
              <br>



              <h3 class="text-center">{{$find_assign_project->project->project_type}} {{isset($find_assign_project->department->name) ? 'Report of '.$find_assign_project->department->name : 'None'}}</h3>

              @include('shared.notification')
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>


                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>

                    </tr>
                  </thead>
                  <tbody>
                    @foreach($folders as $folder)
                    <tr>

                      <td>
                        <div class="d-flex px-2 py-1">

                          <div class="d-flex flex-column justify-content-center">
                            <i class="ni ni-folder-17" style="font-size: 50px;"></i>
                            <h6 class="mb-0 text-sm">{{$folder->folder_name}}</h6>

                          </div>
                        </div>
                      </td>

                      <td class="align-middle text-center">
                        <a href="{{route('admin_folders_files',$folder->id)}}" class="btn text-white btn-xs" style="background:#000000">View</a>
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
      var find_project_url = "{{route('admin_find_task')}}";
      var token = "{{Session::token()}}";

      $(".archive").click(function(){
        var task_id = $(this).val();
        $("#statusProjectId").val(task_id);

      });

       $(".delete").click(function(){
        var task_id = $(this).val();
        $("#deleteProjectId").val(task_id);

      });

      $(".assign").click(function(){
        var task_id = $(this).val();
        $("#assignTask").val(task_id);

      });

      $(".updateProject").click(function(){
          var task_id = $(this).val();
          $("#updateProjectId").val(task_id);
          $.ajax({
           type:'POST',
           url:find_project_url,
           data:{_token: token, task_id : task_id},
           success:function(data) {
              console.log(data);
              $("#editTitle").val(data.title);
              $("#editDescription").val(data.description);
              $("#deadline_get").val(data.deadline);

           }
        });


      });

      $("#arrangeSelect").change(function(){
        var arrange = $(this).val();
        $("#arrangeForm").submit();
      });

    });
  </script>
</body>

</html>

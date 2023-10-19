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
              <div class="d-flex justify-content-between">
                <h6>List of Files</h6>
                <a href="/admin/{{ $project_id }}/task-files" class="btn text-white btn-xs" style="background:#000000">Back </a>
              </div>

              @include('shared.notification')
            </div>
            <div class="card-body px-0 pt-0 pb-2">
             <div class="container-fluid">
                    @foreach($folder_files as $file)
                      <form action="{{route('download_task')}}" method="POST">
                        @csrf

                        <input type="hidden" name="url" value="{{$file->file_name}}">
                        <p>
                          <?php
                            $file_name = explode("/",$file->file_name);
                            echo $file_name[1];
                          ?>

                        </p>
                        <button type="submit" class="btn btn-dark btn-xs">DOWNLOAD</button>

                         @if($file->user_id == Auth::id())
                         {{-- href="{{route('delete_files',$file->id)}}" --}}
                         <button type="button" class="btn btn-danger btn-xs remove-file"  data-bs-toggle="modal"
                        data-bs-target="#deleteFile"
                        value="{{ $file->id }}">
                          REMOVE
                        </button>
                        @endif
                      </form>

                    @endforeach
                  </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </main>
  <div class="modal" id="deleteFile">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title h6">Are you sure to delete?               </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form role="form" action="{{ route('delete_files') }}" method="POST">
                @csrf
                <!-- Modal body -->
                <div class="modal-body">

                    @csrf
                    <input type="hidden" name="id" id="remove_file_id">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger text-white">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
      $(".remove-file").click(function(){
          var file_id = $(this).val();
          $("#remove_file_id").val(file_id);
      });
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

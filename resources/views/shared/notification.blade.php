@if(Session::has('success'))
	<div class="alert  text-white text-bold " style="background: #2CCB8C;with">
		{{Session::get('success')}}
	</div>
@endif

@if(Session::has('error'))
	<div class="alert alert-danger">
		{{Session::get('error')}}
	</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@component('mail::message')

<h5>User:</h5> {{strtolower($data['user'])}}
<br>
<br>
<h5>Task:</h5> {{strtolower($data['task'])}}
<br>
<br>
<h5>Filename:</h5> {{strtolower($data['filename'])}}
<br>
<br>
<h5>Type:</h5> {{$data['type']}}
@endcomponent


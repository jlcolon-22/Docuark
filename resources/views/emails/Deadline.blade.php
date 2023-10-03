
@component('mail::message')

<h5>Type:</h5> {{strtolower($data['type'])}}
<br>
<br>
<h5>Title:</h5> {{strtolower($data['title'])}}
<br>
<br>
<h5>Deadline:</h5> {{$data['deadline']}}
@endcomponent



@component('mail::message')

<h5>User:</h5> {{strtolower($data['user'])}}
<br>
<br>
<h5>Task:</h5> {{strtolower($data['title'])}}
<br>
<br>
<h5>Comment:</h5> {{$data['comment']}}
@endcomponent


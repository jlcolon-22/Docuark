

@component('mail::panel')

User:
<br>
{{strtolower($data['user'])}}
<br>
<br>
Task:
<br>
{{strtolower($data['title'])}}
<br>
<br>
comment:
<br>
{{$data['comment']}}
@endcomponent


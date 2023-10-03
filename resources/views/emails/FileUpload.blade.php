
@component('mail::panel')

User:
<br>
{{strtolower($data['user'])}}
<br>
<br>
Filename:
<br>
{{strtolower($data['filename'])}}
<br>
<br>
Type:
<br>
{{$data['type']}}
@endcomponent


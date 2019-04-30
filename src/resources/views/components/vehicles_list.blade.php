<table>
@foreach ($user['vehicles'] as $vehicle)
    <tr><td>{{$vehicle['title']}}</td><td>{{$vehicle['name']}}</td></tr>
@endforeach
</table>
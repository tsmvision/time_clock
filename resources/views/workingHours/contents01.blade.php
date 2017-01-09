<!-- Main component for a primary marketing message or call to action -->

<h2>Working Hours</h2>
<table class="table table-responsive table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th> Period</th>
        <th> JJAN ID</th>
        <th> Working Minutes</th>
        <th> breakMinutes</th>
        <th> totalMinutes </th>

    </tr>
    </thead>
    <?php $count = 1 ?>
    <tbody>
    @foreach($result as $result1)
        <tr>
            <td> {{$count++}} </td>
            <td> @if ($getSearchPeriod == null) Today @else {{$getSearchPeriod}} @endif</td>
            <td> {{$result1['jjanID']}} </td>
            <td> {{$result1['workingHours']}} </td>
            <td> {{--$result1['breakMinutes']--}} </td>
            <td> {{-- $result['totalMinutes'] --}} </td>

        </tr>
    @endforeach

    </tbody>
</table>

{{-- $list->links() --}}
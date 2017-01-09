<!-- Main component for a primary marketing message or call to action -->

<h2>Working Hours</h2>
<table class="table table-responsive table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th> Period</th>
        <th> JJAN ID</th>
        <th> Firstname</th>
        <th> Lastname</th>
        <th> Working Hours </th>

    </tr>
    </thead>
    <?php $count = 1 ?>
    <tbody>
    @foreach($result as $result)
        <tr>
            <td> {{$count++}} </td>
            <td> @if ($getSearchPeriod == null) Today @else {{$getSearchPeriod}} @endif</td>
            <td> {{$result1['jjanID']}} </td>
            <td> {{$result1['workingMinutes']}} </td>
            <td> {{$result1['breakMinutes']}} </td>
            <td> {{ $result['totalMinutes'] }} hours </td>

        </tr>
    @endforeach

    </tbody>
</table>

{{-- $list->links() --}}
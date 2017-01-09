<!-- Main component for a primary marketing message or call to action -->

<h2>Working Hours</h2>
<table class="table table-responsive table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th> Period</th>
        <th> JJAN ID</th>
        <th> Hours in Work</th>
        <th> Hours in Break</th>
        <th> Total Hours in Work </th>

    </tr>
    </thead>
    <?php $count = 1 ?>
    <tbody>
        <tr>
            <td> {{$count++}} </td>
            <td> @if ($getSearchPeriod == null) Today @else {{$getSearchPeriod}} @endif</td>
            <td> {{$currentUserInfo['jjanID']}} </td>
            <td> {{$workingHours}} </td>
            <td> {{$totalBreakHours}} </td>
            <td> {{$totalWorkingHours}} </td>
        </tr>

    </tbody>
</table>

{{-- $list->links() --}}
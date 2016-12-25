<!-- Main component for a primary marketing message or call to action -->
<h2>History</h2>
<table class="table table-responsive">
    <thead>
    <tr>
        <th>#</th>
        <th> JJAN ID</th>
        <th> Firstname</th>
        <th> Lastname</th>
        <th> Date </th>
        <th> Time</th>
        <th></th>
    </tr>
    </thead>
    <?php $count = 1 ?>
    <tbody>
    @foreach($history as $history1)
        <tr>
            <td> {{$count++}} </td>
            <td> {{$history1->jjanID}} </td>
            <td> {{$history1->firstNm}} </td>
            <td> {{$history1->lastNm}} </td>
            <td> {{\Carbon\Carbon::parse($history1->clockTime)->format('H:m:s a')}} </td>
            <td> {{\Carbon\Carbon::parse($history1->clockTime)->format('m/d/Y')}} </td>
            <td>
                <a type="button" class="btn btn-primary btn-sm" href="#">Modify</a>
                <a type="button" class="btn btn-danger btn-sm" href="#">Delete</a>

            </td>
        </tr>
    @endforeach

    </tbody>
</table>
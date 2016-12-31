<!-- Main component for a primary marketing message or call to action -->

<h2>History</h2>
<table class="table table-responsive table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th> JJAN ID</th>
        <th> Firstname</th>
        <th> Lastname</th>
        <th> Time </th>
        <th> Date</th>
        <th> Punch Type</th>
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
            <td> {{\Carbon\Carbon::parse($history1->punchTime)->format("h:i a")}} </td>
            <td> {{\Carbon\Carbon::parse($history1->punchDate)->format('m/d/Y')}} </td>
            <td> {{$punchType[$history1->punchType]}}</td>
            <td>
                <a type='button' class="btn btn-lg btn-primary btn-sm" data-toggle="modal" data-target="#modify" role="button"> Modify</a>
                <a type='button' class="btn btn-lg btn-danger btn-sm" data-toggle="modal" data-target="#delete" role="button"> Delete</a>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>

{{-- $history->links() --}}
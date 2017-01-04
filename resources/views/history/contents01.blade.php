<!-- Main component for a primary marketing message or call to action -->

<form>
    <div class="form-group">
    <h2>History</h2>
    </div>
    <div class="form-group">
        <a type='button' class="btn btn-primary" data-toggle="modal" data-target="#manualPunch" role="button">Punch Manually</a>
    </div>
</form>

<table class="table table-responsive table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th> Date</th>
        <th> Time</th>
        <th> JJAN ID</th>
        <th> Firstname</th>
        <th> Lastname</th>
        <th> Punch Type</th>
        <th></th>
    </tr>
    </thead>
    <?php $count = 1 ?>
    <tbody>
    @foreach($history as $history1)
        <tr>
            <td> {{$count++}} </td>
            <td> {{\Carbon\Carbon::parse($history1->punchDate)->format('m/d/Y')}} </td>
            <td> {{\Carbon\Carbon::parse($history1->punchTime)->format("h:i a")}} </td>
            <td> {{$history1->jjanID}} </td>
            <td> {{$history1->firstNm}} </td>
            <td> {{$history1->lastNm}} </td>
            <td> {{$punchType[$history1->punchType]}}</td>
            <td>
                <a type='button' class="btn btn-lg btn-danger btn-sm" data-toggle="modal"
                   data-target="#delete{{$history1->id}}" role="button"> Delete</a>
            </td>
        </tr>
        @include('admin.history.modal')
    @endforeach

    </tbody>
</table>

{{-- $history->links() --}}
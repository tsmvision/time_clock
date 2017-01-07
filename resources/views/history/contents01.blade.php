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
        <th> Daily Order</th>
        <th> In / Out</th>
        <th> Time</th>
        <th></th>
    </tr>
    </thead>
    <?php $count = 1 ?>
    <tbody>
    @foreach($history as $history1)
        <tr>
            <td> {{$count++}} </td>
            <td> {{\Carbon\Carbon::parse($history1['punchDate'])->format('m/d/Y')}} </td>
            <td> {{$history1['dailyOrder']}}</td>
            <td> @if ($history1['dailyOrder'] %2 == 1) In @else Out @endif</td>

            <td> {{\Carbon\Carbon::parse($history1['punchTime'])->format("h:i a")}} </td>
            <td>
                <a type='button' class="btn btn-lg btn-primary btn-sm" data-toggle="modal"
                   data-target="#edit{{$history1['id']}}" role="button"> Edit</a>
                <a type='button' class="btn btn-lg btn-danger btn-sm" data-toggle="modal"
                   data-target="#delete{{$history1['id']}}" role="button"> Delete</a>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>

{{-- $history->links() --}}
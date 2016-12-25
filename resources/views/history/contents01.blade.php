<!-- Main component for a primary marketing message or call to action -->
<h2>History</h2>
<p>The .table class adds basic styling (light padding and only horizontal dividers) to a table:</p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>JJAN ID</th>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Punched At </th>
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
            <td> {{\Carbon\Carbon::parse($history1->clockTime)->format('m/d/Y')}} </td>
            <td> {{\Carbon\Carbon::parse($history1->clockTime)->format('H:m:s a')}} </td>
            <td>
                <a type="button" class="btn btn-primary btn-sm" href="#">Modify</a>
                <a type="button" class="btn btn-danger btn-sm" href="#">Delete</a>

            </td>
        </tr>
    @endforeach

    </tbody>
</table>
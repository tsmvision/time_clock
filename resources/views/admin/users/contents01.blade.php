<!-- Main component for a primary marketing message or call to action -->

<h2>Working Hours</h2>
<table class="table table-responsive table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th> JJAN ID</th>
        <th> Firstname</th>
        <th> Lastname</th>

    </tr>
    </thead>
    <?php $count = 1 ?>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td> {{$count++}} </td>
            <td> {{$user->jjanID}} </td>
            <td> {{$user->firstNm}} </td>
            <td> {{$user->lastNm}} </td>


        </tr>
    @endforeach

    </tbody>
</table>

{{-- $list->links() --}}
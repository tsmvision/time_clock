<!-- Main component for a primary marketing message or call to action -->

<h3>Users</h3>
<table class="table table-responsive table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th> JJAN ID</th>
        <th> Firstname</th>
        <th> Lastname</th>
        <th></th>

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
            <td>
                <a type='button' class="btn btn-lg btn-primary btn-sm" data-toggle="modal" data-target="#modify" role="button"> Modify</a>
                <a type='button' class="btn btn-lg btn-danger btn-sm" data-toggle="modal" data-target="#delete" role="button"> Delete</a>
            </td>


        </tr>
    @endforeach

    </tbody>
</table>

{{-- $list->links() --}}
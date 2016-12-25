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
    @foreach($list as $list1)
        <tr>
            <td> {{$count++}} </td>
            <td> {{$getSearchPeriod}}</td>
            <td> {{$list1->jjanID}} </td>
            <td> {{$list1->firstNm}} </td>
            <td> {{$list1->lastNm}} </td>
            <td></td>

        </tr>
    @endforeach

    </tbody>
</table>

{{ $list->links() }}
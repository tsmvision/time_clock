<!-- Main component for a primary marketing message or call to action -->
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
<div class="jumbotron">
    <h3>Current Time is <span id="txt"></span></h3>

    <p> Please clock-in and out when you begin and end work; and when leave and return from meal times.
    </p>
    <p>
        <a type='button' class="btn btn-lg btn-primary" data-toggle="modal" data-target="#modal01" role="button"> Punch In & Out Now</a>
    </p>
</div>

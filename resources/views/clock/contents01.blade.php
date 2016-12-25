<!-- Main component for a primary marketing message or call to action -->
<div class="jumbotron">
    <h3>Current Time is <span id="txt"></span></h3>

    <p> Please clock-in and out when you begin and end work; and when leave and return from meal times.
    </p>
    <p>
        <a type='button' class="btn btn-lg btn-primary" data-toggle="modal" data-target="#modal01" role="button"> Punch In & Out Now</a>
    </p>
</div>
<!-- Modal -->
<div id="modal01" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Do you want to punch now?</h4>
            </div>
            <div class="modal-body">
                <p><a type="button" class="btn btn-default" href="{{url('/punchNow')}}">Yes, Punch it now</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Later</button>
            </div>
        </div>

    </div>
</div>
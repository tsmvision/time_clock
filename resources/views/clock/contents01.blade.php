<!-- Main component for a primary marketing message or call to action -->
<form method="post" id="punchNow" name="punchNow" action='{{url('punchNow')}}'>
    {{ csrf_field() }}
<div class="jumbotron">
    <h3>Current Time is <span id="txt"></span></h3>

    <p></p>
        <div class="form-group">
            <button type='submit' class="btn btn-lg btn-primary">
                Punch Now
            </button>
        </div>
</div>
</form>

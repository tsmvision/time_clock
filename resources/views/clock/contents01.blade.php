<!-- Main component for a primary marketing message or call to action -->

<div class="jumbotron">
    <h3>Current Time is <span id="txt"></span></h3>

    <p> Please clock-in and out when you begin and end work; and when leave and return from meal times.
    </p>
    <form class="form-inline">
        <div class="form-group">
            <a type='button' class="btn btn-lg btn-primary" data-toggle="modal" data-target="#startWork" role="button">
                Start
                Work</a>
        </div>
        <div class="form-group">
            <a type='button' class="btn btn-lg btn-danger" data-toggle="modal" data-target="#endWork" role="button"> End
                Work</a>
        </div>
        <div class="form-group">
            <a type='button' class="btn btn-lg btn-primary" data-toggle="modal" data-target="#startMeal" role="button">
                Start
                Meal Break</a>
        </div>
        <div class="form-group">
            <a type='button' class="btn btn-lg btn-danger" data-toggle="modal" data-target="#endMeal" role="button"> End
                Meal Break</a>
        </div>
    </form>
</div>

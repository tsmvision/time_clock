<!-- Main component for a primary marketing message or call to action -->

<div class="jumbotron">
    <h3>Current Time is <span id="txt"></span></h3>

    <h3>Please clock-in and out when you begin and end work; and when leave and return from meal times.</h3>
        <h5> Start Work and End Work is available once a day.</h5>
        <h5> 'Meal Break' and 'Leave office' are Available 4 times a day for Lunch and Dinner.</h5>
        <p></p>
        <form class="form-inline">
            <div class="form-group">
                <a type='button' class="btn btn-lg btn-primary" data-toggle="modal" data-target="#startWork"
                   role="button">
                    Start
                    Work</a>
            </div>
            <div class="form-group">
                <a type='button' class="btn btn-lg btn-danger" data-toggle="modal" data-target="#endWork" role="button">
                    End
                    Work</a>
            </div>
            <div class="form-group">
                <a type='button' class="btn btn-lg btn-primary" data-toggle="modal" data-target="#startMeal"
                   role="button">
                    Leave Office temporarily
                    </a>
            </div>
            <div class="form-group">
                <a type='button' class="btn btn-lg btn-danger" data-toggle="modal" data-target="#endMeal" role="button">
                    Back to Office</a>
            </div>
        </form>
</div>

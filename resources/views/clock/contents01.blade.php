<!-- Main component for a primary marketing message or call to action -->

<div class="jumbotron">
    <h3>Current Time is <span id="txt"></span></h3>

    <h3>Please clock-in and out when you begin and end work; and when leave and return to office.</h3>
        <h5> Click 'Start Work' when you start to work today. It's only available once a day</h5>
        <h5> Click 'End Work' When you finish your work today. It's only available once a day</h5>
    <h5> Click 'Leave Office' when you leave office for some reason like meal, personal job and etc. It's availble up to 6 times a day.</h5>
    <h5> Click 'Back to office' when you come back to office from your personal jobs. It's available up to 6 times a day.</h5>
    <h5> You are not allowed to register 'Leave Office' or 'Back to Office' after 'End Work'.</h5>
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
                    Leave Office
                    </a>
            </div>
            <div class="form-group">
                <a type='button' class="btn btn-lg btn-danger" data-toggle="modal" data-target="#endMeal" role="button">
                    Back to Office</a>
            </div>
        </form>
</div>

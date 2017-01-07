<!-- Modal -->

<form method="POST" action="{{url('history/add')}}">
    {{ csrf_field() }}

    <div id="manualPunch" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Manual Punch</h4>

                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label>Date: (mm/dd/YYYY)</label>
                            <input type="text" class="form-control" id="getDate" name="getDate">
                        </div>
                        <div class="form-group">
                            <label>Time(24hr type - hh:mm )</label>
                            <input type="text" class="form-control" id="getTime" name="getTime">
                        </div>
                    <p>

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Yes,
                        Save & Close</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>

</form>
{{--

@foreach($history as $history1)
    <form method="POST" action="{{url('history/update')}}">
        {{ csrf_field() }}

        <input type="hidden" id="getID" name="getID" value="{{$history1->id}}">
        <div id="edit{{$history1->id}}" class="modal fade" role="dialog">
            {{$id = $history1->id}}

            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Manual Punch</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">

                        <div class="form-group">
                            <label for="usr">Date:</label>
                            {{\Carbon\Carbon::parse($history1->punchDate)->format('m/d/Y')}}
                        </div>
                        <div class="form-group">
                            <label for="usr">Time(24hr type - hh:mm )</label>
                            <input type="text" class="form-control" id="punchTime" name="punchTime"
                                   placeholder="{{\Carbon\Carbon::parse($history1->punchTime)->format('H:m')}}">
                        </div>

                        <p>

                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Yes,
                            Save & Close
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>

            </div>
        </div>
    </form>


@endforeach


@foreach($history as $history1)
    <div id="delete{{$history1->id}}" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Do you want to delete this now?</h4>

                </div>
                <div class="m
odal-body">
                    <p>
                    <h4>{{\Carbon\Carbon::parse($history1->punchTime)->format("h:i:s a")}}
                        {{\Carbon\Carbon::parse($history1->punchTime)->format('m/d/Y')}}</h4>
                    </p>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger"
                       href="{{url('history/delete')}}/{{$history1->id}}">Yes,
                        Proceed to delete this</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
@endforeach

@foreach($history as $history1)
    <div id="delete{{$history1->id}}" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Do you want to delete this now?</h4>

                </div>
                <div class="modal-body">
                    <p>
                    <h4>{{\Carbon\Carbon::parse($history1->punchTime)->format("h:i:s a")}}
                        {{\Carbon\Carbon::parse($history1->punchDate)->format('m/d/Y')}}</h4>
                    </p>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger"
                       href="{{url('history03')}}/{{$history1->id}}">Yes,
                        Proceed to delete this</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
@endforeach

--}}
<!-- Modal -->
@foreach($history as $history1)
    <div id="modify{{$history1->id}}" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modify history</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        JJAN ID: {{$history1->jjanID}}
                    </div>
                    <div class="form-group">
                        First Name: {{$history1->firstNm}}
                    </div>
                    <div class="form-group">
                        Last Name: {{$history1->lastNm}}
                    </div>
                    <div class="form-group">
                        Punch Type: {{$punchTypeName[$history1->id]}}
                    </div>
                    <div class="form-group">
                        Punched Date: {{\Carbon\Carbon::parse($history1->punchDate)->format('m/d/Y')}}
                    </div>
                    <div class="form-group">
                        <label>Punched Time (24hr format):</label>
                        <input placeholder="{{\Carbon\Carbon::parse($history1->punchTime)->format('H:i')}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" href="{{url('history/update')}}/{{$history1->id}}"
                            name="updateID" id="updateID" value="{{$history1->id}}">Proceed to update
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>

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
                        {{\Carbon\Carbon::parse($history1->punchTime)->format('m/d/Y')}}</h4>
                    </p>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger" href="{{url('history/delete')}}/{{$history1->id}}">Yes,
                        Proceed to delete this</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
@endforeach
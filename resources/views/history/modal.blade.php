<!-- Modal -->

<div id="manualPunch" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Manual Punch</h4>

            </div>
            <div class="modal-body">
                <form>

                    <div class="form-group">
                        <label for="sel1">Punch Type</label>
                        <select class="form-control" id="punchType">
                            <option>Start Working</option>
                            <option>End Working</option>
                            <option>Leave Office / Back to Office</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="usr">Date:</label>
                        <input type="text" class="form-control" id="date">
                    </div>
                    <div class="form-group">
                        <label for="usr">Leave Office At</label>
                        <input type="text" class="form-control" id="time">
                    </div>

                    <div class="form-group">
                        <label for="usr">Back to Office At</label>
                        <input type="text" class="form-control" id="time">
                    </div>

                </form>
                <p>

                </p>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-danger" href="#">Yes,
                    Save & Close</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>

    </div>
</div>


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
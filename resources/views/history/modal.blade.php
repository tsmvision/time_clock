<!-- Modal -->
@foreach($history as $history1)
    <div id="modify" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Do you want to punch now?</h4>
                </div>
                <div class="modal-body">
                    <p>
                        <button type="button" class="btn btn-danger" href="{{url('history/update')}}"
                                name="updateID" id="updateID" value="{{$history1->id}}">Proceed to update
                        </button>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>

    <div id="delete" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Do you want to delete this now?</h4>
                </div>
                <div class="modal-body">
                    <p>
                    <form method="get" action="{{url('history/delete')}}">
                        <button type="submit" class="btn btn-danger" name="deleteID" id="deleteID" value="{{$history1->id}}">Yes, Proceed to delete this</button>
                    </form>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
@endforeach
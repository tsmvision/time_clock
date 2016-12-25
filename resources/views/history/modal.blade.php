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
                        <button type="button" class="btn btn-primary btn-sm" href="{{url('/delete')}}"
                                name="{{$history1->id}}" id="{{$history1->id}}">Modify
                        </button>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Later</button>
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
                    <h4 class="modal-title">Do you want to punch now?</h4>
                </div>
                <div class="modal-body">
                    <p>
                        <button type="button" class="btn btn-danger btn-sm" href="{{url('/modify')}}"
                                name="{{$history1->id}}" id="{{$history1->id}}">Delete
                        </button>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Later</button>
                </div>
            </div>

        </div>
    </div>
@endforeach
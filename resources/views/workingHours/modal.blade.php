<!-- Modal -->
@foreach($list as $list1)
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
                        <button type="button" class="btn btn-danger" href="{{url('workingHours')}}"
                                name="updateID" id="updateID" value="{{$list1->id}}">Proceed to update
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
                    <h4>{{\Carbon\Carbon::parse($list1->punchTime)->format("h:i:s a")}}
                        {{\Carbon\Carbon::parse($list1->punchTime)->format('m/d/Y')}}</h4>
                    </p>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-danger" href="{{url('workingHours')}}/{{$list1->id}}">Yes,
                        Proceed to delete this</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
@endforeach
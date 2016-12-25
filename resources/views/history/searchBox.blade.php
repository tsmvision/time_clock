<p></p>
<form class='form-inline' method="post" id="centerMembers" name="centermembers"
      action='{{url('/history')}}'
      id="centerMembers" name="centerMembers" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <select class="form-control" id="getSearchPeriod" name="getSearchPeriod">
            <option value="this_month"
                    @if ($getSearchPeriod == null or $getSearchPeriod == '0' or $getSearchPeriod == 'this_month' ) selected @endif>
                This Month
            </option>
            <option value="last_month">
                Last Month
            </option>
            <option value="today" @if ($getSearchPeriod == 'today' ) selected @endif> 2 Months Ago</option>
            <option value="thisWeek"> This Week</option>
            <option value="thisMonth"> This Month</option>
            <option value="custom_period"> Custom Period</option>
        </select>
    </div>

    <div class="form-group">
        <select class="form-control" id="getSearchJJANID" name="getSearchJJANID">
            <option>JJAN ID - All</option>
            <option>2</option>
        </select>
    </div>

    <div class="form-group">
        <div class="col-xs-16">
            <input type="text" class="form-control"
                   placeholder=" Type Member's Name" value="@if(old('getMemberName')) {{ $getMemberName }} @endif" name="getMemberName">
        </div>
    </div>

    <button type="submit" class="btn btn-default">Search</button>
</form>
{{--
<hr>
<form class='form-inline'>
    <div class="form-group">
        <a href="{{URL("member/list/healingLife/registerNewBellyButtonTrainer")}}" class="btn btn-default btn-sm" role="button"> Register New Belly Button Trainer </a>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-default  btn-sm">Export to Excel</button>
    </div>

</form> --}}
<hr>
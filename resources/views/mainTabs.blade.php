
<ul class="nav nav-tabs">
    <li @if ($currentUrl =='clock') class="active" @endif><a href="{{url('/clock')}}">Clock</a></li>
    <li @if ($currentUrl == 'history' or $currentUrl == 'history/list')class="active" @endif><a href="{{url('/history')}}">History</a></li>
</ul>
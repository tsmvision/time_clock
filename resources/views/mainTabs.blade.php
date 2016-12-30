
<ul class="nav nav-tabs">
    <li @if ($currentUrl ==='/' or $currentUrl ==='clock') class="active" @endif><a href="{{url('/clock')}}">Clock</a></li>
    <li @if ($currentUrl == 'history')class="active" @endif><a href="{{url('/history')}}">History</a></li>
    <li @if ($currentUrl == 'workingHours')class="active" @endif><a href="{{url('/workingHours')}}">Working Hours</a></li>
    <li @if ($currentUrl == 'Admin')class="active" @endif><a href="{{url('/Admin')}}">Admin</a></li>
</ul>
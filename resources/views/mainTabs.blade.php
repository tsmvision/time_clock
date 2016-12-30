
<ul class="nav nav-tabs">
    <li @if ($currentUrl ==='/' or $currentUrl ==='clock') class="active" @endif><a href="{{url('/clock')}}">Clock</a></li>
    <li @if ($currentUrl == 'history')class="active" @endif><a href="{{url('/history')}}">History</a></li>
    <li @if ($currentUrl == 'workingHours')class="active" @endif><a href="{{url('/workingHours')}}">Working Hours</a></li>
    <li @if ($currentUrl == 'historyForAdmin')class="active" @endif><a href="{{url('/historyForAdmin')}}">History For Admin</a></li>
    <li @if ($currentUrl == 'workingHoursForAdmin')class="active" @endif><a href="{{url('/workingHoursForAdmin')}}">Working Hours For Admin</a></li>
</ul>
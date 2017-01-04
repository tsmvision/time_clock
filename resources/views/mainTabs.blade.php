
<ul class="nav nav-tabs">
    <li @if ($currentUrl ==='/' or $currentUrl ==='clock') class="active" @endif><a href="{{url('/clock')}}">Clock</a></li>
    <li @if ($currentUrl == 'history')class="active" @endif><a href="{{url('/history')}}">History</a></li>
    <li @if ($currentUrl == 'workingHours')class="active" @endif><a href="{{url('/workingHours')}}">Working Hours</a></li>

    @if ($currentUserInfo['userType'] == 'admin')
    <li @if ($currentUrl === 'admin' or $currentUrl === 'admin/history' or $currentUrl ==='admin/workingHours' )class="active" @endif><a href="{{url('/admin')}}">Admin</a></li>
    @endif
</ul>
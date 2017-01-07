<p></p>
<div class="form-group">
<ul class="nav nav-pills">
    <li @if ($currentUrl === 'history03' or $currentUrl === 'admin')class="active"@endif><a href="{{url('history03')}}">History</a></li>
    <li @if ($currentUrl === 'admin/workingHours')class="active"@endif><a href="{{url('/admin/workingHours')}}">Working Hours</a></li>
    <li @if ($currentUrl === 'admin/users')class="active"@endif><a href="{{url('/admin/users')}}">Users</a></li>
    <li @if ($currentUrl === 'register')class="active"@endif><a href="{{url('/register')}}">Register User</a></li>
</ul>
</div>
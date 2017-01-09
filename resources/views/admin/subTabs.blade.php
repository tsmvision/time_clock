<p></p>
<div class="form-group">
<ul class="nav nav-pills">
    <li @if ($currentUrl === 'admin/history' or $currentUrl === 'admin')class="active"@endif><a href="{{url('admin/history')}}">History</a></li>
    <li @if ($currentUrl === 'admin/workingHours')class="active"@endif><a href="{{url('/admin/workingHours')}}">Working Hours</a></li>
    <li @if ($currentUrl === 'admin/users')class="active"@endif><a href="{{url('/admin/users')}}">Users</a></li>
    <li @if ($currentUrl === 'register')class="active"@endif><a href="{{url('/register')}}">Register User</a></li>
</ul>
</div>
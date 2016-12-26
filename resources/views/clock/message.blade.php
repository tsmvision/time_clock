@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if (session('message1'))
    <div class="alert alert-danger">
        {{ session('message1') }}
    </div>
@endif
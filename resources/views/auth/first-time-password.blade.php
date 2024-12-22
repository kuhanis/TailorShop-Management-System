@extends('layouts.auth')
@section('card-subtitle')
Please Change Your Password
@endsection

@section('content')
<form class="form-horizontal" method="post" action="{{ route('first.time.password.change') }}" validate>
    @csrf
    <fieldset class="form-group position-relative has-icon-left">
        <input type="password" name="password" class="form-control" id="new-password" placeholder="Enter New Password" required>
        <div class="form-control-position">
            <i class="la la-key"></i>
        </div>
    </fieldset>
    <fieldset class="form-group position-relative has-icon-left">
        <input type="password" name="password_confirmation" class="form-control" id="confirm-password" placeholder="Confirm New Password" required>
        <div class="form-control-position">
            <i class="la la-key"></i>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-outline-info btn-block"><i class="ft-lock"></i> Change Password</button>
</form>
@endsection

@section('scripts')
<script>
    window.onload = function() {
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, null, window.location.href);
        };
    }
    
    // Prevent back button
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>
@endsection

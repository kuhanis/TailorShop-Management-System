@extends('layouts.auth')
@section('title', 'Login')

@section('card-subtitle')
Enter Username And Password To Login
@endsection

@section('content')

@if (session('loginError'))
    <x-alerts.danger :error="session('loginError')" />
@endif

<form class="form-horizontal" method="post" action="{{route('login')}}" validate>
    @csrf
    <fieldset class="form-group position-relative has-icon-left">
        <input name="username" value="{{old('username')}}" type="text" class="form-control" id="user-name" placeholder="Your Username" >
        <div class="form-control-position">
            <i class="la la-user"></i>
        </div>
    </fieldset>
    <fieldset class="form-group position-relative has-icon-left">
        <div class="position-relative">
            <input type="password" value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>" name="password" class="form-control password-input" id="user-password" placeholder="Enter Password" >
            <div class="form-control-position">
                <i class="la la-key"></i>
            </div>
            <div class="form-control-position" style="right: 0;">
                <i class="la la-eye-slash toggle-password" style="cursor: pointer;"></i>
            </div>
        </div>
    </fieldset>
    <div class="form-group row">
        <div class="col-sm-6 col-12 text-center text-sm-left pr-0">
            <fieldset>
                <input name="remember" type="checkbox" <?php if(isset($_COOKIE['user'])){echo 'checked';} ?>  id="remember-me" class="chk-remember">
                <label for="remember-me"> Remember Me</label>
            </fieldset>
        </div>
        <div class="col-sm-6 col-12 float-sm-left text-center text-sm-right"><a href="{{route('reset-password')}}" class="card-link">Forgot Password?</a></div>
    </div>
    <button name="login" type="submit" class="btn btn-outline-info btn-block"><i class="ft-unlock"></i> Login</button>
</form>
@endsection

@section('scripts')
<script>
    function initPasswordToggle() {
        const toggleBtn = document.querySelector('.toggle-password');
        const pwdField = document.getElementById('user-password');
        
        if(toggleBtn && pwdField) {
            toggleBtn.onclick = function(e) {
                e.preventDefault();
                if(pwdField.type === 'password') {
                    pwdField.type = 'text';
                    toggleBtn.classList.remove('la-eye-slash');
                    toggleBtn.classList.add('la-eye');
                } else {
                    pwdField.type = 'password';
                    toggleBtn.classList.remove('la-eye');
                    toggleBtn.classList.add('la-eye-slash');
                }
            }
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', initPasswordToggle);

    // Keep your existing window.onload code
    window.onload = function() {
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, null, window.location.href);
        };
    }
</script>
@endsection


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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var togglePassword = document.querySelector('.toggle-password');
        var passwordInput = document.querySelector('#user-password');

        togglePassword.addEventListener('click', function() {
            // Toggle type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            this.classList.toggle('la-eye');
            this.classList.toggle('la-eye-slash');
        });
    });

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


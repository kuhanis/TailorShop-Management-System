@extends('layouts.auth')
@section('title', 'Reset Password')

@section('card-subtitle')
Enter your new password
@endsection

@section('content')
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<form class="form-horizontal" method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <fieldset class="form-group position-relative has-icon-left">
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" placeholder="New Password" required>
        <div class="form-control-position">
            <i class="la la-key"></i>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </fieldset>

    <fieldset class="form-group position-relative has-icon-left">
        <input type="password" class="form-control" 
               id="password_confirmation" name="password_confirmation" 
               placeholder="Confirm Password" required>
        <div class="form-control-position">
            <i class="la la-key"></i>
        </div>
    </fieldset>

    <button type="submit" class="btn btn-outline-primary btn-lg btn-block">
        <i class="ft-unlock"></i> Reset Password
    </button>
</form>

@if (session('status'))
    <div class="alert alert-success mt-3">
        Your password has been reset successfully! 
        <a href="{{ route('login') }}" class="alert-link">Back to Login</a>
    </div>
@endif
@endsection 
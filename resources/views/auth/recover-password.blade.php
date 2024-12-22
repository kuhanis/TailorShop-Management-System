@extends('layouts.auth')
@section('card-subtitle')
We will send you a link to reset password.
@endsection

@section('content')
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form class="form-horizontal" action="{{ route('password.email') }}" method="POST">
    @csrf
    <fieldset class="form-group position-relative has-icon-left">
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="user-email" placeholder="Your Email Address" name="email" required>
        <div class="form-control-position">
            <i class="la la-envelope"></i>
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </fieldset>
    <button type="submit" class="btn btn-outline-primary btn-lg btn-block">
        <i class="ft-unlock"></i>
        Send Password Reset Link
    </button>
</form>
<div class="card-footer border-0">
<p class="float-sm-left text-center">
<p class="float-sm-right text-center">Remembered your password ? <a href="{{route('login')}}" class="card-link">Login</a></p>
</div>
@endsection


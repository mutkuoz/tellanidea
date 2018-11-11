@extends('layouts.app')

@section('stylesheets')
    <link href="{{ url('public/css/register.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="container">

        <form class="form-register" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Register new user</h2>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="">Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="">E-Mail Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="E-Mail Address" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password-confirm" class="">Confirm Password</label>
                <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn btn-lg btn-primary btn-block">
                Register
            </button>
            <a href="{{url('/login')}}">Sign-in as existing user</a>

        </form>

    </div>

@endsection

@extends('layouts.app')

@section('stylesheets')
    <link href="{{ url('public/css/login.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="container">

        <form class="form-signin" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Please sign in</h2>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif

            <label for="email" class="sr-only">Email address</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Email address" value="{{ old('username') }}" required autofocus>

            <label for="password" class="sr-only">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            @if(!Auth::check())
                <a href="{{url('/register')}}">Register new user</a>
            @endif
        </form>

    </div> <!-- /container -->
@endsection

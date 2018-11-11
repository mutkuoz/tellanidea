<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="{{asset('public/images/app-icon-76.png')}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Periscope</title>

    <!-- Styles -->
    <link rel="shortcut icon" href="{{{ asset('public/images/icon-periscope.png') }}}">
    <link href="{{ asset('public/css/app.css?v=1') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<!--    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <link href="public/css/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

    @yield('scripts')
    @yield('stylesheets')

</head>
<body class="pt-0">
    <div id="app">

        <nav class="navbar navbar-default navbar-expand-md fixed-top" style="height: 36px">
            @if(Auth::check())
                
                @if($showBackButton)
                    <a href="{{ url()->previous() }}"><img src="{{ asset('public/images/button-back.png') }}"></a>

                @endif
                
                @if($showChangeUserButton)
                <button class="btn btn-primary my-2 my-sm-0" type="submit" id="btn_ChangeActingUser">{{$actingUserName}}</button>
                @endif

                <ul class="navbar-nav ml-auto mr-auto">
                @if($showHomeButton)
                @if (!Request::is('home'))
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/')}}">
                        <i class="fas fa-home text-white"></i>
                    </a>
                </li>
                @endif
                @endif
                @if ($title!='')
                    <span class="page-title">{{$title}}</span>
                @endif

                </ul>
                @if($showSettingsButton)
                <ul class="navbar-nav pull-right">
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/admin')}}">
                            <i class="fas fa-cogs text-white"></i>
                        </a>
                    </li>
                </ul>
                @endif
                @if($showLogoutButton)
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/logout')}}">
                            <i class="fas fa-sign-out-alt text-white"></i>
                        </a>
                    </li>
                </ul>
                @endif
            @endif
        </nav>
        <div class="container-wrap" style="padding-top: 4.0rem">
            @yield('content')
        </div>
        <div id="debug"></div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}"></script>

    @if(Auth::check())
    <div class="modal" tabindex="-1" role="dialog" id="actingUserSelector">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select acting user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="div-userSelection">
                    @foreach ($possibleActingUsers as $user)
                        <button id="userSelection_{{$user['id']}}" userId="{{$user['id']}}" type="button" class="btn-userSelection btn {{ ($user['id'] == $actingUserId ? 'btn-primary' : 'btn-light') }} mt-2">{{$user['name']}}</button>
                    @endforeach
                    <form id="changeActingUser" method="POST" action="{{route('changeUser')}}">
                        {{ csrf_field() }}
                        <input class="form-control" id="actingUserId" name="actingUserId" type="hidden" value="{{$actingUserId}}">
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitUserChange">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</body>
</html>

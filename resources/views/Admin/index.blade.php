@extends('layouts.app')

@section('content')
    <div class="container-fluid m-2 p-2">
        <a href="{{url('kpis')}}">KPIs</a> <br>
        <a href="{{url('users')}}">Users</a> <br>
        <a href="{{url('accountplans/addActions')}}">Add actions</a> <br>

    </div>
@endsection
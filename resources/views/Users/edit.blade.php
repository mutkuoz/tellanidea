@extends('layouts.app')

@section('stylesheets')
    <link href="{{ url('public/css/users.edit.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">

        <!-- if there are creation errors, they will show here -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif

        <h2 class="form-signin-heading pt-2">Editing User: {{$user['id']}}</h2>

        {{ Form::model($user,array('route' => array('users.update', $user->id))) }}

        <?php
            $visibleLobs=(json_decode($user['extraProperties'],true))['visibleLobs'];
            if (!isset($visibleLobs))
                $visibleLobs=array();
        ?>

        @foreach($lobs as $lob)
            {{ Form::checkbox('visibleLobs[]', $lob['id'], in_array($lob['id'],$visibleLobs))}}
            {{ Form::label('Lob_'.$lob['id'], $lob['name'], array('class'=>''))}}<br>
        @endforeach

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        {{ Form::submit('Save', array('class' => 'btn pull-right')) }}
        <a href="{{url('users')}}" class="btn btn-default pull-left">Cancel</a>

        {{ Form::close() }}
    </div>


@endsection
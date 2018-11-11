@extends('layouts.app')

@section('content')
<br>
{{$success. 'opportunities created successfully'}}<br>

@foreach ($errors as $error)
    {{$error}}<br>
@endforeach

@endsection
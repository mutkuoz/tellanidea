@extends('layouts.app')

@section('scripts')
    <link rel="stylesheet" href="{{ asset('public/css/users.index.css')}}">
    <script src="{{asset('public/js/componentPageSelector.js')}}"></script>
    <script src="{{asset('public/js/users.index.js')}}"></script>

@endsection

@section('content')

    <div class="container-fluid m-0 p-0">

        <div class="card text-white ml-0 mt-0 card-50" style="background-color:#3097D1; border-radius: 0">
            <div class="card-header text-right pb-1">
                <h4>
                    @if ($users->currentPage() >= 3)
                        <a class="page-number" href="{{Request::url().'?'.http_build_query(Request::except('page')).'&page=1'}}" tabindex="-1">&laquo;</a>&nbsp;
                    @endif
                    @if ($users->currentPage() >= 2)
                        <a class="page-number" href="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='.($users->currentPage()-1)}}" tabindex="-1">&lsaquo;</a>&nbsp;
                    @endif
                    <span class="page-number" id="spanPageSelector" data-targetUrl="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='}}" ><a class="">{{$users->currentPage()}}/{{$users->lastPage()}}</a></span>&nbsp;
                    <span class="page-number" id="spanPageSelectorX" data-targetUrl="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='}}" ><a class="">{{$users->currentPage()}}/{{$users->lastPage()}}</a></span>&nbsp;

                    @if ($users->currentPage() < $users->lastPage())
                        <a class="page-number" href="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='.($users->currentPage()+1)}}" tabindex="-1">&rsaquo;</a>&nbsp;
                    @endif
                    @if ($users->currentPage() < $users->lastPage())
                        <a class="page-number" href="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='.($users->lastPage())}}" tabindex="-1">&raquo;</a>&nbsp;
                    @endif
                </h4>
            </div>
        </div>
        @foreach ($users as $user)
                <div class="card ml-0 mt-0 card-50" style="background-color: #eeeeee; border-radius: 0">
                    <div class="card-header">
                        <span class="float-rigth">
                        {{$user['name']}}</span>
                        <span class="pull-right">
                            <a href="{{url('users/'.$user['id'].'/edit')}}">Edit</a>
                        </span>
                    </div>
                </div>
        @endforeach
    </div>
@endsection
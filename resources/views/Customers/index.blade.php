@extends('layouts.app')

@section('scripts')
    <link rel="stylesheet" href="{{ asset('public/css/customers.index.css')}}">
    <script src="{{asset('public/js/componentPageSelector.js')}}"></script>
    <script src="{{asset('public/js/componentSearch.js')}}"></script>
    <script src="{{asset('public/js/customers.index.js')}}"></script>

@endsection

@section('content')
    <div class="container-fluid m-0 p-0">
        <div class="card text-white ml-0 mt-0 card-50" style="background-color:#3097D1; border-radius: 0">
            <div class="card-header pb-1">
                <span>
                    <span id="spanSearchQuery" onClick="" style="cursor: pointer" data-targeturl="{{Request::url().'?'.http_build_query(Request::except(['searchQuery','page','searchMethod'])).'&searchQuery='}}">
                        <a class=""><i class="fas fa-search"></i></a>
                    </span>

                    @if (!empty(app('request')->input('searchQuery')))
                    <span id="spanClearSearchQuery" data-targeturl="{{Request::url().'?'.http_build_query(Request::except(['searchQuery','page','searchMethod']))}}">
                        <i class="fas fa-search-minus" style="cursor: pointer"></i>
                    </span>
                    @endif
                </span>
                <span class="pull-right"><h4>
                    @if ($customers->currentPage() >= 3)
                        <a class="page-number" href="{{Request::url().'?'.http_build_query(Request::except('page')).'&page=1'}}" tabindex="-1">&laquo;</a>&nbsp;
                    @endif
                    @if ($customers->currentPage() >= 2)
                        <a class="page-number" href="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='.($customers->currentPage()-1)}}" tabindex="-1">&lsaquo;</a>&nbsp;
                    @endif
                    {{--<span class="page-number" id="spanPageSelector" data-targeturl="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='}}" ><a class="">{{$customers->currentPage()}}/{{$customers->lastPage()}}</a></span>&nbsp;--}}
                    <span class="page-number" id="spanPageSelector" data-targeturl="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='}}" ><a class="">{{$customers->currentPage()}}/{{$customers->lastPage()}}</a></span>&nbsp;

                    @if ($customers->currentPage() < $customers->lastPage())
                        <a class="page-number" href="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='.($customers->currentPage()+1)}}" tabindex="-1">&rsaquo;</a>&nbsp;
                    @endif
                    @if ($customers->currentPage() < $customers->lastPage())
                        <a class="page-number" href="{{Request::url().'?'.http_build_query(Request::except('page')).'&page='.($customers->lastPage())}}" tabindex="-1">&raquo;</a>&nbsp;
                    @endif
                    </h4>
                </span>
            </div>
        </div>

        @foreach ($customers as $customer)
            <div class="card ml-0 mt-0 customer-card" style="background-color: #eeeeee; border-radius: 0">
                <div class="card-header">
                        <span class="float-rigth">
                        {{$customer['name']}}</span>
                    <span class="pull-right">
                            <a href="{{url('customers/'.$customer['id'].'/edit')}}">Edit</a>
                    </span>
                        <div style="font-size: x-small"><a href="{{Request::url().'?'.http_build_query(Request::except(['searchQuery','page','searchMethod'])).'&searchMethod=exactMatch&searchQuery='.$customer['group']['name']}}" >{{$customer['group']['name']}}</a></div>
                </div>
                <div class="card-body p-3">
                    @if (count($customer['accountPlans'])>0)
                        <a href="{{url('accountplans/showplanof/'.$customer['id'])}}"><i class="fas fa-list"></i></a>
                    @endif

                </div>

            </div>
        @endforeach
    </div>
@endsection
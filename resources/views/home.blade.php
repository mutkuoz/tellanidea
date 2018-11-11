@extends('layouts.app')

@section('content')
<div class="container-fluid m-0 p-0">

    @foreach ($kpis as $kpi)
        <div class="card text-white ml-0 mt-0 card-50" style="background-color: {{$kpi['color']}}; border-radius: 0">
        <div class="card-header">
            <h2>
                @if (is_null($kpi['link']))
                    {{$kpi['name']}}
                @else
                    <a class="text-white" href="{{$kpi['link']}}">{{$kpi['name']}}</a>
                @endif
           </h2>

        </div>
        <div class="card-body">
            @if (empty($kpi['toolTipTemplate']))
                <h2 class="card-title text-right">{{number_format($result['kpi_'.$kpi['id']],$kpi['decimalPlaces'])}}&nbsp;{{$kpi['postSign']}}</h2>
            @else
                @php
                    for ($i=1;$i<10;$i++) {
                        if(strpos($kpi['toolTipTemplate'],'%'.$i.'%')===false){
                            break;
                        }
                        else {
                            $kpi['toolTipTemplate']=str_replace('%'.$i.'%',number_format($result['kpi_'.$kpi['id'].'_param'.$i],0),$kpi['toolTipTemplate']);
                        }
                    }
                @endphp
                <h2 class="card-title text-right" data-toggle="tooltip" data-html="true" data-placement="bottom" title="{{$kpi['toolTipTemplate']}}" >{{number_format($result['kpi_'.$kpi['id']],$kpi['decimalPlaces'])}}&nbsp;{{$kpi['postSign']}}</h2>
            @endif
            <p class="card-text"></p>
        </div>
    </div>
    @endforeach
</div>

    <a href="{{url('myPeople')}}">Compare My People</a> -
    <a href="quotations/new">New Quotation</a> -
    <a href="accountplans/showplanof/3">Account Plan</a>
    <a href="accountplans/valuesimulator">Value Simulator</a>


@endsection

@extends('layouts.app')

@section('scripts')
    <script src="{{asset('public/js/kpis.index.js')}}"></script>
    <link href="{{ url('public/css/kpis.index.css') }}" rel="stylesheet">

@endsection

@section('content')
    <div class="container-fluid m-1 p-0">

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">queryWord</th>
                <th scope="col">calculationQuery</th>
                <th scope="col">calculationQueryForGroup</th>
                <th scope="col">calculationParameters</th>
                <th scope="col">color</th>
                <th scope="col">showOnMainScreen</th>
                <th scope="col">decimalPlaces</th>
                <th scope="col">viewMultiplier</th>
                <th scope="col">preSign</th>
                <th scope="col">postSign</th>
                <th scope="col">Link</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($kpis as $kpi)
                <tr>
                    <th scope="row">{{$kpi['id']}}</th>
                    <td>{{$kpi['name']}}</td>
                    <td>{{$kpi['queryWord']}}</td>
                    <td>{{$kpi['calculationQuery']}}</td>
                    <td>{{$kpi['calculationQueryForGroup']}}</td>
                    <td>{{$kpi['calculationParameters']}}</td>
                    <td>{{$kpi['color']}}</td>
                    <td>{{$kpi['showOnMainScreen']}}</td>
                    <td>{{$kpi['decimalPlaces']}}</td>
                    <td>{{$kpi['viewMultiplier']}}</td>
                    <td>{{$kpi['preSign']}}</td>
                    <td>{{$kpi['postSign']}}</td>
                    <td>{{$kpi['link']}}</td>
                    <td>
                        <a href="{{url('kpis/'.$kpi['id'].'/edit')}}">Edit</a>
                        @if (!empty($kpi['calculationQuery']))
                            <a href="{{url('kpis/'.$kpi['id'].'/runCalculationQuery')}}">Calculate</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <button type="button" class="pull-left btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Run All Queries</button>

        <button type="button" class="ml-2 pull-left btn btn-primary" onclick="location.href = '{{url('kpis/create')}}';">Add new KPI</button>

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <button type="button" id="calculateAllKPIs" class="pull-left btn btn-primary" data-url="{{url('')}}"
                            data-ids="{{implode(',',$kpiIds)}}">Run</button>
                    <div id="ajaxResults">

                    </div>
                </div>
            </div>
        </div>

    </div>



@endsection
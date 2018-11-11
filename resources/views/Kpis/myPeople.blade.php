@extends('layouts.app')

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.4/js/dataTables.rowReorder.min.js"></script>

    <script src="{{asset('public/js/kpis.mypeople.js')}}"></script>
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/rowreorder/1.2.4/css/rowReorder.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ url('public/css/kpis.mypeople.css') }}" rel="stylesheet">
@endsection

@section('content')
@php
    $titles=array_keys($results[0]);
    $rowNumber=0;
@endphp

    <div class="m-3">

    <table id="kpiTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>#</th>
            @foreach($titles as $title)
                <th data-viewproperties="{{isset($kpiViewProperties[$title]) ? json_encode($kpiViewProperties[$title]) :''}}">{{strtr($title,$kpiNames)}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
        <tr>
                <td>{{$rowNumber++}}</td>
            @foreach($result as $resultItem)
                <td>{{$resultItem}}</td>
            @endforeach
        </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
                <th>#</th>
            @foreach($titles as $title)
                <th>{{strtr($title,$kpiNames)}}</th>
            @endforeach
        </tr>
        </tfoot>
    </table>
    </div>

@endsection
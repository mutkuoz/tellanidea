@extends('layouts.app')

@section('stylesheets')
    <link href="{{ url('public/css/kpis.edit.css') }}" rel="stylesheet">
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

        <h2 class="form-signin-heading pt-2">Editing Kpi_{{$kpi['id']}}</h2>

        {{ Form::model($kpi,array('route' => array('kpis.update', $kpi->id),'method' => 'PUT')) }}

        {{ Form::label('name', 'Name', array('class'=>''))}}
        {{ Form::text('name', $kpi['name'], array('class'=>'form-control', 'placeholder'=>'Name'))}}

        {{ Form::label('queryWord', 'Query Word', array('class'=>''))}}
        {{ Form::text('queryWord', $kpi['queryWord'], array('class'=>'form-control', 'placeholder'=>'Query Word'))}}

        {{ Form::label('calculationQuery', 'Calculation Query', array('class'=>''))}}
        {{ Form::textarea('calculationQuery', $kpi['calculationQuery'], array('class'=>'form-control', 'placeholder'=>'Calculation Query'))}}

        {{ Form::label('calculationQueryForGroup', 'Calculation Query For Group', array('class'=>''))}}
        {{ Form::textarea('calculationQueryForGroup', $kpi['calculationQueryForGroup'], array('class'=>'form-control', 'placeholder'=>'Calculation Query For The Group'))}}

        {{ Form::label('calculationParameters', 'Calculation Parameters', array('class'=>''))}}
        {{ Form::textarea('calculationParameters', $kpi['calculationParameters'], array('class'=>'form-control', 'placeholder'=>'Calculation Parameters'))}}

        {{ Form::label('color', 'Color', array('class'=>''))}}
        {{ Form::text('color', $kpi['color'], array('class'=>'form-control', 'placeholder'=>'Color'))}}

        {{ Form::label('toolTipTemplate', 'Tooltip Template', array('class'=>''))}}
        {{ Form::textarea('toolTipTemplate', $kpi['toolTipTemplate'], array('class'=>'form-control', 'placeholder'=>'Tooltip Template'))}}

        {{ Form::label('toolTipNumbers', 'Tooltip Numbers', array('class'=>''))}}
        {{ Form::textarea('toolTipNumbers', $kpi['toolTipNumbers'], array('class'=>'form-control', 'placeholder'=>'Tooltip Numbers'))}}

        {{ Form::checkbox('showOnMainScreen', 1,($kpi['showOnMainScreen']===1))}}
        {{ Form::label('showOnMainScreen', 'Show on Main Screen', array('class'=>''))}}<br>

        {{ Form::label('decimalPlaces', 'Decimal Places', array('class'=>''))}}
        {{ Form::number('decimalPlaces', $kpi['decimalPlaces'], array('class'=>'form-control', 'placeholder'=>'Decimal Places'))}}

        {{ Form::label('viewMultiplier', 'View Multiplier', array('class'=>''))}}
        {{ Form::number('viewMultiplier', $kpi['viewMultiplier'], array('class'=>'form-control', 'placeholder'=>'View Multiplier Places'))}}

        {{ Form::label('preSign', 'Pre-sign', array('class'=>''))}}
        {{ Form::text('preSign', $kpi['preSign'], array('class'=>'form-control', 'placeholder'=>'Pre-sign'))}}

        {{ Form::label('postSign', 'Post-Sign', array('class'=>''))}}
        {{ Form::text('postSign', $kpi['postSign'], array('class'=>'form-control', 'placeholder'=>'Post-sign'))}}

        {{ Form::label('link', 'Link', array('class'=>''))}}
        {{ Form::text('link', $kpi['link'], array('class'=>'form-control', 'placeholder'=>'Url'))}}

        {{ Form::submit('Save', array('class' => 'btn pull-right')) }}
        <a href="{{url('kpis')}}" class="btn btn-default pull-left">Cancel</a>

        {{ Form::close() }}
    </div>


@endsection
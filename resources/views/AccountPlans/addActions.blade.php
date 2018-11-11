@extends('layouts.app')

@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <link href="{{ url('public/css/accounPlans.addActions.css') }}" rel="stylesheet">

@endsection

@section('content')

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif

        <h2 class="form-signin-heading pt-2">Add multiple actions</h2>

        {{ Form::label('productSelectionInput', 'Select product')}}
        <select class="js-example-basic-single" id="productSelectionInput" style="width: 100%" name="productSelectionInput"></select>

        <div class="row pt-3 d-none" id="actionDetails">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Manual List</a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Analytics Engine</a>
                </div>
            </div>

            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        Manual action entry:
                        <ul class="nav nav-tabs" id="manualActionEntryTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active hidePenetration" data-toggle="tab" href="#manualActionEntryPenetration" role="tab" aria-selected="true">Penetration</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link hideVolume" data-toggle="tab" href="#manualActionEntryVolume" role="tab" aria-selected="false">Volume</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link hideSpread" data-toggle="tab" href="#manualActionEntrySpread" role="tab" aria-selected="false">Spread</a>
                            </li>
                        </ul>

                        {{ Form::open(array('url' => 'accountplans/addManualActions'))}}
                        {{ Form::hidden('actionType','penetration', array('class'=>'actionType'))}}
                        {{ Form::hidden('productId','', array('class'=>'product'))}}
                        <div class="tab-content" id="manualActionEntryTabContent">
                            <div class="tab-pane fade show active hidePenetration" id="manualActionEntryPenetration" role="tabpanel" aria-labelledby="penetration-tab">
                                For these customers a <b>penetration</b> opportunity will be created if the customer does not have an active product.
                            </div>
                            <div class="tab-pane fade hideVolume" id="manualActionEntryVolume" role="tabpanel" aria-labelledby="volume-tab">
                                For these customers a <b>volume</b> opportunity will be created if the customer has an active product but the volume is less than target.
                                <div class="row">
                                    <div class="col">{{ Form::label('targetVolume', 'Please provide a volume target', array('class'=>''))}} </div>
                                    <div class="col">{{ Form::text('targetVolume', '', array('class'=>'form-control', 'placeholder'=>'Enter a target value'))}}</div>
                                </div>
                            </div>
                            <div class="tab-pane fade hideSpread" id="manualActionEntrySpread" role="tabpanel" aria-labelledby="contact-tab">
                                For these customers a <b>spread</b> opportunity will be created if the customer has an active product but the spread is less than target.
                                <div class="row">
                                    <div class="col">{{ Form::label('targetSpread', 'Please provide a spread target', array('class'=>''))}} </div>
                                    <div class="col">{{ Form::text('targetSpread', '', array('class'=>'form-control', 'placeholder'=>'Enter a target value'))}}</div>
                                </div>
                            </div>
                        </div>

                        {{ Form::label('customers', 'Enter one or multiple customers here', array('class'=>''))}}
                        {{ Form::textarea('customers', '', array('class'=>'form-control', 'placeholder'=>'Write customer numbers as a list or comma separated values'))}}
                        <div class="row pull-right">
                            <div class="col">
                            {{ Form::submit('Save', array('class' => 'btn')) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        Analytics Action Entry
                        <ul class="nav nav-tabs" id="analyticsActionEntryTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active hidePenetration" data-toggle="tab" href="#analyticsActionEntryPenetration" role="tab" aria-selected="true">Penetration</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link hideVolume" data-toggle="tab" href="#analyticsActionEntryVolume" role="tab" aria-selected="false">Volume</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link hideSpread" data-toggle="tab" href="#analyticsActionEntrySpread" role="tab" aria-selected="false">Spread</a>
                            </li>
                        </ul>
                        {{ Form::open(array('url' => 'accountplans/addActionsWithAnalytics'))}}
                        {{ Form::hidden('actionType','penetration', array('class'=>'actionType'))}}
                        {{ Form::hidden('productId','', array('class'=>'product'))}}
                        <div class="tab-content" id="analyticsActionEntryTabContent">
                            <div class="tab-pane fade show active hidePenetration" id="analyticsActionEntryPenetration" role="tabpanel" aria-labelledby="penetration-tab">
                                <div class="row">
                                    <div class="col">{{Form::label('Minimum Probability Threshold','',array('class'=>''))}}</div>
                                    <div class="col">{{Form::number('minProbabilityThreshold',0.75, array('class'=>'pull-right','id'=>'minProbabilityThreshold')) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col">{{Form::label('Number of Customers Targeted','',array('class'=>''))}}</div>
                                    <div class="col">{{Form::number('numberOfCustomersTargeted','', array('class'=>'pull-right','id'=>'numberOfCustomersTargeted'))}}</div>
                                </div>
                                <div class="row pull-right">
                                    <div class="col">{{Form::submit('Save', array('class' => 'btn')) }}</div>
                                </div>
                            </div>
                            <div class="tab-pane fade hideVolume" id="analyticsActionEntryVolume" role="tabpanel" aria-labelledby="volume-tab">
                                <div class="row">
                                    <div class="col">{{Form::label('Minimum target volume','',array('class'=>''))}}</div>
                                    <div class="col">{{Form::number('minTargetVolume',1000000, array('class'=>'pull-right','id'=>'minTargetVolume')) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col">{{Form::label('Minimum volume growth','',array('class'=>''))}}</div>
                                    <div class="col">{{Form::number('minVolumeGrowth','', array('class'=>'pull-right','id'=>'minVolumeGrowth')) }}</div>
                                </div>
                                <div class="row pull-right">
                                    <div class="col">{{Form::submit('Save', array('class' => 'btn')) }}</div>
                                </div>
                            </div>
                            <div class="tab-pane fade hideSpread" id="analyticsActionEntrySpread" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="row">
                                    <div class="col">{{Form::label('Minimum target spread','',array('class'=>''))}}</div>
                                    <div class="col">{{Form::number('minTargetSpread',1000000, array('class'=>'pull-right','id'=>'minTargetSpread')) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col">{{Form::label('Minimum spread growth (bps)','',array('class'=>''))}}</div>
                                    <div class="col">{{Form::number('minSpreadGrowth','', array('class'=>'pull-right','id'=>'minSpreadGrowth')) }}</div>
                                </div>
                                <div class="row pull-right">
                                    <div class="col">{{Form::submit('Save', array('class' => 'btn')) }}</div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


<script>
    var productListUrl= "{{ route('getProductList') }}";
    var actionCountUrl= "{{ route('countOfActionsWithAnalytics') }}";
    var getMinThresholdUrl = "{{ route('minThresholdWithAnalytics') }}"
</script>

<script src="{{asset('public/js/accountplans.addActions.js')}}"></script>

@endsection
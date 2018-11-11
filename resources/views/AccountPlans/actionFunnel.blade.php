@extends('layouts.app')

@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('public/js/accountPlans.actionFunnel.js') }}"></script>
@endsection

@section('content')
    <div class="container pt-5">
        <svg width="100%" viewBox="0 0 430 25">
            <style>
                .title-text {font-size: x-small}
                .number-text {cursor: pointer}
            </style>
            <text class="title-text" text-anchor="middle" x="50" y="20" fill="black">Recommended</text>
            <text class="title-text" text-anchor="middle" x="160" y="20" fill="black">Planned</text>
            <text class="title-text" text-anchor="middle" x="270" y="20" fill="black">In negotiation</text>
            <text class="title-text" text-anchor="middle" x="380" y="20" fill="black">Sold</text>

        </svg>
        <svg class="svgClass" width="100%" viewBox="0 0 430 220">

            <polygon points="0,0 100,7.5 100,100 0,107.5" style="fill:#82b4dd;stroke:#4452bf;stroke-width:0;fill-rule:evenodd;" />
            <polygon points="110,8.25 210,15.75 210,91.75 110,99.25" style="fill:#82b4dd;stroke:#4452bf;stroke-width:0;fill-rule:evenodd;" />
            <polygon points="220,16.5 320,24 320,83.5 220,91" style="fill:#82b4dd;stroke:#4452bf;stroke-width:0;fill-rule:evenodd;" />
            <polygon points="330,24.75 430,32.25 430,75.25 330,82.75" style="fill:#82b4dd;stroke:#4452bf;stroke-width:0;fill-rule:evenodd;" />
            <text text-anchor="middle" x="50" y="60" fill="white">{{$numberOfActions->numberOfRecommendedActions}}</text>
            <text text-anchor="middle" x="160" y="60" fill="white">{{$numberOfActions->numberOfPlannedActions}}</text>
            <text text-anchor="middle" x="270" y="60" fill="white">{{$numberOfActions->numberOfTalkedToClientActions}}</text>
            <text text-anchor="middle" x="380" y="60" fill="white">{{$numberOfActions->numberOfRealizedActions}}</text>

            <line x1="50" y1="65" x2="50" y2="110" style="stroke:rgb(0,0,0);stroke-width:0.5" />
            <line x1="160" y1="65" x2="160" y2="110" style="stroke:rgb(0,0,0);stroke-width:0.5" />
            <line x1="270" y1="65" x2="270" y2="110" style="stroke:rgb(0,0,0);stroke-width:0.5" />

            <text class="number-text" text-anchor="middle" x="50" y="130" fill="black">{{$numberOfActions->numberOfDroppedActionsAfterRecommended}}</text>
            <text class="number-text" text-anchor="middle" x="160" y="130" fill="black">{{$numberOfActions->numberOfDroppedActionsAfterPlanned}}</text>
            <text class="number-text" text-anchor="middle" x="270" y="130" fill="black">{{$numberOfActions->numberOfDroppedActionsAfterTalkedToClient}}</text>
        </svg>
    <div>
@endsection

@extends('layouts.app')

@section('scripts')
    <link rel="stylesheet" href="{{ asset('public/css/quotations.new.css')}}">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="{{ asset('public/js/grouped-categories.js')}}"></script>
@endsection

@section('content')
    <div class="container-fluid m-0 p-0">

        <div class="row">
            <div class="col-12 text-center">
                <button type="button" id="btnCustomer" class="btn btn-light" data-toggle="modal" data-target="#customerSelector">
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col text-center">
                <div id="container-rorwa" class="gauage"></div>
				<div class="w-100 text-center metric-text"><span id="rorwa-text-1"></span></div>
                <div class="w-100 text-center metric-text"><span id="rorwa-text-2"></span></div>
				<div class="w-100 text-center metric-text"><span id="rorwa-text-3"></span></div>
            </div>
            <div class="col text-center">
                <div id="container-sow" class="gauage"></div>
                <div class="w-100 text-center metric-text"><span id="sow-text-1"></span></div>
                <div class="w-100 text-center metric-text"><span id="sow-text-2"></span></div>
                <div class="w-100 text-center metric-text"><span id="sow-text-3"></span></div>
            </div>
            <div class="col text-center">
                <div id="container-CSR" class="gauage"></div>
                <div class="w-100 text-center metric-text"><span id="csr-text-1"></span></div>
                <div class="w-100 text-center metric-text"><span id="csr-text-2"></span></div>
                <div class="w-100 text-center metric-text"><span id="csr-text-3"></span></div>
            </div>
            <div class="col text-center">
                <div id="container-productVariety" class="gauage"></div>
            </div>
        </div>

        <div class="range-slider">
            <div class="row py-3">
                <div class="col-2 volumeSettings"><h5 class="text-right">Lending Volume</h5></div>
                <div class="col-8">
                    <input id="volumeRangeSliderRange" class="range-slider__range" type="range" value="0" min="0" max="500000" step="1000">
                </div>
                <div class="col-2 volumeSettings">
                    <span id="volumeRangeSliderValue" class="range-slider__value">0</span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-2"></div>
                <div class="col-8 sliderExplanation" id="volumeExplanation">Current volume (cash lending only, last month):</div>
                <div class="col-2"></div>
            </div>
        </div>

        <div class="range-slider">
            <div class="row py-3">
                <div class="col-2"><h5 class="text-right">Lending Spread</h5></div>
                <div class="col-8">
                    <input id="spreadRangeSliderRange" class="range-slider__range" type="range" value="0.0" min="0" max="5.0" step='0.01'>
                </div>
                <div class="col-2">
                    <span id="spreadRangeSliderValue" class="range-slider__value">0</span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-2"></div>
                <div class="col-8 sliderExplanation" id="spreadExplanation">Current Spread (last month):</div>
                <div class="col-2"></div>
            </div>
        </div>

        <div class="range-slider">
            <div class="row py-3">
                <div class="col-2"><h5 class="text-right">Maturity</h5></div>
                <div class="col-8">
                    <input id="maturityRangeSliderRange" class="range-slider__range" type="range" value="6" min="0" max="36">
                </div>
                <div class="col-2">
                    <span id="maturityRangeSliderValue" class="range-slider__value">0</span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-2"></div>
                <div class="col-8 sliderExplanation" id="maturityExplanation">Target End Date :</div>
                <div class="col-2"></div>
            </div>
        </div>

        <div class="range-slider">
            <div class="row py-3">
                <div class="col-2 sideBusinessSettings"><h5 class="text-right">Side Business</h5></div>
                <div class="col-8">
                    <input id="sideBusinessRangeSliderRange" class="range-slider__range" type="range" value="0" min="0" max="500">
                </div>
                <div class="col-2 sideBusinessSettings">
                    <span id="sideBusinessRangeSliderValue" class="range-slider__value">0</span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-2"></div>
                <div class="col-8 sliderExplanation" id="sideBusinessExplanation">X TL per month on top of existing Y TL per month</div>
                <div class="col-2"></div>
            </div>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-12 text-center">
    <button type="button" id="btnShowDetails" class="btn btn-primary" data-toggle="modal" data-target="#detailsTable">
        Show details
    </button>

    <button type="button" id="btnCalculatePrice" class="btn btn-secondary">
        Calculate Price
    </button>
        </div>
    </div>

    <!-- Financial Details Pop-up -->
    <div class="modal fade" id="detailsTable" tabindex="-1" role="dialog" aria-labelledby="detailsTableLable" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Financial Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: xx-small">
                    <div id='financialDetailsContent' class="financialDetailsDiv">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" onclick="selectElementContents( document.getElementById('tableFinancialDetails') );">Copy</button>
                </div>
            </div>
        </div>
    </div>

    <!-- RoRWA Chart Pop-up -->
    <div class="modal fade" id="rorwaDetailChart" tabindex="-1" role="dialog" aria-labelledby="rorwaChartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-big" role="document">
            <div class="modal-content modal-big-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Financial Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="rorwaDetailChartContainer" class="modal-body" style="">
                </div>
            </div>
        </div>
    </div>

    <!-- SOW Chart Pop-up -->
    <div class="modal fade" id="sowDetailChart" tabindex="-1" role="dialog" aria-labelledby="sowChartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Financial Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="sowDetailChartContainer" class="modal-body" style="">
                </div>
            </div>
        </div>
    </div>

    <!-- CSR Chart Pop-up -->
    <div class="modal fade" id="csrDetailChart" tabindex="-1" role="dialog" aria-labelledby="csrChartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Financial Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="csrDetailChartContainer" class="modal-body" style="">
                </div>
            </div>
        </div>
    </div>

    <!-- Lending Volume Setting Pop-up -->
    <div class="modal fade" id="settingsVolume" tabindex="-1" role="dialog" aria-labelledby="settingsVolumeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lending Volume</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="settingsSideBusinessBody" class="modal-body" style="">
                    <label for="value" class="">Value</label>
                    <input class="form-control" placeholder="0" name="value" type="number" value="0" min="0" id="settingsVolumeValue" data-bind="value:settingsSideBusinessValue" style="text-align: right">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="settingsVolumeSave">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SideBusiness Setting Pop-up -->
    <div class="modal fade" id="settingsSideBusiness" tabindex="-1" role="dialog" aria-labelledby="settingsSideBusinessLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Side Business Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="settingsSideBusinessBody" class="modal-body" style="">
                    <label for="value" class="">Value</label>
                    <input class="form-control" placeholder="0" name="value" type="number" value="0" min="0" id="settingsSideBusinessValue" data-bind="value:settingsSideBusinessValue" style="text-align: right">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="settingsSideBusinessSave">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Selection Pop-up -->
    <div class="modal fade" id="customerSelector" tabindex="-1" role="dialog" aria-labelledby="csrChartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="customerList" class="modal-body" style="">AAA
                </div>
            </div>
        </div>
    </div>

    <!-- Scenarios -->
    <div class="modal fade" id="scenarioSelector" tabindex="-1" role="dialog" aria-labelledby="scenarioSelectorLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select a scenairo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="scenarioSelectorBody" class="modal-body" style="">
                    <div class="card my-2">
                        <div class="card-body">Scnario 1</div>
                    </div>
                    <div class="card my-2">
                        <div class="card-body">Scnario 2</div>
                    </div>
                    <div class="card my-2">
                        <div class="card-body">Scnario 3</div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Margin Details -->
    <div class="modal fade" id="marginDetailChart" tabindex="-1" role="dialog" aria-labelledby="marginDetailChartLaebl" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-big" role="document">
            <div class="modal-content modal-big-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Margins Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="marginDetailChartContainer" class="modal-body" style="">
                </div>
            </div>
        </div>
    </div>

    <div id="debugArea" style="font-size: xx-small"></div>
    <script src="{{asset('public/js/customer-data.js')}}"></script>
    <script src="{{asset('public/js/quotations.new.js')}}"></script>

@endsection
@extends('layouts.app')

@section('scripts')
    <link rel="stylesheet" href="{{ asset('public/css/accountPlans.index.css?v=1')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/css/swiper.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.min.js"></script>
    <script src="{{ asset('public/js/accountPlans.index.js')}}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

@endsection

@section('content')
    <div id="loading-splash" style="display: flex ; align-items: center;justify-content: center;position: absolute; background-color: white; width: 100%; height: 100%; z-index: 1100">
        <div style="font-size: larger; position: absolute">Loading</div>
        <br>
        <div class="loader"></div>
    </div>

    <div class="company-selector">
        <div class="swiper-container" id="company-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="company-wrapper" data-company-id="%COMPANY-ID%">
                        <div class="company-text">%COMPANY-NAME%</div>
                        <div class="strategy-zone">
                            <div class="strategy-text strategy-%STRATEGY%">%STRATEGY%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="kpi-zone">
        <div class="kpi-card %COMPANY-ID%">
            <div class="kpi-text">%KPI-NAME%</div>
            <div class="previous-figure">
                <div class="previous-text">2017</div>
                <div class="previous-follow-line"></div>
                <div class="previous-value">%PAST-YEAR-VALUE%</div>
            </div>
            <div class="current-figure">
                <div class="current-value">%YEARTODATE-VALUE%</div>
            </div>
            <div class="target-figure">
                <div class="target-text">Target</div>
                <div class="target-follow-line"></div>
                <div class="target-value">%TARGET-VALUE%</div>
            </div>
        </div>
    </div>

    <div class="opportunity-zone">
        <div class="opportunities-title">
            Opportunities
            <img class="mb-1" style="cursor: pointer" id="buttonAddOpportunity" src="{{asset('public/images/button-add-opportunity.png')}}"/>
        </div>
        <div class="opportunities-separator"></div>
        <div class="opportunity-card-zone">
            <div class="swiper-container" id="opportunity-swiper">
                <div class="swiper-wrapper" id="opportunity-wrapper">
                </div>
            </div>
        </div>
    </div>

    <div class="notifications-zone">
        <div class="notifications-title">Notifications</div>
        <div class="notifications-separator"></div>

        <div class="notifications-text-container">
            <div class="notification company-1 company-3">
                <i class="fa fa-exclamation-circle icon-red"></i>
                <span class="company-name">Dynamic Sales Ltd</span>
                -
                <span class="sender-name"> System Generated </span>
                -
                <span class="notification-text">Significant SoW Decline last month (32%-16&) </span>
                <div class="notification-separator"></div>
            </div>
            <div class="notification company-1 company-2">
                <i class="fa fa-envelope icon-blue"></i>
                <span class="company-name">Dynamic Sales Ltd</span>
                -
                <span class="sender-name">Mehmet Yılmaz (Branch Manager)</span>
                -
                <span class="notification-text">Can we schedule an appointment for this customer?</span>
                <div class="notification-separator"></div>
            </div>
            <div class="notification company-1">
                <i class="fa fa-users icon-green"></i>
                <span class="company-name">Dynamic Automotive Group</span>
                -
                <span class="sender-name">Transaction Banking Team</span>
                -
                <span class="notification-text">Pushed Transaction Banking Platform Sales as Opportunity</span>
                <div class="notification-separator"></div>
            </div>

        </div>
    </div>

    <div class="d-none" id="opportunityTemplate">
        <div class="swiper-slide">
            <div class="opportunity-card %companyClass%" id="%opportunityDivId%">
                <div class="inactivated-wrapper d-none position-absolute h-100 bg-light" style="font-weight: bold; font-size: 36px;opacity: 0.8; box-sizing: border-box; width: 210px; z-index:999; color: #CACCC9">
                <div class="text-center" style="transform: rotate(315deg); position: relative; margin-top: -15%; top: 50%;">
                    Inactive
                </div>
                </div>
                <div class="successfulClosure-wrapper d-none position-absolute h-100" style="color: green; font-weight: bold; font-size: 72px;opacity: 0.8; box-sizing: border-box; width: 210px; z-index:999 ">
                    <div class="text-center" style="position: relative; margin-top: -15%; top: 50%;">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <div class="failClosure-wrapper d-none position-absolute h-100" style="color: #FF5D56; font-weight: lighter; font-size: 96px;opacity: 0.8; box-sizing: border-box; width: 210px; z-index:999 ">
                    <div class="text-center" style="position: relative; margin-top: -25%; top: 50%;">
                        <i class="fas fa-times"></i>
                    </div>
                </div>

                <div class="opportunity-card-title">
                    <div class="opportunity-status-sign"></div>
                    %productName%
                </div>
                <div class="opportunity-card-content">
                    <div class="opportunity-card-company-name">%companyName%</div>
                    <div class="opportunity-card-opportunity-text opportunity-card-content-detail">%opportunityText%</div>
                    <div class="opportunity-card-opportunity-reason opportunity-card-content-detail">%opportunityReason%</div>
                    <div class="opportunity-card-opportunity-status opportunity-card-content-detail">%opportunityStatus%</div>
                </div>
                <div style="width: 100%">
                    <div class="opportunity-card-action-area">
                        <div id="rejectButton" class="reject-button opportunity-button" data-target-window="%opportunityRejectButtonWindowId%" data-opportunityindex="%opportunityIndex%">
                            <img src="{{asset('public/images/reject-button.png')}}">
                        </div>
                        <div id="acceptButton" class="accept-button opportunity-button" data-target-window="%opportunityAcceptButtonWindowId%" data-opportunityindex="%opportunityIndex%">
                            <img src="{{asset('public/images/accept-button.png')}}">
                        </div>
                        <div id="proceedButton" class="proceed-button opportunity-button" data-target-window="%opportunityProceedButtonWindowId%" data-opportunityindex="%opportunityIndex%">
                            <span class="deadline-text">%deadLine%</span>
                            <img src="{{asset('public/images/button-forward.png')}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accept Button Window -->
    <div class="modal fade" id="standardOpportunityAcceptWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalWindowTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <h5 class="float-left"><label for="dateSelectionInput1">Select realization date</label></h5>
                        <input data-property="deadLine" data-provide="datepicker" class="btn btn-secondary datepicker pull-right user-input float-right">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="clsBtn1" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary window-accept-button" data-target-status="opportunityAcceptStatus">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.datepicker').datepicker({
            autoclose: true,
            format: "M-yy",
            startView: "months",
            minViewMode: "months",
            disableTouchKeyboard: true
        });
    </script>

    <!-- Reject Button Window -->
    <div class="modal fade" id="standardOpportunityRejectWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reject Opportunity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-option">Does not use this product</div>
                    <div class="modal-seperator"></div>
                    <div class="modal-option"><a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" class="no-decoration">Works with other bank <i class="fas fa-angle-double-down"></i></a></div>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <div class="modal-option">Set a reminder for:</div>
                            <div class="d-flex">
                                <div class="d-inline-flex flex-grow-1 mr-2">
                                <input data-property="reminderDate" data-provide="datepicker" id="reminderDatePicker" class="btn btn-secondary datepicker user-input w-100">
                                </div>
                                <div class="d-inline-flex float-right">
                                    <button type="button" class="btn btn-primary window-accept-button"
                                            data-target-status="opportunityRejectStatus"
                                            data-required-field-selector="#reminderDatePicker"
                                    >Set</button>
                                </div>

                            </div>
                            <div class="modal-seperator"></div>
                            <div class="modal-option window-accept-button" data-target-status="opportunityRejectStatus"
                                 data-update-status='{"rejectReason":"Does not want to work with us", "reminderDate": null}'
                                 style="cursor: pointer;"><a>Does not want to work with us</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#reminderDatePicker').datepicker({
            autoclose: true,
            format: "M-yy",
            startView: "months",
            minViewMode: "months",
            disableTouchKeyboard: true
        });
    </script>

    <!-- Proceed First Step Window -->
    <div class="modal fade" id="opportunityFirstProceedStepWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Proceeded on Opportunity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-option window-accept-button" data-target-status="opportunityFirstStepForwardStatus"
                         style="cursor: pointer;"><a>Contacted customer and will prepare a proposal</a></div>
                    <div class="modal-seperator"></div>
                    <div class="modal-option window-accept-button" data-target-status="opportunityRejectStatus"
                         data-update-status='{"status":"Failed"}'
                         style="cursor: pointer;"><a>Contacted customer but he said he wont buy the product</a></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Proceed Second Step Window -->
    <div class="modal fade" id="opportunitySecondProceedStepWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Proceeded on Opportunity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-option window-accept-button" data-target-status="opportunitySecondStepForwardStatus"
                         style="cursor: pointer;"><a>Proposal submitted, awaiting response from client</a></div>
                    <div class="modal-seperator"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Proceed Third Step Window -->
    <div class="modal fade" id="opportunityThirdProceedStepWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Proceeded on Opportunity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-option window-accept-button" data-target-status="opportunityThirdStepForwardStatus"
                         style="cursor: pointer;"><a>Sales closed successfully</a></div>
                    <div class="modal-seperator"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add new opportunity window -->
    <div class="modal fade" id="addOpportunityWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add new opportunity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select class="form-control" id="company-selector">
                    </select>
                    <div class="modal-seperator"></div>
                    <select class="form-control" id="product-selector">
                        <option>Product...</option>
                    </select>
                    <div class="modal-seperator"></div>
                    <div class="d-flex">
                        <h6 class="ml-2 float-left">Opportunity Type</h6>
                        <div class="ml-auto btn-group btn-group-toggle" data-toggle="buttons" id="btn-action-type">
                            <label class="btn btn-secondary active" id="btn-Penetration">
                                <input type="radio" name="options" autocomplete="off" checked> Pen.
                            </label>
                            <label class="btn btn-secondary" id="btn-Volume">
                                <input type="radio" name="options" autocomplete="off"> Vol.
                            </label>
                            <label class="btn btn-secondary" id="btn-Spread" >
                                <input type="radio" name="options" autocomplete="off"> Spr.
                            </label>
                        </div>
                    </div>
                    <div class="modal-seperator hide-if-no-target"></div>
                    <h6 class="ml-2 hide-if-no-target">Target</h6>
                    <div class="add-opportunity-window-target-area hide-if-no-target">
                        <div class="add-opportunity-window-current-value ml-2">1.25</div>
                        <div class="add-opportunity-window-target-follow-line"></div>
                        <div class="add-opportunity-window-target-value">
                            <input type="text" class="form-control" placeholder="Target" style="width: 6rem">
                        </div>
                    </div>
                    <div class="modal-seperator"></div>
                    <div class="d-flex align-items-center">
                        <h6 class="ml-2 float-left">Deadline</h6>
                        <div class="ml-auto" data-toggle="buttons">
                            <input type="text" class="form-control" placeholder="Target" style="width: 6rem">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-target-status="opportunityAcceptStatus">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

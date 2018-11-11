@extends('layouts.app')

@section('scripts')
    <link rel="stylesheet" href="{{ asset('public/css/accountplans.show.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

@endsection

@section('content')

    {{Form::hidden('opportunities', $opportunities, array('id'=>'opportunitiesArray'))}};
    <input id="opportunitiesArray1" name="opportunities1" type="hidden" value="{{$opportunities}}">
    <div id="opportunityCards"></div>
    <div>
        <button class="btn btn-success ml-3" id="addNewOpportunityButton">Add new opportunity</button>
        <button class="btn btn-danger mr-3 pull-right" id="saveOpportunitiesButton">Save</button>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="opportunityAcceptanceDialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="dateSelectionInput1">Select realization date</label>
                        <input id="dateSelectionInput1" data-provide="datepicker" class="btn btn-secondary datepicker pull-right">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="opportunityAcceptaneceDialogCancel">Close</button>
                    <button type="button" class="btn btn-primary" id="opportunityAcceptaneceDialogSubmit">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="opportunityRejectionDialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Opportunity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <button type="button" class="btn btn-light w-100 reject-Reason" data-dismiss="modal" id="">Does not use this product</button>
                        <hr>
                        <button type="button" class="btn btn-light w-100 reject-Reason" data-dismiss="modal" id="">Uses this product but prefers other bank</button>
                        <hr>
                        <button type="button" class="btn btn-light w-100 reject-Reason" data-dismiss="modal" id="">Uses this product but won't work with us</button>
                        <hr>
                        <button type="button" class="btn btn-light w-100 reject-Reason" data-dismiss="modal" id="">I don't want to sell this product to this client</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="addNewOpportunityDialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new opportunity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="productSelectionInput">Please select a product</label>
                    <select class="js-example-basic-single" id="productSelectionInput1" style="width: 100%" name="state">
                    </select>
                    <div class="mt-3">
                        <label for="dateSelectionInput2">Select realization date</label>
                        <input id="dateSelectionInput2" data-provide="datepicker" class="btn btn-secondary datepicker pull-right">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="addNewOpportunityDialogCancel">Close</button>
                    <button type="button" class="btn btn-primary" id="addNewOpportunityDialogSubmit">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var productListUrl= "{{ route('getProductList') }}";
        var saveOpportunitiesUrl= "{{  route('saveOpportunities')  }}";
    </script>
    <script src="{{asset('public/js/customer-data.js')}}"></script>
    <script src="{{asset('public/js/accountplans.show.js')}}"></script>
@endsection
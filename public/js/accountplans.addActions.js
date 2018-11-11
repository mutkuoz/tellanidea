$('#productSelectionInput').select2({
    placeholder: 'Product...',
    allowClear: true,
    ajax: {
        url:  productListUrl,
        dataType: 'json',
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});

function processActionTypes(data){
    var foundType=false;

    if(data.penetrationActionEnabled===0 || data.penetrationActionEnabled===false) {
        $('.hidePenetration').addClass('d-none');
    } else {
        $('.hidePenetration').removeClass('d-none');
        $('#manualActionEntryTab a[href="#manualActionEntryPenetration"]').tab('show');
        $('#analyticsActionEntryTab a[href="#analyticsActionEntryPenetration"]').tab('show');
        foundType=true;
    }

    if(data.volumeActionEnabled===0 || data.volumeActionEnabled===false) {
        $('.hideVolume').addClass('d-none');
    } else {
        $('.hideVolume').removeClass('d-none');
        if (!foundType) {
            $('#manualActionEntryTab a[href="#manualActionEntryVolume"]').tab('show');
            $('#analyticsActionEntryTab a[href="#analyticsActionEntryVolume"]').tab('show');
        }
    }

    if(data.spreadActionEnabled===0 || data.spreadActionEnabled===false) {
        $('.hideSpread').addClass('d-none');
    } else {
        $('.hideSpread').removeClass('d-none');
        if (!foundType){
            $('#manualActionEntryTab a[href="#manualActionEntrySpread"]').tab('show');
            $('#analyticsActionEntryTab a[href="#analyticsActionEntrySpread"]').tab('show');
        }
    }
}

$('#productSelectionInput').on('select2:select', function (e) {
    var productId=$('#productSelectionInput').val();

    if (productId!=null) {
        $('.product').val(productId);
        $('#actionDetails').removeClass('d-none');
    }
    else
        $('#actionDetails').addClass('d-none');

    processActionTypes(e.params.data);
});

var analyticsFieldUpdating=false;
$('#minProbabilityThreshold').on('change', function () {
    if (analyticsFieldUpdating)
        return;
    analyticsFieldUpdating=true;
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: actionCountUrl,
        type: 'POST',
        data: {'productSelectionInput':$('#productSelectionInput').val(), minProbabilityThreshold:$(this).val()},
        success: function (data) {
            $('#numberOfCustomersTargeted').val(data);
            analyticsFieldUpdating=false;
        }
    });
});
$('#numberOfCustomersTargeted').on('change', function () {
    var productId=$('#productSelectionInput').val();
    if (productId==null) {
        $(this).val('');
        alert('Please select a product');
        return;
    }
    if (analyticsFieldUpdating)
        return;
    analyticsFieldUpdating=true;
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: getMinThresholdUrl,
        type: 'POST',
        data: {'productSelectionInput':productId, numberOfCustomersTargeted:$(this).val()},
        success: function (data) {
            $('#minProbabilityThreshold').val(data);
            analyticsFieldUpdating=false;
        }
    });
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $('.actionType').val(e.target.innerHTML.toLowerCase());
})
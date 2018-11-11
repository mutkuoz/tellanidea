String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

var opportunityBeingEdited=null;

var opportunities=[];
function loadOpportunities(){
    var opportunityArray=JSON.parse($('#opportunitiesArray').val());
    for (var i=0; i<opportunityArray.length;i++)
    {
        var opp=JSON.parse(opportunityArray[i].parameters);
        opp.changed=false;
        opportunities.push(JSON.parse(opportunityArray[i].parameters));
    }
}
loadOpportunities();

var opportunities_original = JSON.parse(JSON.stringify(opportunities));

function findOpportunityWithId(id){
    for (var i=0;i<opportunities.length;i++)
        if (opportunities[i].opportunityId===id || parseInt(opportunities[i].opportunityId)===id)
            return opportunities[i];
    return null;
};

function getOpportunityIndexWithId(id){
    for (var i=0;i<opportunities.length;i++)
        if (opportunities[i].opportunityId===id)
            return i;
    return null;
};

var replacementMatrix=[
    {from:'%OPPORTUNITYID%',toProperty:'opportunityId'},
    {from:'%PRODUCTNAME%',toProperty:'productName'},
    {from:'%CUSTOMERID%',toProperty:'customerId'}
];

$(function() {
    opportunities.forEach(creteOpportunityCard);
    var products=[
        {id:1,text:'Product 1'},
        {id:2,text:'Product 2'},
        {id:3,text:'Product 3'},
        {id:4,text:'Product 4'},
        {id:5,text:'Product 5'},
        {id:6,text:'Product 6'},
        {id:7,text:'Product 7'}
    ];

    $('#productSelectionInput1').select2({
        dropdownParent: $('#addNewOpportunityDialog'),
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

    $('.datepicker').datepicker({
        autoclose: true,
        format: "M-yy",
        startView: "months",
        minViewMode: "months"
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

function creteOpportunityCardHTML(opportunity, addWrapper){
    var firstWord = opportunity.status.replace(/ .*/,'');
    if (firstWord==="Canceled" || firstWord==="Rejected" || firstWord==="Dropped")
        return "";
    addWrapper= (typeof addWrapper !== 'undefined') ?  addWrapper : true;
    var html="";
    if (addWrapper)
        html+="<div class=\"opportunitywrapper\">";
    html+="<div id='opportunity_%OPPORTUNITYID%' class=\"card opportunity-card\" >";
    html+="<div class=\"card-header\">";
    html+="%PRODUCTNAME%";
    html+='<i class="fas fa-undo fa-sm ml-1 text-primary" onclick="revertBackOpportunity(%OPPORTUNITYID%);"></i>';
    html+="</div>";
    html+="<div class=\"card-body text-muted\">";
    html+="Benzer müşterilere göre bu müşteri'nin diğer bankalarda vadeli mevduat kullanma şansı X%\n";
    html+="</div>";
    if (opportunity.status==="Recommended"){
        html+="<div class=\"card-footer text-right font-italic text-muted\">";
        html+="<button type=\"button\" class=\"btn btn-success accept-button m-1\" data-opportunityid=\"%OPPORTUNITYID%\"><i class=\"fas fa-check\"></i></button>";
        html+="<button type=\"button\" class=\"btn btn-danger reject-button m-1\" data-opportunityid=\"%OPPORTUNITYID%\"><i class=\"fas fa-times\"></i></button>";
        html+="</div>";
    }
    else if (opportunity.status==="Planned") {
        html+="<div class=\"card-footer text-right font-italic text-muted\">";
        html+=opportunity.realizationDate + " tarihinde gerçekleştirilmek üzere planlandı";
        html+="</div>";
    }
    html+="</div>";
    html+="</div>";
    if (addWrapper) html+="</div>";

    for(var i=0;i<replacementMatrix.length;i++) {
        html=html.replaceAll(replacementMatrix[i].from,opportunity[replacementMatrix[i].toProperty])
    }
    return html;
}

function creteOpportunityCard(opportunity) {
  $('#opportunityCards').append(creteOpportunityCardHTML(opportunity));
}

$('#opportunityCards').on('click','.accept-button',function(){
    opportunityBeingEdited=$(this).data('opportunityid');
    $('#opportunityAcceptanceDialog').modal('show');
});

$('#opportunityCards').on('click','.reject-button',function(){
    opportunityBeingEdited=$(this).data('opportunityid');
    $('#opportunityRejectionDialog').modal('show');
});

$('#opportunityAcceptaneceDialogSubmit').on('click', function () {
    var opportunity=findOpportunityWithId(opportunityBeingEdited);
    opportunity.status='Planned';
    opportunity.realizationDate=$('#dateSelectionInput1').val();
    opportunity.changed=true;
    $('#opportunity_'+opportunityBeingEdited+'>.card-footer').html(
        opportunity.realizationDate+' tarihinde gerçekleştirilmek üzere planlandı'
    );
    $('#opportunityAcceptanceDialog').modal('hide');
});

$('.reject-Reason').on('click', function(){
    var opportunity=findOpportunityWithId(opportunityBeingEdited);
    opportunity.status='Dropped (After Recommended)';
    opportunity.realizationDate=null;
    opportunity.changed=true;
    opportunity.rejectReason=$(this).html();
    $('#opportunity_'+opportunityBeingEdited+'>.card-footer').html(
        'Reddedildi ('+opportunity.rejectReason+')'
    );
    $('#opportunityRejectionDialog').modal('hide');
});

$('#opportunityAcceptaneceDialogCancel').on('click', function () {
    $('#opportunityAcceptanceDialog').modal('hide');
});

$('#addNewOpportunityButton').on('click', function(){
    $('#addNewOpportunityDialog').modal('show');
});

function getNewOpportunityId() {
    for (i=-1;i>-999;i--)
        if (getOpportunityIndexWithId(i)===null) {
            return i;
        }
}

$('#addNewOpportunityDialogSubmit').on('click', function(){
    var selectedProduct = $('#productSelectionInput1').select2('data')
    var newOpportunity={};
    newOpportunity.opportunityId=getNewOpportunityId();
    newOpportunity.customerId=1;
    newOpportunity.productId=selectedProduct[0].id;
    newOpportunity.productName=selectedProduct[0].text;
    newOpportunity.realizationDate=$('#dateSelectionInput2').val();
    newOpportunity.status="Planned";
    opportunities.push(newOpportunity);
    //{'opportunityId':1, 'customerId':1, 'productId':1, 'productName':'Vadesiz Mevduat', 'plannedDate':null, status:'Recommended'},
    creteOpportunityCard(newOpportunity);
    $('#addNewOpportunityDialog').modal('hide');
});

function revertBackOpportunity(opportunityId){
    var index=getOpportunityIndexWithId(opportunityId);
    opportunities[index]=JSON.parse(JSON.stringify(opportunities_original[index]));
    var wrapper=$('#opportunity_'+opportunityId).parent();
    wrapper.html(creteOpportunityCardHTML(opportunities[index]));
}

$('#saveOpportunitiesButton').on('click', function () {
    $.ajax({
        url: saveOpportunitiesUrl,//"http://localhost/tellanidea/opportunities/saveopportunities",
        method: "POST",
        data: {opportunities:opportunities},
        context: document.body
    }).done(function(returnData) {
        //$('#debug').html(returnData);
    });
});
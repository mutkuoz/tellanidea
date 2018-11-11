$(document).ready(function () {
    var kpis;
    var calculationCounter=0;
    $('#calculateAllKPIs').on('click',function(e){
        kpis=$(this).data('ids').split(',');
        $('#ajaxResults').append('Starting Exection<br>');
        $('#ajaxResults').append('Sending query for Kpi '+kpis[calculationCounter]+'...');

        callAjax($(this).data('url'), kpis[calculationCounter]);
    });

    function callAjax(url, kpiIndex) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: url +'/kpis/'+kpiIndex+'/runCalculationQuery',
            success: function(data) {
                $('#ajaxResults').append('...'+data['result']+'<br>');
                if (calculationCounter<kpis.length-1){
                    calculationCounter++;
                    $('#ajaxResults').append('Sending query for Kpi '+kpis[calculationCounter]+'...');
                    callAjax(url,kpis[calculationCounter])
                }
                else {
                    $('#ajaxResults').append('Execution completed');
                }
            }
        });
    }
});
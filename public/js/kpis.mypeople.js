$(document).ready(function () {

    var colCounter=0;
    var _columnDefs=[];

    $('#kpiTable >  thead > tr > th').each(function(e){
        // var properties=$(this).data('viewproperties');
        var preSign, postSign, decimalPlaces;
        var properties;
        if(properties=$(this).data('viewproperties')) {
            preSign=properties.preSign;
            postSign=properties.postSign;
            decimalPlaces=properties.decimalPlaces;
        }
        else{
            preSign='';
            postSign='';
            decimalPlaces=0;
        }

        var colDef={
            targets: colCounter,
            render: $.fn.dataTable.render.number( ',', '.', decimalPlaces, preSign, postSign)
        };
        if (colCounter===0) {colDef['visible']=false;}
        if (colCounter>1) {colDef['class']='text-right';}

        _columnDefs.push(colDef);
        colCounter++;
    });


    var table= $('#kpiTable').DataTable({
        dom: 'lBfrtip',
        buttons: [
            'columnsToggle','copy'
        ],
        rowReorder:{selector:'tr'},
        columnDefs: _columnDefs
    });

    table.on('row-reorder', function ( e, diff, edit ) {
        table.order([0,'asc']);
    } );
} );

var actingUserId=0;
var selectedUserId=0;

$(document).ready(function () {
    $('#btn_ChangeActingUser').click(function () {
        actingUserId= $('input#actingUserId').val();
        $('#actingUserSelector').modal('show');
    })

    $('.btn-userSelection').click(function () {
        var items = $('#div-userSelection .btn-userSelection');
        items.each(function() {
             $( this ).removeClass('btn-primary').addClass('btn-light');
        });
        $(this).removeClass('btn-light').addClass('btn-primary')
        selectedUserId=$(this).attr('userId');
    })

    $('#actingUserSelector').on('hidden.bs.modal', function (e) {
        $('#div-userSelection .btn-userSelection').removeClass('btn-primary').addClass('btn-light')
        $('#userSelection_'+actingUserId).removeClass('btn-light').addClass('btn-primary');
        selectedUserId=actingUserId;
    })

    $( "#submitUserChange" ).click(function() {
        $('input#actingUserId').val(selectedUserId);
        $('#changeActingUser' ).submit();
    });

    $('[data-toggle="tooltip"]').tooltip()
});
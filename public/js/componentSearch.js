(function ( $ ) {

    $.fn.sendSearchQuery = function() {

        var targetURL=this.data('targeturl');

        $('body').append("<div class='modal fade' id='divSearchQuery'>" +
            "            <div class='modal-dialog' role='document'>" +
            "                <div class='modal-content' style='margin: auto'>" +
            "                    <div class='modal-body'>" +
            "                    <div class='row'>" +
            "                        <div class='col-2 modal-title text-nowrap d-none d-sm-inline pt-2'><p5>Search:</p5></div> " +
            "                        <div class='col'><input type='text' class='form-control d-flex' style='' id='txtSearchQuery' placeholder='Customer id or name'></div>" +
            "                        <div class='col-2'><button type='submit' id='btnSendSearchQuery' class='btn btn-primary d-inline'>&raquo;</button></div>" +
            "                    </div>" +
            "                    </div>" +
            "                </div>" +
            "            </div>" +
            "        </div>");

        this.css('cursor','pointer');

        this.on('click', function () {
            $('#divSearchQuery').modal('show');
        });

        $('#divSearchQuery').on('shown.bs.modal.bs.modal', function (e) {
            $('#txtSearchQuery').focus();
        });

        $('#btnSendSearchQuery').on('click touchstart', function (e) {
            pageToGo=$('#txtSearchQuery').val();
            window.location.href = targetURL+pageToGo;
        });

        $('#spanClearSearchQuery').on('click touchstart', function (e) {
            window.location.href = $('#spanClearSearchQuery').data('targeturl');
        });

        return this;
    };

}( jQuery ));
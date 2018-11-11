(function ( $ ) {

    $.fn.pageSelector = function() {

        var targetURL=this.data('targeturl');

        function isInt(value) {
            if (isNaN(value)) {
                return false;
            }
            var x = parseFloat(value);
            return (x | 0) === x;
        }

        $('body').append("<div class=\"modal fade\" id=\"divPageSelection\">\n" +
            "            <div class=\"modal-dialog\" role=\"document\">\n" +
            "                <div class=\"modal-content\" style=\"width: 14rem; margin: auto\">\n" +
            "                    <div class=\"modal-body d-inline\">\n" +
            "                        <form id=\"formPageSelector\" onsubmit=\"return false;\">\n" +
            "                        <p5 class=\"modal-title text-nowrap d-inline\">Goto Page:</p5>\n" +
            "                        <input type=\"text\" class=\"form-control d-inline\" style=\"width: 4rem;\" id=\"txtSelectedPage\" placeholder=\"#\">\n" +
            "                        <button type=\"submit\" id=\"buttonGotoPage\" class=\"btn btn-primary d-inline\" style=\"margin-top: -4px;\">&raquo;</button>\n" +
            "                        </form>\n" +
            "                    </div>\n" +
            "                </div>\n" +
            "            </div>\n" +
            "        </div>");

        this.css('cursor','pointer');

        this.on('click', function () {
            $('#divPageSelection').modal('show');
        });

        $('#divPageSelection').on('shown.bs.modal.bs.modal', function (e) {
            $('#txtSelectedPage').focus();
        });

        $('#buttonGotoPage').on('click touchstart', function (e) {
            pageToGo=$('#txtSelectedPage').val();
            if (isInt(pageToGo)){
                window.location.href = targetURL+pageToGo;
            }
            else
                alert("Page number is not an integer")
        });

        return this;
    };

}( jQuery ));
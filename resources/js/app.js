import "./bootstrap";

var Payment = {
    total: 0.0,
    init: function () {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({});
        });

        // $(".form").validate({
        //     submitHandler: function (form) {
        //         form.submit();
        //     },
        // });

        $(".pay_invoice").on("change", function () {
            if ($(this).is(":checked")) {
                Payment.total += parseInt($(this).attr("data"));
            } else {
                Payment.total -= parseInt($(this).attr("data"));
            }

            // Format the price above to USD using the locale, style, and currency.
            let INTL = new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "MKD",
            });

            if (Payment.total > 0)
                $("#payment_summary button").attr("disabled", false);
            else $("#payment_summary button").attr("disabled", true);

            $("#payment_summary span b").html(
                INTL.format(Payment.total).replace("MKD", "")
            );
        });
    },
};

Payment.init();

jQuery(document).ready(function($) {
    var timeoutMessage;
    timeoutMessage = window.setTimeout(function() {
        $("#message").text(paypal_digital_goods_payment_gateway_for_woocommerce.msgWaiting);
    }, 7000);
    $.ajax({
        url: paypal_digital_goods_payment_gateway_for_woocommerce.ajaxUrl,
        data: 'action=paypal_digital_goods_payment_gateway_for_woocommerce_do_express_checkout&' + paypal_digital_goods_payment_gateway_for_woocommerce.queryString,
        success: function(response) {
            try {
                var response = $.parseJSON(response);
                if ('success' == response.result) {
                    $('#message').text(paypal_digital_goods_payment_gateway_for_woocommerce.msgComplete);
                    if (window != top) {
                        top.location.replace(decodeURI(response.redirect));
                    } else {
                        window.location = decodeURI(response.redirect);
                    }
                } else {
                    response = response.message;
                    throw response.message;
                }
            } catch (err) {
                if (response.indexOf('woocommerce_error') == -1 && response.indexOf('woocommerce_message') == -1) {
                    response = '<div class=\"woocommerce_error\">' + response + '</div>';
                }
                if ($('form.checkout').length > 0) {
                    $('form.checkout').prepend(response);
                    $('html, body').animate({
                        scrollTop: ($('form.checkout').offset().top - 100)
                    }, 1000);
                } else {
                    window.clearTimeout(timeoutMessage);
                    $('#message').html(response).css({
                        'font-style': 'normal',
                        'color': '#CC0000',
                    });
                    $('#message').siblings('img').hide();
                }
            }
        },
        dataType: 'html'
    });
});

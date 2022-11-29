$(window).on("load", function() {
    $('.login-box-body.animation').css('opacity', '1').css('transform', 'translateY(0)');
    $('body').tooltip({
        selector: '.qb-tooltip-link',
        template: '<div class="tooltip" style="opacity: 1" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    });
    // New Login Animation UI
    $('.qb-login-box.animation').addClass('on-load-animation');
    $('.news.animation').addClass('on-load-animation');
    if ($(".login-screen .is-focused").length < 1 && $(".login-screen .login-email").length) {
        setTimeout(function() {
            var hasValue = $(".login-screen .login-email").val().length > 0; // Normal
            if (!hasValue) {
                hasValue = $(".login-screen .login-email:-webkit-autofill").length > 0; // Chrome
            }
            if (hasValue) {
                $(".login-screen .login-email").parent('.label-floating').removeClass("is-empty");
                $(".login-screen .login-password").parent('.label-floating').removeClass("is-empty");
            }
        }, 100);
    }
});
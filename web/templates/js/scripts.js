/*$(window).on('load', function() {
 if (window.innerWidth <= 767) {
 $("body").css("padding-top", $("#main-header").outerHeight());
 }
 });*/
$(document).ready(function () {
    if ($(".filters").length) {
        $(".filters li a").on('click tap', function (e) {
            e.preventDefault();
            $(".filters li a.active").removeClass("active");
            $(this).addClass("active");
        });
    }

    $(".svg-icon").on('click tap', function () {
        $(this).blur();
    });
    $("#follow-button").on('click tap', function () {
        $(this).fadeOut('200');
        setTimeout(function () {
            $("#main-header .social-media").addClass("active");
        }, 400);
    });

    $("#scroll-down-arrow").on('click tap', function () {
        $("html").animate({scrollTop: $("#top-holder").outerHeight()});
    });

    $("#mobile-menu-button").on('click tap', function () {
        $("#mobile-menu").addClass("active");
    });

    $("#mobile-menu-button-close").on('click tap', function () {
        $("#mobile-menu").removeClass("active");
    });

    $("html").on('change', 'select:not(.select-standalone)', function () {
        if ($(this).val()) {
            $(this).parent().addClass("input-holder-select-selected");
        } else {
            $(this).parent().removeClass("input-holder-select-selected");
        }
    });

    $("html").on('focus', 'input', function () {
        $(this).parents(".input-holder").addClass("labeled");
    });
    $("html").on('blur', 'input', function () {
        if (!$(this).val())
            $(this).parents(".input-holder").removeClass("labeled");
    });

    $('body').on('click', 'a.submitButton', function (e) {
        e.preventDefault();

        var form = $(this).parents('form:eq(0)');

        if (form[0].checkValidity()) {
            if ($(this).attr('data-modal')) {
                $('#' + $(this).attr('data-modal')).removeClass('hidden');
            }
        }

        if (form.find('button[type=submit]').length) {
            form.find('button[type=submit]').trigger('click');
            return false;
        } else if (form[0].checkValidity()) {
            form.submit();
        }
        return false;
    })


    $('body.congratulationsPage').on('click', 'a:not(.submitButton,.blockLeaveModal,.clipboard,#insta-button)', function () {
        if ($('.customPopup:visible').length || $('body.congratulationsPage').length) {
            if (!$('#leavePopup:visible').length && !$('#instagramPopup:visible').length) {
                $('#leavePopup').removeClass('hidden');
                $('#leavePopup').data('targetUrl', $(this).attr('href'));
            }
            return false;
        }
    })

    $('.popup-close-button a, .customPopup a[href=\\#no]').on('click', function () {
        $(this).parents('.customPopup').addClass('hidden');

        return false;
    });

    $('.customPopup a[href=\\#yes]').on('click', function () {
        window.location.href = $('#leavePopup').data('targetUrl');
        return true;
    });

    $('body').on('click', "#insta-button", function (e) {
        e.preventDefault();
        $("#instagramPopup").removeClass("hidden");

        return false;
    });

    if (typeof $.fn.fancybox !== 'undefined') {
        $('a.fancybox').fancybox();
    }

});

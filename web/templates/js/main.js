if (typeof $.uf === 'undefined') {
    $.uf = {};
}

$.uf.copy = function (button) {
    var _this = this;

    var clipboard = new Clipboard(button, {
        text: function (trigger) {
            var el = $(trigger).closest('.js-copy-container').find('.js-copy-target');
            if (el.is(':input')) {
                return el.val();
            } else {
                return el.html();
            }
        }
    });

    clipboard.on('success', function (e) {
        setTooltip(e.trigger, 'Copied!');
        hideTooltip(e.trigger);
    });

    clipboard.on('error', function (e) {
        setTooltip(e.trigger, 'Failed!');
        hideTooltip(e.trigger);
    });

    function setTooltip(btn, message) {
        $(btn)
            .attr('data-original-title', message)
            .tooltip('show');
    }

    function hideTooltip(btn) {
        setTimeout(function () {
            $(btn).tooltip('hide')
                .attr('data-original-title', "");
        }, 1000);
    }

    // Tooltip
    $(button).tooltip({
        trigger: 'click'
    });
};

$(function () {

    $('body').on('change', '#app_create_pet_step_one_file, #app_create_pet_step_one_fileCamera', function () {
        $('#uploadingPopup').removeClass('hidden');
        $(this).parents('form:eq(0)').find('button[type=submit]').trigger('click');

        return true;
    })

    $('body').on('click', '#submitForm', function (e) {
        e.preventDefault();
        if ($(this).parents('form:eq(0)').length) {
            $(this).parents('form:eq(0)').find('button[type=submit]').trigger('click');
        }
        return false;
    })

    bindGaEvents();

    $('body').on('click', '.btn-delete, .confirmAction', function (e) {
        e.preventDefault();

        if ($('#confirm').length) {
            if ($(this)[0].hasAttribute('disabled')) {
                return false;
            }
            var handler = $(this), href, confirmTitle = 'Potwierdz akcje', confirmText = 'Czy na pewno chcesz usunac?', buttonTitle = 'Usun', buttonClass = 'btn-danger';
            if (handler.attr('data-href')) {
                href = handler.attr('data-href');
            } else {
                href = handler.attr('href');
            }
            if (handler.attr('data-confirmation-title')) {
                confirmTitle = handler.attr('data-confirmation-title');
            }
            if (handler.attr('data-confirmation-text')) {
                confirmText = handler.attr('data-confirmation-text');
            }
            if (handler.attr('data-confirmation-button-title')) {
                buttonTitle = handler.attr('data-confirmation-button-title');
            }
            if (handler.attr('data-confirmation-button-class')) {
                buttonClass = handler.attr('data-confirmation-button-class');
            }

            $('#confirm').find('.modal-title').text(confirmTitle);
            $('#confirm').find('.modal-body').html('<p>' + confirmText + '</p>');
            $('#confirm').find('#confirmIt').text(buttonTitle).attr('class', 'btn ' + buttonClass);
            if (handler[0].hasAttribute('data-ajax-action')) {
                $('#confirm').find('#confirmIt').removeAttr('data-dismiss');
            } else {
                $('#confirm').find('#confirmIt').attr('data-dismiss', 'modal');
            }

            $('#confirm')
                .modal()
                .one('click', '#confirmIt', function (e) {
                    if (handler[0].hasAttribute('data-ajax-action')) { //via ajax
                        ajaxAction(handler, href);
                    } else {
                        window.location = href;
                    }
                });
        }
    });

    if(typeof $.fn.datepicker !== 'undefined') {
        $.fn.datepicker.dates['pl'] = {
            days: ["Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota"],
            daysShort: ["Niedz.", "Pon.", "Wt.", "Śr.", "Czw.", "Piąt.", "Sob."],
            daysMin: ["Ndz.", "Pn.", "Wt.", "Śr.", "Czw.", "Pt.", "Sob."],
            months: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"],
            monthsShort: ["Sty.", "Lut.", "Mar.", "Kwi.", "Maj", "Cze.", "Lip.", "Sie.", "Wrz.", "Paź.", "Lis.", "Gru."],
            today: "Dzisiaj",
            weekStart: 1,
            clear: "Wyczyść",
            format: "dd.mm.yyyy"
        };
    }
});

var gaEventsFunctions = {
    gaClick: function (event) {
        var handler = $(event.currentTarget);
        if (handler.attr('data-event-click-action')) {
            gaSendEvent(handler.attr('data-event-click-category'), handler.attr('data-event-click-action'));
        }
    },
    gaChange: function (event) {
        var handler = $(event.currentTarget);
        if (handler.attr('data-event-change-action')) {
            gaSendEvent(handler.attr('data-event-change-category'), handler.attr('data-event-change-action'));
        }
    },
    gaOnOff: function (event) {
        var handler = $(event.currentTarget);
        if (handler.attr('data-event-onoff-action')) {
            var action = handler.attr('data-event-onoff-action'), actionType = handler.is(':checked') ? 'enabled' : 'disabled';
            action = action.replace('_onoff_', actionType);
            gaSendEvent(handler.attr('data-event-onoff-category'), action);
        }
    },
    gaFill: function (event) {
        var handler = $(event.currentTarget);
        if (handler.val() && handler.attr('data-event-fill-action')) {
            gaSendEvent(handler.attr('data-event-fill-category'), handler.attr('data-event-fill-action'));
        }
    },
    gaData: function (event) {
        var handler = $(event.currentTarget);
        if (handler.val() && handler.attr('data-event-data-action')) {
            var action = handler.attr('data-event-data-action');
            action = action.replace('_data_', handler.val());
            gaSendEvent(handler.attr('data-event-data-category'), action);
        }
    }
};

var bindGaEvents = function () {
    //Ga events
    $('[data-event-click-category]').off('click', gaEventsFunctions.gaClick).bind('click', gaEventsFunctions.gaClick);

    $('[data-event-change-category]').off('change', gaEventsFunctions.gaChange).bind('change', gaEventsFunctions.gaChange);

    $('[data-event-onoff-category]').off('change', gaEventsFunctions.gaOnOff).bind('change', gaEventsFunctions.gaOnOff);

    $('[data-event-fill-category]').off('blur', gaEventsFunctions.gaFill).bind('blur', gaEventsFunctions.gaFill);

    $('[data-event-data-category]').off('change', gaEventsFunctions.gaData).bind('change', gaEventsFunctions.gaData);
}

var gaSendEvent = function (eventCategory, eventAction, eventPath) {
    if (typeof ga !== 'undefined') {
        ga('send', 'event', eventCategory, eventAction, eventPath && typeof eventPath !== 'undefined' ? eventPath : window.location.pathname);
    }
}

var stepOneCallback = function () {
    $('#ajaxLoaderImage').remove();
    gaSendEvent('app_create_pet_step_one', 'card|step-1', window.location.pathname);
};

var stepOnePreviewCallback = function () {
    $('#ajaxLoaderImage').remove();
};

var stepTwoCallback = function (form) {
    gaSendEvent('app_create_pet_step_two', 'card|step-2', window.location.pathname);

    var lazyInterval = null, maxAttemps = 150, attempts = 0;

    lazyInterval = setInterval(function () {
        if ($('img.lazy').length == $('img.lazyLoaded').length) {
            clearInterval(lazyInterval);
            lazyInterval = null;
            initStep2();
        } else {
            if (attempts >= maxAttemps) {
                window.location.reload();
            }
            attempts++;
        }
    }, 100);

    var initStep2 = function () {
        $('.formInner').removeClass('noVisible');
        $('.swiper-slide').removeClass('temporarilyAbsolute');
        $('.ajaxFormContainer').addClass('ajaxFormLoaded');
        $('#ajaxLoaderImage').remove();

        var mySwiper = new Swiper('#card-layers', {
            speed: 400,
            spaceBetween: 0,
            slidesPerView: 'auto',
            simulateTouch: false,
            shortSwipes: true,
            longSwipes: true,
            touchMoveStopPropagation: false,
            draggable: false,
            onlyExternal: true,
            centeredSlides: true,
            init: false,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
        mySwiper.on('slideChangeTransitionEnd', function (e) {
            var cardId = $('.swiper-slide-active').attr('data-card-id');
            $('input#app_create_pet_step_two_frame').val(cardId);
        });
        mySwiper.on('init', function (e) {
            if (typeof activeFrame !== 'undefined') {
                mySwiper.slideTo(activeFrame - 1);
            } else {
                $('input#app_create_pet_step_two_frame').val($('.swiper-slide-active').attr('data-card-id'));
            }

            var ajaxFormData;
            if ($('.ajaxFormContainer form:eq(0)').data('ajaxData')) {
                ajaxFormData = $('.ajaxFormContainer form:eq(0)').data('ajaxData');
            }
            if ($('#card-preview-image').length) {
                var initCropper = function () {
                    $('#card-preview-image').cropper({
                        autoCropArea: 1,
                        strict: true,
                        guides: false,
                        highlight: false,
                        dragCrop: false,
                        viewMode: 0,
                        scalable: true,
                        responsive: true,
                        restore: true,
                        checkCrossOrigin: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        minContainerWidth: 100,
                        minContainerHeight: 100
                    });
                }

                setTimeout(function () {
                    initCropper();
                }, 50);

                $('#card-preview-image').on('cropend', function () {
                    cropData = $('#card-preview-image').cropper("getData");
                    canvasData = $('#card-preview-image').cropper("getCanvasData");
                    containerData = $('#card-preview-image').cropper("getContainerData");
                });

                $('#card-preview-image').on('cropmove', function () {
                    gaSendEvent('app_create_pet_step_two', 'card|step-2|move', window.location.pathname);
                });

                $('#card-preview-image').on('zoom', function (e) {
                    if (e.ratio > e.oldRatio) {
                        gaSendEvent('app_create_pet_step_two', 'card|step-2|zoom-in', window.location.pathname);
                    } else {
                        gaSendEvent('app_create_pet_step_two', 'card|step-2|zoom-out', window.location.pathname);
                    }
                });

                $('#card-preview-image').on('ready', function (e) {
                    if (ajaxFormData && typeof ajaxFormData.crop == 'object') {
                        $('#card-preview-image').cropper("setData", ajaxFormData.crop);
                    }

                    if (ajaxFormData && typeof ajaxFormData.canvas == 'object') {
                        $('#card-preview-image').cropper("setCanvasData", ajaxFormData.canvas);
                    }

                    if (ajaxFormData && typeof ajaxFormData.filterImage !== 'undefined' && ajaxFormData.filterImage) {
                        $('.cropper-view-box img, .cropper-canvas img').css({
                            filter: ajaxFormData.filterImage
                        });
                    }

                    $('#card-preview-image').cropper("setDragMode", 'move');

                    $('#card-preview-image').cropper("setCropBoxData", {
                        width: $('.card-preview').width(),
                        height: $('.card-preview').height()
                    });

                    if (!ajaxFormData) {
                        var minHeight = $('.cropper-container').height(),
                            canvasHeight = $('.cropper-canvas').height(),
                            zoom = minHeight / canvasHeight - 1;
                        $('#card-preview-image').cropper('zoom', zoom);
                    }

                    $(this).parent().addClass('readyImage');
                    $('.formInner').removeClass('noVisible');
                    $('#card-preview-image').addClass('initializedCropper');
                });

                $('#zoomInImage').on('click', function (e) {
                    e.preventDefault();
                    $('#card-preview-image').cropper("zoom", 0.1);
                    return false;
                });

                $('#zoomOutImage').on('click', function (e) {
                    e.preventDefault();
                    $('#card-preview-image').cropper("zoom", -0.1);
                    return false;
                });

                $('#rotateLeftImage').on('click', function (e) {
                    e.preventDefault();
                    $('#card-preview-image').cropper("rotate", 10);
                    return false;
                });

                $('#rotateRightImage').on('click', function (e) {
                    e.preventDefault();
                    $('#card-preview-image').cropper("rotate", -10);
                    return false;
                });

                $('#moveImage').on('click', function (e) {
                    e.preventDefault();
                    $('#card-preview-image').parent().toggleClass('editImage');
                    $('#card-layers .swiper-wrapper').toggleClass('hideMask');
                    $('.adoptMeBadgeContainer').toggleClass('noVisible');
                    $('#moveImage').toggleClass('active');
                    return false;
                });

                //https://www.w3schools.com/cssref/playit.asp?filename=playcss_filter&preval=sepia(100%25)
                $('.imagesFilters a').on('click', function (e) {
                    e.preventDefault();

                    $('.cropper-view-box img').css({
                        filter: $(this).attr('data-filter')
                    });

                    $('input#app_create_pet_step_two_filter').val($(this).attr('data-filter'));
                    $('input#app_create_pet_step_two_filter').attr('data-value', $(this).attr('data-filter-name'));

                    return false;
                });

                $('#app_create_pet_step_two_adoptMe').on('change', function () {
                    if ($(this).is(':checked')) {
                        $('.adoptMeBadgeContainer').addClass('badgeVisible');
                    } else {
                        $('.adoptMeBadgeContainer').removeClass('badgeVisible');
                    }
                }).trigger('change');
            }
        });

        mySwiper.allowTouchMove = false;
        mySwiper.init();
    }
}

var stepTwoSubmitBeforeCallback = function () {
    $('input#app_create_pet_step_two_cropData').val(JSON.stringify($('#card-preview-image').cropper('getData')));
    $('input#app_create_pet_step_two_imageData').val(JSON.stringify($('#card-preview-image').cropper('getImageData')));
    $('input#app_create_pet_step_two_containerData').val(JSON.stringify($('#card-preview-image').cropper('getContainerData')));
    $('input#app_create_pet_step_two_canvasData').val(JSON.stringify($('#card-preview-image').cropper('getCanvasData')));
}

function stepThreeCallback() {
    gaSendEvent('app_create_pet_step_three', 'card|step-3', window.location.pathname);

    var lazyInterval = null, maxAttemps = 150, attempts = 0;

    var ajaxFormData;
    if ($('.ajaxFormContainer form:eq(0)').data('ajaxData')) {
        ajaxFormData = $('.ajaxFormContainer form:eq(0)').data('ajaxData');
    }

    $('form').find('select, input').each(function () {
        if ($(this).val()) {
            if ($(this)[0].tagName == 'INPUT') {
                $(this).parents('div:eq(0)').addClass('labeled');
            } else {
                $(this).parents('div:eq(0)').addClass('input-holder-select-selected');
            }
        }
    });

    $('select[required]').on('change', function () {
        if ($(this).parent().find('input').length) {
            $(this).find('option').each(function (i) {
                var option = $(this);
                option.text(option.text().replace(' *', ''));
                if (option.is(':checked')) {
                    option.text(option.text() + ' *');
                }
            })
        } else {
            $(this).find('option').each(function (i) {
                var option = $(this);
                option.text(option.text().replace(' *', ''));
                if (option.is(':checked') && !option.attr('value')) {
                    option.text(option.text() + ' *');
                }
            })
        }
    }).trigger('change');

    if (typeof ajaxFormData.petImage !== 'undefined' && typeof ajaxFormData.frameImage !== 'undefined') {
        $('#petImage').attr('data-original', ajaxFormData.petImage).addClass('lazy');
        $('.layer-layout').attr('data-original', ajaxFormData.frameImage).addClass('lazy');
        $('.lazy').lazyload();
        lazyInterval = setInterval(function () {
            if ($('img.lazy').length == $('img.lazyLoaded').length || $('div.lazy').length == $('div.lazyLoaded').length) {
                clearInterval(lazyInterval);
                lazyInterval = null;
                initStep3();
            } else {
                if (attempts >= maxAttemps) {
                    window.location.reload();
                }
                attempts++;
            }
        }, 100);
    }

    var initStep3 = function () {
        $('#ajaxLoaderImage').remove();
        var baseFont = $('.puppy-card').attr('data-base-font-size') ? $('.puppy-card').attr('data-base-font-size') : 22;
        var newFont = Math.ceil($('.layer-image').width() * baseFont / 600);
        $('.puppy-card').css('font-size', newFont + 'px');

        $('[data-card-target=petName]').on('change', function () {
            if ($(this).val()) {
                $('#petName').text($(this).val());
            }
        }).trigger('change');

        $('[data-card-target=petLocation]').on('change', function () {
            if ($('[data-card-target=petLocation][data-city]').val() || $('[data-card-target=petLocation][data-state]').val()) {
                if ($('[data-card-target=petLocation][data-city]').val() && $('[data-card-target=petLocation][data-state]').val()) {
                    $('#petLocation').text($('[data-card-target=petLocation][data-city]').val() + ($('[data-card-target=petLocation][data-state]').val() ? (', ' + $('[data-card-target=petLocation][data-state]').val().replace('*', '')) : ''));
                } else if ($('[data-card-target=petLocation][data-state]').val()) {
                    $('#petLocation').text($('[data-card-target=petLocation][data-state]').val().replace('*', ''));
                } else if ($('[data-card-target=petLocation][data-city]').val()) {
                    $('#petLocation').text($('[data-card-target=petLocation][data-city]').val());
                } else {
                    $('#petLocation').text('Home City, State');
                }
            } else {
                $('#petLocation').text('Home City, State');
            }
        }).trigger('change');

        $('[data-card-target=stats]').on('change', function () {
            var id = $(this).attr('data-card-target-id');
            if ($('select[data-card-target=stats][data-card-target-id=' + id + ']').val()) {
                $('#stats' + id).find('.label').text($('select[data-card-target=stats][data-card-target-id=' + id + '] option:selected').text().replace('*', ''));
            }
            if ($('input[data-card-target=stats][data-card-target-id=' + id + ']').val()) {
                $('#stats' + id).find('.value').text($('input[data-card-target=stats][data-card-target-id=' + id + ']').val());
            }
        })

        $('[data-card-target=stats]').each(function () {
            $(this).trigger('change');
        });

        if (typeof ajaxFormData.adoptMe !== 'undefined' && ajaxFormData.adoptMe) {
            $('#adoptMe').show();
        } else {
            $('#adoptMe').remove();
        }

        var initCropper = function () {
            $('#petImage').cropper({
                autoCropArea: 1,
                strict: true,
                guides: false,
                highlight: false,
                dragCrop: false,
                viewMode: 0,
                scalable: true,
                responsive: true,
                restore: true,
                checkCrossOrigin: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                minContainerWidth: 100,
                minContainerHeight: 100
            });
        }

        setTimeout(function () {
            initCropper();
        }, 50);

        $('#petImage').on('ready', function (e) {
            if (typeof ajaxFormData.crop == 'object') {
                $('#petImage').cropper("setData", ajaxFormData.crop);
            }

            var factor = parseFloat(ajaxFormData.container.width / $('.layer-image').width());
            ajaxFormData.canvas.width /= factor;
            ajaxFormData.canvas.height /= factor;
            ajaxFormData.canvas.left /= factor;
            ajaxFormData.canvas.top /= factor;

            if (typeof ajaxFormData.canvas == 'object') {
                $('#petImage').cropper("setCanvasData", ajaxFormData.canvas);
            }

            if (typeof ajaxFormData.filterImage !== 'undefined') {
                $('.cropper-view-box img, .cropper-canvas img').css({
                    filter: ajaxFormData.filterImage
                });
            }

            $('#petImage').cropper("setDragMode", 'move');
            $('#petImage').cropper("setCropBoxData", {
                width: $('.layer-image').width(),
                height: $('.layer-image').height()
            });

            $('.noVisible').removeClass('noVisible');
        });
    };
}

function stepConfirmCallback() {
    gaSendEvent('app_create_pet_step_confirm', 'card|step-4', window.location.pathname);

    var ajaxFormData, lazyInterval = null, maxAttemps = 150, attempts = 0;

    if ($('.ajaxFormContainer form:eq(0)').data('ajaxData')) {
        ajaxFormData = $('.ajaxFormContainer form:eq(0)').data('ajaxData');
    }

    $('form').find('select, input').each(function () {
        if ($(this).val()) {
            if ($(this)[0].tagName == 'INPUT') {
                $(this).parents('div:eq(0)').addClass('labeled');
            } else {
                $(this).parents('div:eq(0)').addClass('input-holder-select-selected');
            }
        }
    });

    $('#app_create_pet_step_four_agree3Terms').on('change', function () {
        var checked = $(this).is(':checked');
        $('input[data-should-be-required], select[data-should-be-required]').each(function (i) {
            $(this).attr('required', checked);
            if (checked) {
                $(this).parent().find('label').addClass('required');
            } else {
                $(this).parent().find('label').removeClass('required');
            }
            if ($(this)[0].tagName == 'SELECT') {
                $(this).find('option').each(function (i) {
                    var option = $(this);
                    option.text(option.text().replace(' *', ''));
                    if (checked) {
                        if (option.is(':checked') && !option.attr('value')) {
                            option.text(option.text() + ' *');
                        }
                    }
                })
            } else {
                if (checked) {
                    $(this).parents('div:eq(0)').find('label').addClass('required');
                } else {
                    $(this).parents('div:eq(0)').find('label').removeClass('required');
                }
            }
        });
    });

    $('[data-card-target=petLocation]').on('change', function () {
        $('#petLocation').text($('[data-card-target=petLocation][data-city]').val() + ', ' + $('[data-card-target=petLocation][data-state]').val());
    });

    $('[data-card-target=stats]').on('change', function () {
        var id = $(this).attr('data-card-target-id');
        $('#stats' + id).find('.label').text($('select[data-card-target=stats][data-card-target-id=' + id + '] option:selected').text());
        $('#stats' + id).find('.value').text($('input[data-card-target=stats][data-card-target-id=' + id + ']').val());
    })

    if (typeof ajaxFormData.petImage !== 'undefined' && typeof ajaxFormData.frameImage !== 'undefined') {
        $('#petImage').attr('data-original', ajaxFormData.petImage).addClass('lazy');
        $('.layer-layout').attr('data-original', ajaxFormData.frameImage).addClass('lazy');
        $('.lazy').lazyload();
        lazyInterval = setInterval(function () {
            if ($('img.lazy').length == $('img.lazyLoaded').length || $('div.lazy').length == $('div.lazyLoaded').length) {
                clearInterval(lazyInterval);
                lazyInterval = null;
                initStepConfirm();
            } else {
                if (attempts >= maxAttemps) {
                    window.location.reload();
                }
                attempts++;
            }
        }, 100);
    }

    var initStepConfirm = function () {
        var baseFont = $('.puppy-card').attr('data-base-font-size') ? $('.puppy-card').attr('data-base-font-size') : 22;
        var newFont = Math.ceil($('.layer-image').width() * baseFont / 600);
        $('.puppy-card').css('font-size', newFont + 'px');

        if (typeof ajaxFormData.cardData !== 'undefined') {
            $('#petName').text(ajaxFormData.cardData.petName);
            $('#petLocation').text(ajaxFormData.cardData.city + (ajaxFormData.cardData.state ? (', ' + ajaxFormData.cardData.state) : ''));

            if (ajaxFormData.adoptMe) {
                $('#adoptMe').show();
            } else {
                $('#adoptMe').remove();
            }
            $('#stats1').find('.label').text(ajaxFormData.cardData.stat1Id);
            $('#stats1').find('.value').text(ajaxFormData.cardData.stat1Value);
            $('#stats2').find('.label').text(ajaxFormData.cardData.stat2Id);
            $('#stats2').find('.value').text(ajaxFormData.cardData.stat2Value);
            $('#stats3').find('.label').text(ajaxFormData.cardData.stat3Id);
            $('#stats3').find('.value').text(ajaxFormData.cardData.stat3Value);
            $('#stats4').find('.label').text(ajaxFormData.cardData.stat4Id);
            $('#stats4').find('.value').text(ajaxFormData.cardData.stat4Value);

            $('#petImage').cropper({
                autoCropArea: 1,
                strict: true,
                guides: false,
                highlight: false,
                dragCrop: false,
                viewMode: 0,
                scalable: true,
                responsive: true,
                restore: true,
                checkCrossOrigin: false,
                cropBoxMovable: false,
                cropBoxResizable: false,
                minContainerWidth: 100,
                minContainerHeight: 100
            });

            $('#petImage').on('ready', function (e) {
                $('#ajaxLoaderImage').remove();
                if (typeof ajaxFormData.crop == 'object') {
                    $('#petImage').cropper("setData", ajaxFormData.crop);
                }

                var factor = parseFloat(ajaxFormData.container.width / $('.layer-image').width());
                ajaxFormData.canvas.width /= factor;
                ajaxFormData.canvas.height /= factor;
                ajaxFormData.canvas.left /= factor;
                ajaxFormData.canvas.top /= factor;

                if (typeof ajaxFormData.canvas == 'object') {
                    $('#petImage').cropper("setCanvasData", ajaxFormData.canvas);
                }

                if (typeof ajaxFormData.filterImage !== 'undefined') {
                    $('.cropper-view-box img, .cropper-canvas img').css({
                        filter: ajaxFormData.filterImage
                    });
                }

                $('#petImage').cropper("setDragMode", 'move');
                $('#petImage').cropper("setCropBoxData", {
                    width: $('.layer-image').width(),
                    height: $('.layer-image').height()
                });

                $('.noVisible').removeClass('noVisible');
            });

            $('[data-card-target=stats]').each(function () {
                $(this).trigger('change');
            });

            $('#app_create_pet_step_four_agree3Terms').trigger('change');

            $('[data-card-target=petLocation]').trigger('change');

            $('select, input').trigger('change');
        }
    }
}

var generationInterval = null, maxCheckAttempts = 30, attempts = 0;
var checkForCardGeneration = function (hash) {
    generationInterval = setInterval(function () {
        if (attempts <= maxCheckAttempts) {
            $.ajax({
                url: '/card/check/' + hash,
                type: 'POST',
                data: {
                    hash: hash
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (json) {
                    if (json.success) {
                        clearInterval(generationInterval);
                        generationInterval = null;
                        gaSendEvent('app_create_pet_step_confirm', 'card|complete', window.location.pathname);
                        window.location.href = json.url;
                    }
                    attempts++;
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            })
        } else {
            window.location.href = '/card/unable/' + hash;
        }
    }, 2000);

};

var refreshFlash = function () {
    $.ajax({
        type: "GET",
        url: '/flash-messages',
        dataType: 'json',
        cache: false,
        success: function (json) {
            if (typeof json.success !== 'undefined' && json.success) {
                if (typeof json.redirect !== 'undefined') {
                    window.location.href = json.redirect;
                    return;
                }
                if (json.html.length) {
                    $('.systemMessages').html(json.html);
                    $('.systemMessages').show();
                    $('html, body').animate({
                        scrollTop: $('.systemMessages').offset().top - 10
                    }, 200);
                } else {
                    $('.systemMessages').hide();
                }
            }
        },
        error: function (e) {

        }
    });
}

var gaSubmitEvents = function (form) {
    if (typeof form === 'object') {
        form.find('[data-event-submit-category]').each(function (i) {
            if ($(this).attr('data-event-submit-action')) {
                gaSendEvent($(this).attr('data-event-submit-category'), $(this).attr('data-event-submit-action').replace('_value_', $(this).attr('data-value') ? $(this).attr('data-value') : $(this).val()), window.location.pathname);
            }
        });
    }
}

function AjaxFormAction(url, loadCallback, beforeSubmitCallback) {
    var form;
    var object = this;
    var jsonData;
    var sendXhr = null;
    var formSubmit = function (form) {
        $('.ajaxFormContainer form:eq(0)').on('submit', function (e) {
            e.preventDefault();
            $(this).find('button[type=submit]').click();
            var valid = form[0].checkValidity();
            if (valid) {
                sendForm();
            }
            return false;
        });

        var sendForm = function () {
            if (sendXhr) {
                sendXhr.abort();
                sendXhr = null;
            }
            if (typeof beforeSubmitCallback === 'function') {
                beforeSubmitCallback(form);
            }
            if (typeof gaSubmitEvents === 'function') {
                gaSubmitEvents(form);
            }
            var data = new FormData(form[0]);
            sendXhr = $.ajax({
                type: "POST",
                enctype: form.attr('enctype'),
                url: url,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                dataType: 'json',
                success: function (json) {
                    if (typeof json.success !== 'undefined' && json.success) {
                        if (typeof json.redirect !== 'undefined' && json.redirect) {
                            window.location.href = json.redirect;
                            return;
                        }
                    }
                    if (typeof json.refreshFlash !== 'undefined') {
                        refreshFlash();
                    }
                    if (typeof json.html !== 'undefined') {
                        $('.ajaxFormContainer').html(json.html);
                    }

                    if (typeof json.hash !== 'undefined') {
                        checkForCardGeneration(json.hash);
                        return;
                    } else {
                        $('#uploadingPopup').addClass('hidden');
                        $('#generatedPopup').addClass('hidden');
                        if (typeof json.data === 'object') {
                            $('.ajaxFormContainer').find('form:eq(0)').data('ajaxData', json.data);
                        }
                        startForm($('.ajaxFormContainer').find('form:eq(0)'));
                    }

                    if (typeof createStep !== 'undefined') {
                        switch (createStep) {
                            case 1:
                                gaSendEvent('app_create_pet_step_one', 'card|step-1|upload-complete', window.location.pathname);
                                break;
                        }
                    }
                },
                error: function (e) {
                    if (typeof createStep !== 'undefined') {
                        switch (createStep) {
                            case 1:
                                gaSendEvent('app_create_pet_step_one', 'card|step-1|upload-failed', window.location.pathname);
                                break;
                        }
                    }
                }
            });
        }
    };

    var startForm = function (form) {
        if (typeof loadCallback === 'function') {
            loadCallback(form);
        }
        $('.ajaxFormContainer form:eq(0)').find('input, select').each(function () {
            if ($(this).attr('required')) {
                var handler = $(this);
                handler.parents().each(function () {
                    if (!$(this).is(':visible') || $(this).css('opacity') == '0') {
                        handler.removeAttr('required');
                        return false;
                    }
                })
            }
        });
        bindGaEvents();
        refreshFlash();
        formSubmit(form);
    }

    var loadForm = function (url) {
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            cache: false,
            beforeSend: function () {
                $('.ajaxFormContainer').removeClass('ajaxFormLoaded');
                if ($('.ajaxFormContainer').data('loaderHtml')) {
                    $('.ajaxFormContainer').html($('.ajaxFormContainer').data('loaderHtml'));
                }
            },
            success: function (json) {
                if (typeof json.success !== 'undefined' && json.success) {
                    if (typeof json.html !== 'undefined') {
                        $('.ajaxFormContainer').data('loaderHtml', $('.ajaxFormContainer').find('#ajaxLoaderImage')[0].outerHTML);
                        $('.ajaxFormContainer').append(json.html);
                        $("img.lazy").lazyload();
                        form = $('.ajaxFormContainer').find('form').first();
                        //$('.ajaxFormContainer').addClass('ajaxFormLoaded');
                        if (typeof json.data === 'object') {
                            form.data('ajaxData', json.data);
                        }
                        startForm(form);
                    }
                }
                if (typeof json.redirect !== 'undefined' && json.redirect) {
                    window.location.href = json.redirect;
                    return;
                }
            },
            error: function (e) {

            }
        });
    }

    loadForm(url);

    return {
        loadForm: loadForm,
        jsonData: function () {
            return jsonData;
        }
    };
}

var datetimepickerRange = function () {
    var dateTimeFormat = 'YYYY-MM-DD HH:mm',
        fromTime = $(".datetimepicker_range.date_from")
            .datetimepicker({
                language: 'pl',
                allowInputToggle: true,
                format: dateTimeFormat
            }).on('dp.change', function (e) {
                //toTime.data("DateTimePicker").minDate(e.date);
            })
    toTime = $(".datetimepicker_range.date_to").datetimepicker({
        language: 'pl',
        allowInputToggle: true,
        format: dateTimeFormat,
    }).on('dp.change', function (e) {
        //fromTime.data("DateTimePicker").maxDate(e.date);
    })
};

var datetimepickerDate = function () {
    var dateTimeFormat = 'YYYY-MM-DD',
        time = $(".datetimepicker.date")
            .datetimepicker({
                locale: 'pl_PL',
                allowInputToggle: true,
                format: dateTimeFormat
            })
};

var datetimepickerTime = function () {
    var dateTimeFormat = 'HH:mm';
    $(".datetimepicker_time")
        .datetimepicker({
            language: 'pl',
            allowInputToggle: true,
            format: dateTimeFormat
        })
};

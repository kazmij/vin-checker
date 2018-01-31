function callback_tinymce_init(editor) {

}

$(function () {
    $('body').on('click', '.buttonElfinderOpen', function (e) {
        e.preventDefault();
        var input = $(this).parents('.form-group:eq(0)').find('input').first();
        var childWin = window.open($(this).attr('data-href'), "popupWindow", "height=450, width=900");
    });

    $('body').on('click', '.elfinderImagesRemove', function (e) {
        var inputId = $(this).parent().find('img').attr('data-input-id'),
            input = $('#' + inputId),
            imagesToRemove = $('select.imagesToRemove');

        if (input.attr('data-id') && imagesToRemove.length) {
            var option = imagesToRemove.find('option[value=' + input.attr('data-id') + ']');
            if (option.length) {
                option.attr('selected', true);
            }
        }
        $(this).parent().remove();
        if ($('.elfinderImageCollection').length > 1) {
            input.remove();
        } else {
            input.val('');
        }
        normalizeElfinderImageCollectionInputs();
    });

    $('.confirmAction, .btn-delete').each(function (i) {
        $(this).attr('data-href', $(this).attr('href'));
        $(this).removeAttr('href');
    });

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

    $('.portalSelect input[type=checkbox]').on('change', function(e){
        if($(this).is(':checked') && $(this).val() == 183) {
            $('.portalSelect input[type=checkbox][value!="183"]').prop('checked', false);
        } else if($('.portalSelect input[type=checkbox][value="183"]').is(':checked')) {
            $('.portalSelect input[type=checkbox][value="183"]').prop('checked', false);
        }

        return true;
    });
})

var setValue = function (value, element_id) {
    $('[data-type="elfinder-input-field"]' + (element_id ? '[id="' + element_id + '"]' : '')).val(value).change();

}

var addElfinderImages = function (files, htmlObjectId, multiple) {
    if (multiple) {
        var inputHtmlPrototype = $('input.elfinderImageCollection:last')[0].outerHTML;
        var matches = inputHtmlPrototype.match(/(_\d+_)/gm);
        var currentIndex = parseInt(matches[0].replace('_', ''));
        currentIndex += $('input.elfinderImageCollection:last').val() ? 1 : 0;
        inputHtmlPrototype = inputHtmlPrototype.replace(/_\d+_/gm, '_#_');
        inputHtmlPrototype = inputHtmlPrototype.replace(/\[\d+\]/gm, '[#]');
        var getHtmlInput = function () {
            var input = inputHtmlPrototype.replace(/_\#_/gm, '_' + currentIndex + '_');
            input = input.replace(/\[#\]/gm, '[' + currentIndex + ']');
            if ($('body').find('input#' + $(input).attr('id')).length) {

            } else {
                $('input.elfinderImageCollection:last').parents('.form-group:eq(0)').append(input);
            }
            input = $('body').find('input#' + $(input).attr('id'));
            input.attr('data-id', '');
            currentIndex++;
            return input;
        };
        var input;
        files.forEach(function (el, index, arr) {
            input = getHtmlInput();
            if ($('input.elfinderImageCollection:eq(0)').parents('.form-group:eq(0)').find('.elfinderImagesThumb:last').length) {
                $('input.elfinderImageCollection:eq(0)').parents('.form-group:eq(0)').find('.elfinderImagesThumb:last').after('<div class="elfinderImagesThumb"><a title="Remove" class="elfinderImagesRemove" href="#removeImage"><i class="fa fa-2x fa-times"></i></a><a class="fancybox" href="/' + el.path + '"><img data-input-id="' + (input.attr('id')) + '" src="/' + el.path + '" class="img-thumbnail"/></a></div>');
            } else {
                $('input.elfinderImageCollection:eq(0)').parents('.form-group:eq(0)').find('button:last').after('<div class="elfinderImagesThumb"><a title="Remove" class="elfinderImagesRemove" href="#removeImage"><i class="fa fa-2x fa-times"></i></a><a class="fancybox" href="/' + el.path + '"><img data-input-id="' + (input.attr('id')) + '" src="/' + el.path + '" class="img-thumbnail"/></a></div>');
            }
            input.val('/' + el.path).change();
        });
    } else {
        $('#' + htmlObjectId).parents('.form-group:eq(0)').find('.elfinderImagesThumb').remove();
        $('[data-type="elfinder-input-field"]' + (htmlObjectId ? '[id="' + htmlObjectId + '"]' : '')).val('').change();
        files.forEach(function (el, index, arr) {
            $('#' + htmlObjectId).parents('.form-group:eq(0)').append('<div class="elfinderImagesThumb"><a class="fancybox" href="/' + el.path + '"><img src="/' + el.path + '" class="img-thumbnail"/></a></div>');
            $('[data-type="elfinder-input-field"]' + (htmlObjectId ? '[id="' + htmlObjectId + '"]' : '')).val('/' + el.path).change();
        })
    }
    if (typeof $.fancybox !== 'undefined') {
        $('.fancybox').fancybox();
    }
}

var normalizeElfinderImageCollectionInputs = function () {
    var k = 0;
    $('.elfinderImageCollection').each(function (i) {
        var currentId = $(this).attr('id'),
            matches = currentId.match(/(_\d+_)/gm),
            currentIndex = parseInt(matches[0].replace('_', '')),
            inputCurrentVal = $(this).val(),
            inputHtml = $(this)[0].outerHTML,
            regex1 = new RegExp('_' + currentIndex + '_', 'gm'),
            regex2 = new RegExp('\\[' + currentIndex + '\\]', 'gm'),
            newInputHtml = inputHtml.replace(regex1, '_' + k + '_');
        newInputHtml = newInputHtml.replace(regex2, '[' + k + ']');
        $(this).after(newInputHtml);
        $(this).remove();
        var inputObj = $('#' + $(newInputHtml).attr('id'));
        inputObj.val(inputCurrentVal);
        $('img[data-input-id=' + currentId + ']').attr('data-input-id', inputObj.attr('id'));
        k++;
    });
}

var ajaxAction = function (btn, href, sendData) {
    if (btn && href) {
        if (!sendData) {
            sendData = {};
        }
        var headers = {};

        $.ajax({
            headers: headers,
            type: btn.attr('data-ajax-action') ? btn.attr('data-ajax-action') : 'post',
            url: href,
            data: sendData,
            dataType: 'json',
            beforeSend: function () {
            },
            success: function (json) {
                $('.modal.in').modal('hide');
                if (typeof window[btn.attr('data-ajax-callback')] === 'function') {
                    return window[btn.attr('data-ajax-callback')](btn, json);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        })
    } else {
        console.error('ajaxAction: missed arguments!');
        return false;
    }
}

var categoryActionClick = function (el) {
    if ($(el).hasClass('confirmAction')) {

    } else {
        window.location.href = $(el).attr('data-href');
    }
}

var datetimepickerRange = function () {
    var dateTimeFormat = 'YYYY-MM-DD HH:mm',
        fromTime = $(".datetimepicker_range.date_from")
            .datetimepicker({
                allowInputToggle: true,
                format: dateTimeFormat,
            }).on('dp.change', function (e) {
                //toTime.data("DateTimePicker").minDate(e.date);
            })
    toTime = $(".datetimepicker_range.date_to").datetimepicker({
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
                allowInputToggle: true,
                format: dateTimeFormat
            })
};

var datetimepickerTime = function () {
    var dateTimeFormat = 'HH:mm';
    $(".datetimepicker_time")
        .datetimepicker({
            allowInputToggle: true,
            format: dateTimeFormat,
        })
};






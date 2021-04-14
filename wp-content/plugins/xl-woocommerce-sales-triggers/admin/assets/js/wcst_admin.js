var wcst_admin_change_content = null;
jQuery(document).ready(function ($) {
    'use strict';

    function timestamp_converter(UNIX_timestamp) {
        var newDate = new Date(UNIX_timestamp * 1000);
        var year = newDate.getFullYear();
        var month = newDate.getMonth();
        var date = newDate.getDate();
        var hour = newDate.getHours();
        var min = "0" + newDate.getMinutes();
        var sec = "0" + newDate.getSeconds();

        month = (month + 1);

        if (month < 9) {

            month = "0" + month;
        }

        if (hour < 9) {
            hour = "0" + hour;
        }

        var time = year + "/" + month + "/" + date + " " + hour + ":" + min.substr(-2) + ":" + sec.substr(-2);
        return time;
    }

    if ($(".wcst_manual_timer").length > 0) {
        $(".wcst_manual_timer").each(function () {
            var $this = $(this);
            var childSpan = $this.children("span");
            var displayFormat;
            displayFormat = childSpan.attr("data-format");
            var diffTimestamp = parseInt(childSpan.attr("data-diff"));
            var modifiedDate = new Date().getTime() + diffTimestamp * 1000;
            childSpan.wcstCountdown(modifiedDate, function (event) {
                $(this).text(event.strftime(displayFormat));
            });
        });
    }

    $("select[name=_wcst_data_choose_trigger]").on("change", function () {
        var current_slug = this.value;
        if ($(".wcst_input_replace_id").length > 0) {
            $(".wcst_input_replace_id").each(function () {
                var getshortcode_val = $(this).val();
                var ID = $("input[name=post_ID]").val();
                getshortcode_val = getshortcode_val.replace("{TRIGGER_SLUG}", current_slug);
                getshortcode_val = getshortcode_val.replace("{TRIGGER_ID}", ID);
                $(this).val(getshortcode_val);
            });
        }
    });

    /**
     * Set up the functionality for CMB2 conditionals.
     */
    window.WCST_CMB2ConditionalsInit = function (changeContext, conditionContext) {
        var loopI, requiredElms, uniqueFormElms, formElms;

        if ('undefined' === typeof changeContext) {
            changeContext = 'body';
        }
        changeContext = $(changeContext);

        if ('undefined' === typeof conditionContext) {
            conditionContext = 'body';
        }
        conditionContext = $(conditionContext);
        window.wcst_admin_change_content = conditionContext;
        changeContext.on('change', 'input, textarea, select', function (evt) {
            var elm = $(this),
                    fieldName = $(this).attr('name'),
                   dependantsSeen = [],
                    checkedValues,
                    elmValue;

            var dependants = $('[data-wcst-conditional-id="' + fieldName + '"]', conditionContext);
            if (!elm.is(":visible")) {
                return;
            }

            // Only continue if we actually have dependants.
            if (dependants.length > 0) {

                // Figure out the value for the current element.
                if ('checkbox' === elm.attr('type')) {
                    checkedValues = $('[name="' + fieldName + '"]:checked').map(function () {
                        return this.value;
                    }).get();
                } else if ('radio' === elm.attr('type')) {
                    if ($('[name="' + fieldName + '"]').is(':checked')) {
                        elmValue = elm.val();
                    }
                } else {
                    elmValue = evt.currentTarget.value;
                }

                dependants.each(function (i, e) {
                    var loopIndex = 0,
                            current = $(e),
                            currentFieldName = current.attr('name'),
                            requiredValue = current.data('wcst-conditional-value'),
                            currentParent = current.parents('.cmb-row:first'),
                            shouldShow = false;


                    // Only check this dependant if we haven't done so before for this parent.
                    // We don't need to check ten times for one radio field with ten options,
                    // the conditionals are for the field, not the option.
                    if ('undefined' !== typeof currentFieldName && '' !== currentFieldName && $.inArray(currentFieldName, dependantsSeen) < 0) {
                        dependantsSeen.push = currentFieldName;

                        if ('checkbox' === elm.attr('type')) {
                            if ('undefined' === typeof requiredValue) {
                                shouldShow = (checkedValues.length > 0);
                            } else if ('off' === requiredValue) {
                                shouldShow = (0 === checkedValues.length);
                            } else if (checkedValues.length > 0) {
                                if ('string' === typeof requiredValue) {
                                    shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                                } else if (Array.isArray(requiredValue)) {
                                    for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                        if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                            shouldShow = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        } else if ('undefined' === typeof requiredValue) {
                            shouldShow = (elm.val() ? true : false);
                        } else {
                            if ('string' === typeof requiredValue) {
                                shouldShow = (elmValue === requiredValue);
                            }
                            if ('number' === typeof requiredValue) {
                                shouldShow = (elmValue == requiredValue);
                            } else if (Array.isArray(requiredValue)) {
                                shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                            }
                        }

                        // Handle any actions necessary.
                        currentParent.toggle(shouldShow);

                        window.wcst_admin_change_content.trigger("wcst_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);

                        if (current.data('conditional-required')) {
                            current.prop('required', shouldShow);
                        }

                        // If we're hiding the row, hide all dependants (and their dependants).
                        if (false === shouldShow) {
                            // CMB2ConditionalsRecursivelyHideDependants(currentFieldName, current, conditionContext);
                        }

                        // If we're showing the row, check if any dependants need to become visible.
                        else {
                            if (1 === current.length) {
                                current.trigger('change');
                            } else {
                                current.filter(':checked').trigger('change');
                            }
                        }
                    } else {

                        /** Handling for */
                        if (current.hasClass("wcst-cmb2-tabs") || current.hasClass("cmb2-wcst_html")) {

                            if ('checkbox' === elm.attr('type')) {
                                if ('undefined' === typeof requiredValue) {
                                    shouldShow = (checkedValues.length > 0);
                                } else if ('off' === requiredValue) {
                                    shouldShow = (0 === checkedValues.length);
                                } else if (checkedValues.length > 0) {
                                    if ('string' === typeof requiredValue) {
                                        shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                                    } else if (Array.isArray(requiredValue)) {
                                        for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                            if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                                shouldShow = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            } else if ('undefined' === typeof requiredValue) {
                                shouldShow = (elm.val() ? true : false);
                            } else {
                                if ('string' === typeof requiredValue) {
                                    shouldShow = (elmValue === requiredValue);
                                }
                                if ('number' === typeof requiredValue) {
                                    shouldShow = (elmValue == requiredValue);
                                } else if (Array.isArray(requiredValue)) {
                                    shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                                }
                            }

                            currentParent.toggle(shouldShow);
                            window.wcst_admin_change_content.trigger("wcst_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);

                        }
                    }
                });
            }
            if (elm.hasClass('wcst_icon_select')) {
                var ecomm_font_icon_val = $(this).val();
                if (ecomm_font_icon_val > 0) {
                    elm.next('.wcst_icon_preview').html('<i class="wcst_custom_icon wcst-ecommerce' + wcst_return_font_val(ecomm_font_icon_val) + '"></i>');
                }
            }
        });

        window.wcst_admin_change_content.on("wcst_conditional_runs", function (e, current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue) {

            var loopIndex = 0;
            var checkedValues;

           shouldShow = false;
            if (typeof current.attr('data-wcst-conditional-value') == "undefined") {
                return;
            }

            elm = $("[name='" + current.attr('data-wcst-conditional-id') + "']", changeContext).eq(0);

            if (!elm.is(":visible")) {
                return;
            }
            // Figure out the value for the current element.
            if ('checkbox' === elm.attr('type')) {
                checkedValues = $('[name="' + current.attr('data-wcst-conditional-id') + '"]:checked').map(function () {
                    return this.value;
                }).get();
            } else if ('radio' === elm.attr('type')) {
                elmValue = $('[name="' + current.attr('data-wcst-conditional-id') + '"]:checked').val();

            }

            requiredValue = current.data('wcst-conditional-value');

            // Only check this dependant if we haven't done so before for this parent.
            // We don't need to check ten times for one radio field with ten options,
            // the conditionals are for the field, not the option.
            if ('undefined' !== typeof currentFieldName && '' !== currentFieldName) {

                if ('checkbox' === elm.attr('type')) {
                    if ('undefined' === typeof requiredValue) {
                        shouldShow = (checkedValues.length > 0);
                    } else if ('off' === requiredValue) {
                        shouldShow = (0 === checkedValues.length);
                    } else if (checkedValues.length > 0) {
                        if ('string' === typeof requiredValue) {
                            shouldShow = ($.inArray(requiredValue, checkedValues) > -1);
                        } else if (Array.isArray(requiredValue)) {
                            for (loopIndex = 0; loopIndex < requiredValue.length; loopIndex++) {
                                if ($.inArray(requiredValue[loopIndex], checkedValues) > -1) {
                                    shouldShow = true;
                                    break;
                                }
                            }
                        }
                    }
                } else if ('undefined' === typeof requiredValue) {
                    shouldShow = (elm.val() ? true : false);
                } else {

                    if ('string' === typeof requiredValue) {
                        shouldShow = (elmValue === requiredValue);
                    }
                    if ('number' === typeof requiredValue) {
                        shouldShow = (elmValue == requiredValue);
                    } else if (Array.isArray(requiredValue)) {

                        shouldShow = ($.inArray(elmValue, requiredValue) > -1);
                    }
                }

                // Handle any actions necessary.
                currentParent.toggle(shouldShow);

                window.wcst_admin_change_content.trigger("wcst_internal_conditional_runs", [current, currentFieldName, requiredValue, currentParent, shouldShow, elm, elmValue]);

                if (current.data('conditional-required')) {
                    current.prop('required', shouldShow);
                }

                // If we're hiding the row, hide all dependants (and their dependants).
                if (false === shouldShow) {
                    // CMB2ConditionalsRecursivelyHideDependants(currentFieldName, current, conditionContext);
                }

                // If we're showing the row, check if any dependants need to become visible.
                else {
                    if (1 === current.length) {
                        current.trigger('change');
                    } else {
                        current.filter(':checked').trigger('change');
                    }
                }
            }
        });

        $('[data-wcst-conditional-id]', conditionContext).not(".wcst_custom_wrapper_group").parents('.cmb-row:first').hide({
            "complete": function () {
                $("body").trigger("wcst_w_trigger_conditional_on_load");

                uniqueFormElms = [];
                $(':input', changeContext).each(function (i, e) {
                    var elmName = $(e).attr('name');
                    if ('undefined' !== typeof elmName && '' !== elmName && -1 === $.inArray(elmName, uniqueFormElms)) {
                        uniqueFormElms.push(elmName);
                    }
                });

                for (loopI = 0; loopI < uniqueFormElms.length; loopI++) {
                    formElms = $('[name="' + uniqueFormElms[loopI] + '"]');
                    if (1 === formElms.length || !formElms.is(':checked')) {
                        formElms.trigger('change');
                    } else {
                        formElms.filter(':checked').trigger('change');
                    }
                }

            }
        });



        $( '.cmb-tab-nav' ).on( 'click', 'a', function ( e )
        {
            e.preventDefault();

            var $li = $( this ).parent(),
                panel = $li.data( 'panel' );

            $(document).trigger('wcst_cmb2_options_tabs_activated',[panel]);
        } );



        $(document).on('wcst_cmb2_options_tabs_activated', function (e, panel) {

            var uniqueFormElms = [];
            $(':input', ".cmb-tab-panel").each(function (i, e) {
                var elmName = $(e).attr('name');
                if ('undefined' !== typeof elmName && '' !== elmName && -1 === $.inArray(elmName, uniqueFormElms) && $(e).is(":visible")) {
                    uniqueFormElms.push(elmName);
                }
            });

            for (loopI = 0; loopI < uniqueFormElms.length; loopI++) {
                formElms = $('[name="' + uniqueFormElms[loopI] + '"]');
                if (1 === formElms.length || !formElms.is(':checked')) {
                    formElms.trigger('change');
                } else {
                    formElms.filter(':checked').trigger('change');
                }
            }
        });
    };

    if (typeof pagenow !== "undefined" && ("wcst_trigger" == pagenow || pagenow === 'product')) {
        WCSTCMB2ConditionalsInit('#post .cmb2-wrap.wcst_cmb2_wrapper', '#post .cmb2-wrap.wcst_cmb2_wrapper');
        WCST_CMB2ConditionalsInit('#post  .cmb2-wrap.wcst_cmb2_wrapper', '#post  .cmb2-wrap.wcst_cmb2_wrapper');
    }

    $('.wcst_global_option .wcst_options_page_left_wrap').removeClass('dispnone');

    $(window).load(function () {
        $("body").on("click", ".cmb2_wcst_acc_head", function () {
            if ($(this).hasClass("active")) {
                $(this).next(".cmb2_wcst_wrapper_ac_data").toggle(false);
                $(this).parents(".cmb2_wcst_wrapper_ac").removeClass('opened');
            } else {
                $(this).next(".cmb2_wcst_wrapper_ac_data").toggle(true);
                $(this).parents(".cmb2_wcst_wrapper_ac").addClass('opened');
            }
            $(this).toggleClass("active");
        });
        if ($("select.wcst_icon_select").length > 0) {
            $("select.wcst_icon_select").each(function () {
                $(this).trigger("change");
            });
        }

    });

    /** FUNCTIONS DECLARATION STARTS **/
    /**
     * Function to return font value
     * @param $icon_num
     * @returns {*}
     */
    function wcst_return_font_val($icon_num) {
        if ($icon_num.length === 3) {
            return $icon_num;
        } else if ($icon_num.length === 2) {
            return '0' + $icon_num;
        } else if ($icon_num.length === 1) {
            return '00' + $icon_num;
        } else {
            return '001';
        }
    }

    // jQuery('table.wcst-instance-table #the-list, table.pages #the-list').sortable({
    //     'items': 'tr',
    //     'axis': 'y',
    //     'helper': fixHelper,
    //     'update' : function(e, ui) {
    //         jQuery.post( ajaxurl, {
    //             action: 'update-menu-order',
    //             order: jQuery('table.wcst-instance-table #the-list').sortable('serialize'),
    //         });
    //     }
    // });

    var fixHelper = function (e, ui) {
        ui.children().children().each(function () {
            $(this).width($(this).width());
        });
        return ui;
    };

});

function wcst_show_tb(title, id) {
    wcst_modal_show(title, "#WCST_MB_inline?height=500&amp;width=1000&amp;inlineId=" + id + "");

}
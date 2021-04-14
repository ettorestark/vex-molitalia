(function ($) {
    'use strict';
    var hardTxt;
    var wcstRefresh_timers_count = 0;
    var wcstCurrent_received_timers = 0;

    function wcst_clear_variation_html() {
        // Fires whenever variation selects are changed
        $(".wcst_low_stock").html('');
        $(".wcst_deal_expiry").not(".wcst_static").html('');
        $('.woocommerce-variation.single_variation .wcst_you_save_variation').html('');
    }

    $(".variations_form").on("woocommerce_variation_select_change", function () {
        wcst_clear_variation_html();
    });

    $(".variations_form").on("show_variation", function (event, variation) {

        wcst_clear_variation_html();

        // Fired when the user selects all the required drop-downs/ attributes and a final variation is selected/ shown
        if (typeof variation !== 'undefined' && typeof wcst_data !== 'undefined' && typeof wcst_data.settings !== 'undefined') {
            var variation_id = variation.variation_id;
            var variation_data = {};
            variation_data.from = variation.wcst_sale_from;
            variation_data.to = variation.wcst_sale_to;

            var variation_is_in_stock = variation.is_in_stock;
            var variation_is_on_sale = 1;

            if (variation.display_price == variation.display_regular_price) {
                variation_is_on_sale = 0;
            }
            var stockQty = variation.max_qty;

            if (stockQty === 0) {
                variation_is_in_stock = false;
            }
            if (variation_is_in_stock === 0) {
                return false;
            }

            // check for stock status
            if (typeof wcst_data.settings.low_stock !== 'undefined' && wcst_data.compatibility.low_stock.indexOf(wcst_data.product_type) !== -1) {
                if (Object.keys(wcst_data.settings.low_stock).length > 0 && $(".wcst_low_stock").length > 0) {
                    $(".wcst_low_stock").html('');

                    $.each(wcst_data.settings.low_stock, function (triggerKey, triggerData) {
                        var stock_status_html = stock_status_display(triggerData, stockQty, variation_is_in_stock, variation);
                        if (variation_is_in_stock === true) {
                            $(".wcst_low_stock.wcst_low_stock_key_" + wcst_data.current_postid + "_" + triggerKey).removeClass("wcst_out_of_stock");
                        } else {
                            $(".wcst_low_stock.wcst_low_stock_key_" + wcst_data.current_postid + "_" + triggerKey).addClass("wcst_out_of_stock");
                        }
                        $(".wcst_low_stock.wcst_low_stock_key_" + wcst_data.current_postid + "_" + triggerKey).html(stock_status_html);
                    });
                }
            }

            // check for you save
            if (typeof wcst_data.settings.savings !== 'undefined' && wcst_data.compatibility.savings.indexOf(wcst_data.product_type) !== -1 && Object.keys(wcst_data.settings.savings).length > 0 && variation_is_on_sale === 1 && variation_is_in_stock === true) {
                $.each(wcst_data.settings.savings, function (triggerKey, triggerData) {
                    you_save_display(triggerKey, triggerData, variation);
                });
            }

            // check for deal expiry
            if (typeof wcst_data.settings.deal_expiry !== 'undefined' && wcst_data.compatibility.deal_expiry.indexOf(wcst_data.product_type) !== -1 && Object.keys(wcst_data.settings.deal_expiry).length > 0 && variation_is_on_sale === 1 && variation_is_in_stock === true) {
                if (variation_data.to !== '' && variation_data.to > 0) {
                    if (wcst_data.utc0_time < variation_data.to) {
                        if (variation_data.from !== '' && variation_data.from > 0) {
                            if (wcst_data.utc0_time > variation_data.from) {
                                $.each(wcst_data.settings.deal_expiry, function (triggerKey, triggerData) {
                                    deal_expiry_display(triggerKey, triggerData, variation_data, variation);
                                });
                            } else {
                            }
                        } else {
                        }
                    }
                }
            }
        }
    });

    /**
     * handling cart refreshments
     */
    $(document.body).on('wc_fragments_refreshed', function () {
        deal_expiry_timer_init();
    });
    $(window).bind('load', function () {

        if ($(".wcst_deal_expiry,.wcst_manual_timer").length > 0) {
            $.ajax({
                url: wcst_data.wcstajax_url,
                type: "POST",
                data: {
                    'wcst_action': 'wcst_reset_current_time',
                    'location': document.location.href,
                    'product': wcst_data.current_postid,
                },
                success: function (result) {
                    if (result.hasOwnProperty('utc0_time') === true) {
                        wcst_data.utc0_time = result.utc0_time;
                        reset_all_timer_data();
                    }
                }
            });
        }
    });

    function reset_all_timer_data() {
        if (typeof wcst_data.settings !== "undefined" && typeof wcst_data.settings.deal_expiry !== "undefined") {
            for (var key in wcst_data.settings.deal_expiry) {
                var class_key = ".wcst_deal_expiry_key_" + wcst_data.current_postid + "_" + key;
                if ($(class_key).length === 0) {
                    continue;
                }
                var triggerData = wcst_data.settings.deal_expiry[key];
                var date = $(class_key).find("span").attr("data-date");
                var variation_date = {"to": date};
                var sale_end_diff = variation_date.to - wcst_data.utc0_time;
                var display_html, display_label, merge_tag, timer_class, data_display, pattern, re = '';

                if ($(class_key).hasClass("wcst_deal_expiry_variable")) {
                    continue;
                }
                if ($(class_key).hasClass("wcst_timer")) {
                    continue;
                }
                if (triggerData.display_mode == 'reverse_timer') {
                    continue;
                }
                if (triggerData.display_mode === 'reverse_date') {
                    display_html = humanized_time_span(timestamp_converter(variation_date.to), timestamp_converter(wcst_data.utc0_time));
                    display_label = triggerData.reverse_date_label;
                    merge_tag = '{{time_left}}';
                }

                // checking switch mode
                if (triggerData.switch_period > 0) {
                    if (sale_end_diff < (triggerData.switch_period * 3600)) {
                        display_html = "";
                        display_label = triggerData.reverse_timer_label;
                        merge_tag = '{{countdown_timer}}';
                        timer_class = ' wcst_timer';
                        data_display = 'days';
                        if (sale_end_diff < 86400) {
                            data_display = 'hrs';
                        }
                    } else {
                        continue;
                    }
                }
                display_html = '<span data-diff="' + (variation_date.to - wcst_data.utc0_time) + '" data-display="' + data_display + '">' + display_html + '</span>';
                if (timer_class == ' wcst_timer') {

                    $(".wcst_deal_expiry.wcst_deal_expiry_key_" + wcst_data.current_postid + "_" + key).addClass("wcst_timer");
                }
                pattern = merge_tag;
                re = new RegExp(pattern, "g");
                display_label = display_label.replace(re, display_html);

                $(".wcst_deal_expiry.wcst_deal_expiry_key_" + wcst_data.current_postid + "_" + key).html(display_label);
            }
        }

        $(".wcst_timer,.wcst_manual_timer").each(function () {
            $.ajax({
                url: wcst_data.ajax_url,
                type: "POST",
                data: {
                    'action': 'wcst_refreshed_times',
                    'location': document.location.href,
                    'endDate': $(this).children('span').attr('data-date'),
                },
                beforeSend: function () {
                    wcstRefresh_timers_count++;
                },
                success: function (result) {
                    wcstCurrent_received_timers++;
                    $(".wcst_timer,.wcst_manual_timer").find("span[data-date='" + result.old_diff + "']").each(function () {
                        jQuery(this).attr("data-diff", result.diff);
                    });
                    if (wcstRefresh_timers_count === wcstCurrent_received_timers) {
                        deal_expiry_timer_init();
                        init_manual_timers();
                    }
                }
            });
        });
    }

    function init_manual_timers() {
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
    }

    function deal_expiry_timer_init() {
        if ($(".wcst_timer").length > 0) {
            hardTxt = wcst_data.hard_texts;
            var wcst_day = 'D', wcst_days = 'D';
            var wcst_hour = 'h', wcst_hours = 'h';
            var wcst_mins = 'm', wcst_min = 'm';
            var wcst_sec = 's', wcst_secs = 's';

            if (typeof hardTxt.day !== 'undefined' && hardTxt.day != '') {
                wcst_day = hardTxt.day;
            }
            if (typeof hardTxt.days !== 'undefined' && hardTxt.days != '') {
                wcst_days = hardTxt.days;
            }
            if (typeof hardTxt.hr !== 'undefined' && hardTxt.hr != '') {
                wcst_hour = hardTxt.hr;
            }
            if (typeof hardTxt.hrs !== 'undefined' && hardTxt.hrs != '') {
                wcst_hours = hardTxt.hrs;
            }
            if (typeof hardTxt.mins !== 'undefined' && hardTxt.mins != '') {
                wcst_mins = hardTxt.mins;
            }
            if (typeof hardTxt.min !== 'undefined' && hardTxt.min != '') {
                wcst_min = hardTxt.min;
            }
            if (typeof hardTxt.sec !== 'undefined' && hardTxt.sec != '') {
                wcst_sec = hardTxt.sec;
            }
            if (typeof hardTxt.secs !== 'undefined' && hardTxt.secs != '') {
                wcst_secs = hardTxt.secs;
            }
            $(".wcst_timer").each(function () {
                var $this = $(this);
                var childSpan = $this.children("span").not(".amount");
                var diffTimestamp = parseInt(childSpan.attr("data-diff"));
                var modifiedDate = new Date().getTime() + diffTimestamp * 1000;

                var displayFormat;
                var displayVal = childSpan.attr("data-display");

                childSpan.wcstCountdown(modifiedDate, function (event) {

                    var dayDirective = (event.offset.totalDays > 1) ? wcst_days : wcst_day;
                    var HourDirective = (event.offset.hours > 1) ? wcst_hours : wcst_hour;
                    var MinDirective = (event.offset.minutes > 1) ? wcst_mins : wcst_min;
                    var SecDirective = (event.offset.seconds > 1) ? wcst_secs : wcst_sec;

                    displayFormat = '%H' + HourDirective + ' %M' + MinDirective + ' %S' + SecDirective;
                    if (displayVal == 'days') {
                        displayFormat = '%-D ' + dayDirective + ' %H ' + HourDirective + ' %M ' + MinDirective + ' %S ' + SecDirective;
                    }
                    $(this).text(event.strftime(displayFormat));
                });
            });
        }
    }

    function stock_status_display(low_stock_settings, stockQty, variation_is_in_stock, variation) {
        var pattern, re;
        var scarcity_text = low_stock_settings.scarcity_label;
        var assurance_text = low_stock_settings.assurance_label;
        var out_of_stock_text = low_stock_settings.out_of_stock_label;
        var qtytext = (stockQty !== null) ? variation.wcst_stock_qty : "";

        pattern = "{{stock_quantity_left}}";
        re = new RegExp(pattern, "g");

        scarcity_text = scarcity_text.replace(re, qtytext);

        pattern = "{{stock_quantity_left}}";
        re = new RegExp(pattern, "g");
        assurance_text = assurance_text.replace(re, qtytext);

        var wcst_stock_status_html = '';

        if (variation_is_in_stock === true) {

            if (variation.wcst_manage_stock === false) {
                wcst_stock_status_html = '<div class="wcst_low_stock_assurance"><span>' + assurance_text + '</span></div>';
            } else {
                if ((stockQty === 0 || stockQty === null || stockQty === "") && (false === variation.backorders_allowed)) {
                    wcst_stock_status_html = '<div class="wcst_low_stock_assurance"><span>' + assurance_text + '</span></div>';
                } else if ('0' == variation.wcst_stock_qty && (true === variation.backorders_allowed)) {
                    wcst_stock_status_html = '<div class="wcst_low_stock_assurance"><span>' + assurance_text + '</span></div>';
                } else {
                    if (low_stock_settings.default_mode == "scarcity") {
                        wcst_stock_status_html = '<div class="wcst_low_stock_scarcity"><span>' + scarcity_text + '</span></div>';
                    } else if (low_stock_settings.switch_scarcity_min_stock > 0 && low_stock_settings.switch_scarcity_min_stock >= variation.wcst_stock_qty) {
                        wcst_stock_status_html = '<div class="wcst_low_stock_scarcity"><span>' + scarcity_text + '</span></div>';
                    } else {
                        wcst_stock_status_html = '<div class="wcst_low_stock_assurance"><span>' + assurance_text + '</span></div>';
                    }
                }
            }
        } else {
            wcst_stock_status_html = '<div class="wcst_low_stock_out"><span>' + out_of_stock_text + '</span></div>';
        }
        return wcst_stock_status_html;
    }

    function you_save_display(triggerKey, triggerData, variation) {
        var pattern, re;

        if (triggerData.show_below_variation_price === 'yes') {

            var cp = variation.display_regular_price;
            var sp = variation.display_price;
            var variation_label = triggerData.label;
            var variation_diff = Math.abs(cp - sp);

            if (variation_diff > 0 && $('.woocommerce-variation.single_variation').length > 0 && $('.woocommerce-variation.single_variation .woocommerce-variation-price').length > 0) {
                var variation_percentage = (variation_diff / cp) * 100;
                var decimal_point = wcst_data.wc_decimal_count ? wcst_data.wc_decimal_count : 1;
                variation_percentage = variation_percentage.toFixed(decimal_point);

                if ((variation_percentage % 1 === 0) || triggerData.hide_decimal_in_saving_percentage === 'yes') {
                    variation_percentage = Math.floor(variation_percentage);
                }

                pattern = "{{savings_value}}";
                re = new RegExp(pattern, "g");

                variation_label = variation_label.replace(re, '<span class="you_save_value">' + wcst_wc_price(variation_diff) + '</span>');
                pattern = "{{savings_percentage}}";
                re = new RegExp(pattern, "g");

                variation_label = variation_label.replace(re, '<span class="you_save_percentage">' + variation_percentage + '%</span>');
                pattern = "{{savings_value_percentage}}";
                re = new RegExp(pattern, "g");

                variation_label = variation_label.replace(re, '<span class="you_save_value_percentage">' + wcst_wc_price(variation_diff) + ' (' + variation_percentage + '%)</span>');
                pattern = "{{regular_price}}";
                re = new RegExp(pattern, "g");

                var regPrice = '<span class="woocommerce-Price-amount amount">' + wcst_wc_price(cp) + '</span>';
                variation_label = variation_label.replace(re, regPrice);

                var salePrice = '<span class="woocommerce-Price-amount amount">' + wcst_wc_price(sp) + '</span>';

                pattern = "{{sale_price}}";
                re = new RegExp(pattern, "g");
                variation_label = variation_label.replace(re, salePrice);

                $('.woocommerce-variation.single_variation').find('.wcst_savings_variation').remove();
                $('.woocommerce-variation.single_variation .woocommerce-variation-price').after('<div class="wcst_savings_variation wcst_savings_variation_key_' + wcst_data.current_postid + '_' + triggerKey + '">' + variation_label + '</div>');
            }
        }
    }

    /**
     * Alias of wc_price in javascript
     * Format given price amount with native woocommerce curreny settings
     * @param price Price to format
     * @global wcst_data['currency_pos']
     * @global wcst_data['currency']
     * @global wcst_data['wc_decimal_count']
     * @global wcst_data['wc_decimal_sep']
     * @global wcst_data['wc_thousand_sep']
     * @returns string priceHTML
     */
    function wcst_wc_price(price) {
        var priceHtml;
        if (wcst_data.currency_pos === 'left') {
            priceHtml = '<span class="woocommerce-Price-currencySymbol">' + wcst_data.currency + '</span>' + wcst_number_format(price, wcst_data.wc_decimal_count, wcst_data.wc_decimal_sep, wcst_data.wc_thousand_sep);

        } else if (wcst_data.currency_pos === 'left_space') {
            priceHtml = '<span class="woocommerce-Price-currencySymbol">' + wcst_data.currency + '</span> ' + wcst_number_format(price, wcst_data.wc_decimal_count, wcst_data.wc_decimal_sep, wcst_data.wc_thousand_sep);
        } else if (wcst_data.currency_pos === 'right') {
            priceHtml = wcst_number_format(price, wcst_data.wc_decimal_count, wcst_data.wc_decimal_sep, wcst_data.wc_thousand_sep) + '<span class="woocommerce-Price-currencySymbol">' + wcst_data.currency + '</span> ';
        } else if (wcst_data.currency_pos === 'right_space') {
            priceHtml = wcst_number_format(price, wcst_data.wc_decimal_count, wcst_data.wc_decimal_sep, wcst_data.wc_thousand_sep) + ' <span class="woocommerce-Price-currencySymbol">' + wcst_data.currency + '</span> ';
        }
        return priceHtml;
    }

    function wcst_number_format(number, decimals, dec_point, thousands_sep) {

        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

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

    function deal_expiry_display(triggerKey, triggerData, variation_date, variation) {
        var timer_class = '', data_display = '', display_html, display_label, merge_tag, sale_end_diff = 0;
        sale_end_diff = variation_date.to - wcst_data.utc0_time;
        var cp = variation.display_regular_price;
        var sp = variation.display_price;
        var pattern, re;
        var regPrice, salePrice;
        if (triggerData.display_mode === 'reverse_date' || triggerData.display_mode === 'expiry_date') {
            if (triggerData.display_mode === 'reverse_date') {
                display_html = humanized_time_span(timestamp_converter(variation_date.to), timestamp_converter(wcst_data.utc0_time));
                display_label = triggerData.reverse_date_label;
                merge_tag = '{{time_left}}';
            }
            if (triggerData.display_mode == 'expiry_date') {
                display_html = variation.wcst_sale_to_val;
                display_label = triggerData.expiry_date_label;
                merge_tag = '{{end_date}}';
            }

            // checking switch mode
            if (triggerData.switch_period > 0) {
                if (sale_end_diff < (triggerData.switch_period * 3600)) {
                    display_label = triggerData.reverse_timer_label;
                    merge_tag = '{{countdown_timer}}';
                    timer_class = ' wcst_timer';
                    data_display = 'days';
                    if (sale_end_diff < 86400) {
                        data_display = 'hrs';
                    }
                }
            }

            display_html = '<span data-diff="' + (variation_date.to - wcst_data.utc0_time) + '" data-display="' + data_display + '">' + display_html + '</span>';

            if (timer_class == ' wcst_timer') {
//                display_html += ' hours';
                $(".wcst_deal_expiry.wcst_deal_expiry_key_" + wcst_data.current_postid + "_" + triggerKey).not(".wcst_static").addClass("wcst_timer");
            } else {
                $(".wcst_deal_expiry.wcst_deal_expiry_key_" + wcst_data.current_postid + "_" + triggerKey).not(".wcst_static").removeClass("wcst_timer");
            }

            pattern = merge_tag;
            re = new RegExp(pattern, "g");
            display_label = display_label.replace(re, display_html);

            pattern = "{{regular_price}}";
            re = new RegExp(pattern, "g");

            regPrice = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' + wcst_data.currency + '</span>' + cp.toFixed(2) + '</span>';
            display_label = display_label.replace(re, regPrice);

            salePrice = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' + wcst_data.currency + '</span>' + sp.toFixed(2) + '</span>';

            pattern = "{{sale_price}}";
            re = new RegExp(pattern, "g");
            display_label = display_label.replace(re, salePrice);

            $(".wcst_deal_expiry.wcst_deal_expiry_key_" + wcst_data.current_postid + "_" + triggerKey).not(".wcst_static").html(display_label);
            deal_expiry_timer_init();
        } else if (triggerData.display_mode === 'reverse_timer') {
            display_label = triggerData.reverse_timer_label;
            merge_tag = '{{countdown_timer}}';
            timer_class = ' wcst_timer';
            data_display = 'days';
            if (sale_end_diff < 86400) {
                data_display = 'hrs';
            }
            display_html = '<span data-diff="' + (variation_date.to - wcst_data.utc0_time) + '" data-display="' + data_display + '">' + display_html + '</span>';
            if (timer_class == ' wcst_timer') {
                $(".wcst_deal_expiry").addClass("wcst_timer");
            }

            pattern = merge_tag;
            re = new RegExp(pattern, "g");
            display_label = display_label.replace(re, display_html);

            pattern = "{{regular_price}}";
            re = new RegExp(pattern, "g");

            regPrice = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' + wcst_data.currency + '</span>' + cp.toFixed(2) + '</span>';
            display_label = display_label.replace(re, regPrice);


            salePrice = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">' + wcst_data.currency + '</span>' + sp.toFixed(2) + '</span>';

            pattern = "{{sale_price}}";
            re = new RegExp(pattern, "g");
            display_label = display_label.replace(re, salePrice);


            $(".wcst_deal_expiry.wcst_deal_expiry_key_" + wcst_data.current_postid + "_" + triggerKey).not(".wcst_static").html(display_label);
            deal_expiry_timer_init();
        }
    }

})(jQuery);
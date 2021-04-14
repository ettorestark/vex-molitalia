=== XL WooCommerce Sales Triggers ===
Contributors: XLPlugins
Tags: WooCommerce, eCommerce Sales Booster, Sales Triggers, XLPlugins
Tested up to: 4.9.6
Stable tag: 2.6.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
A suite of conversion-boosting solutions (a.k.a 'Triggers' ) that get you more sales from your existing product pages.

== Installation ==
Follow the below steps to install the plugin.
1. Upload the plugin files to the '/wp-content/plugins/xl-woocommerce-sales-triggers' directory.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to WooCommerce > XL Sales Triggers to fill the settings

== Changelog ==

== 2.6.0 ==

* Added: A new trigger 'Static badge' introduced.
* Added: Compatible with Yoast plugin primary category for Best Seller Badge & Static Badge
* Added: Sales Snippet trigger - Added an option for output display.
* Added: Savings trigger - New option, Hide decimals in saving percentage.
* Added: Trigger duplicate feature.
* Added: Global setting for Order status selection for Sales snippet & count triggers.
* Fixed: Removed all occurrences of 'wp_cache_flush' and managed caching proper.


== 2.5.1 ==

* Added: 'data-trigger-id' attribute added to each trigger parent div element.
* Added: Force plugin transient removal and optin reset options added in xlplugins -> tools
* Added: Admin notification when plugin update is available.


== 2.5.0 ==

* Added: WPML support for Sales trigger Transients.
* Added: New rule 'Product Tags'.
* Added: New field 'Heading Text Color' in Guarantee Trigger.
* Added: New field 'Heading Color' in Guarantee Trigger.
* Added: Logging errors in case site faced any PHP Error.
* Improved: Savings trigger HTML to consider WooCommerce native decimal separator settings while showing savings percentage.
* Fixed: Transients were not getting cleared on deletion of a trigger.


== 2.4.1 ==

* Fixed: Dynamic CSS for all the triggers were not set up, resulting in default settings for color and font to apply.


== 2.4.0 ==

* Fixed: When multiple triggers of the same type then priorities are not working, fixed now.
* Improved: is_array like conditions added to handle warnings in PHP7.
* Added: meta no-index for WCCT Campaign post type for search engines.
* Improved: Sales Triggers rules parsing performance improved.
* Improved: WordPress File System API used to store Sales Triggers query in files.
* Added: 'On Backorder' stock status added in rules.
* Added: Flushing object cache when Sales Triggers modified.
* Added: Compatible with WooCommerce 3.3, 3.3.1, 3.3.2 and 3.3.3
* Added: Compatible with Astra theme.
* Fixed: Porto theme modified their price calling code, Finale compatibility updated.


== 2.3.0 ==

* Added: Compatible with Tucson & Techmarket theme
* Improved: Latest CMB2 version 2.3.0 added
* Added: PHP 7.2 compatible
* Fixed: Savings trigger: For variations, savings data appended just below variation price. Duplication fixed for Avada case.
* Fixed: Savings trigger: 'Hide top variation price' default value is set to 'yes'


== 2.2.8 ==

* Added: Compatibility with yith product bundles addon
* Added: Compatible with WordPress 4.9 version
* Added: Compatible with Boxshop theme
* Added: Compatible with WooCommerce 3.2.5 version
* Improved: XL core updated to 3.4
* Fixed: Guarantee trigger countdown timer merge tag was not ticking from the last update.
* Fixed: Shopkeeper theme compatibility code modified for latest version.
* Added: Tools Export functionality added for quick debugging.
* Added: Transients clear feature added on Settings page.


== 2.2.7 ==

* Added: Themes (Oceanwp, Basel, Enfold, Porto, Revo, Aurum, Savoy, Sober, TheGem) support added for single product page positions.
* Improved: Some themes force font family on i tag, so guarantee trigger icons disappeared, now fixed.
* Improved: Reduce number of requests on frontend. Minified and Combined public css and js files.
* Fixed: Deal expiry trigger has a time mismatch conflict, now fixed.
* Fixed: Low Stock trigger has issue in variable product when backorder is false, now corrected.


== 2.2.6 ==

* Fixed: Issue with the timings: Guarantee trigger merge tags and rules for date|time was not working fine as they consider UTC 0 timings to decode merge tags, used wordpress blog's time instead.
* Improvement: Low stock trigger to work with variations that have backorder enabled, pushing assurance mode to work on this scenario.
* Added : Compatibility with WooCommerce Product Bundles Add-On for all the triggers.


== 2.2.5 ==

* Fixed: 'From' text wasn't translatable globally.
* Added: Guarantee trigger box alignment field added. Options: left, right & center.
* Improved: Custom Ajax fired to get current server UTC0 time for better performance.
* Improved: Ajax to get current UTC time placed over window after load to not include it in waterfall.
* Improved: Do not fire reset UTC time request on pages where we do not have any deal expiry triggers.


== 2.2.4 ==

* Fixed: WordPress native transient function replaced with custom transient function. Issue occurred with cache plugins.


== 2.2.3 ==

* Fixed: admin-ajax url calling from woocommerce_params variable. sometimes that variable didn't initialize, now corrected.


== 2.2.2 ==

* Added: Not now and No thanks option in Notices.
* Modified: Sidebar links modified.
* Modified: Plugin updation array key length issue fixed.


== 2.2.1 ==

* Fixed: JS Error, Caused by the last update 2.2, handling for "setting" key in localized data was missing
* Improved: CMB2 Tabs localized completely for wcst, it will prevent conflict.


== 2.2 ==

* Improved: Deal Expiry trigger output & Date Cutoff time left merge tag's output will now get refreshed on the client side, using ajax. This will prevent caching issues with deal expiry
* Fixed: Issue with caching add-ons while using get_transients, returning previously saved data regardless of timeout. Logic replaced by removing all the transeints for the item just after order completed.


== 2.1.5 ==
* Added: WPML Compatibility with global settings
* Fixed: "Chosen" JS conflict on single campaign edit page

== 2.1.4 ==
* Added: Compatibility with following themes: Betheme, Eva, Merchandiser & Oxygen.
* Added: Sorting functionality on Guarantee trigger to sort guarantees.
* Improved: JS and Css files minified to avoid conflicts with caching plugins.
* Added: Dismissible notifications for the admin panel.
* Fixed: Updater class modified to sustain request result with better caching.
* Improved: Added option in savings to hide top savings range for variations.

== 2.1.3 ==

* Fixed: Deal expiry trigger timer, Merge tag timer for admin-end and front-end handling with local user browser time.
* Fixed: Removed default settings updation code on plugin activation.
* Fixed: Guarantee trigger: handled line breaks.
* Added: Wowmall theme support added.
* Added: Filter hook to modify woocommerce order states for Best Seller trigger.

== 2.1.2 ==

* Fixed: Price Formatting issue in savings trigger's variation HTML

== 2.1.1 ==

* Added: Filter hook to modify WooCommerce order states for Sales Activity both triggers
* Fixed: Savings trigger for variation, handling in a case when difference is 0
* Added: New Filter `wcst_get_wc_states` to modify order states to check in sales activity triggers. 
* Fixed: Usage of php date_default_timezone_set completely replaced with get_time_time. 
* Fixed: Savings Trigger: Fix to variation when price is zero. 
* Fixed: Savings trigger support with Finale, to show well discounted range applied by finale. 
* Added: New Setting area as global settings to replace all the hard text used in plugin for front end. 
* Fixed: Handling and support for Caching plugins, hard flush cache on saving of the post. 
* Added: Compatibility with Famous WooCommerce themes like: Flatsome, Electro, Hcode, Denso, Accessories Shop, XStore, Claue, THE-7, Uncode, ShopKeeper & Float. 
* Added: New Rule for Product Stock added.
* Fixed: Low Stock trigger when back-order is allowed for the product will show "In Stock" from now on.

== 2.1.0 ==

* Fixed: Admin Tab CSS conflict with divi theme
* Update: XL core updated to the recent version.

== 2.0.9 ==

* Fixed: Critical Bug: Class 'WCST_Product' not found, throwing php error and breaking the sites.

== 2.0.8 ==

* Fixed: Resolved Issue with activation, handling when activation failed previously
* Added: Filter Hooks at inside trigger modals to modify data before and after data extraction.
* Added: filtered sales activities hooks before data extraction to show static text when no merge tag given in the content. using filters.
* Fixed: Modified WCST_Product classname to `XL_WCST_Product` to save it from conflict with plugin https://codecanyon.net/item/woocommerce-shipping-tracking/11363158
* Fixed: ShortCode callback throwing fatal error on false product id, fixed it by proper algo to find which product to load and handling.
* Added: New shortcode attribute `skip_rules` (default: no) , to skip rules execution if you want to print them right away and avoild rules matching.
* Added: Filter `wcst_modify_rules` to apply_custom_rules in runtime.
* Fixed: CSS Fix for the best seller badge , misalignment issue.
* Fixed: XL Core updated to 2.0.0

==  2.0.7 ==

* Fixed: Added 3rd param in all update_options with value 'false'.
* Fixed: Handling in js in case no trigger is active.
* Fixed: date replaced with date_i18n for Deal expiry trigger.

==  2.0.6 ==

* Fixed: CMB2 conditional script enqueueing issue: Condition modified for the site pages.
* Added: Plugin update transients removal query arg added.
* Fixed: Guarantee-trigger-help-popup content was printing over each page on the backend, creating issues with Js builders powered by angular due to curly phrases.
* Added: New Filter inside best seller badge added to show hard text and by-pass PHP and database operations.
* Added: best seller List: Condition to prevent rendering when there exist no supported merge tags.
* Fixed: Removed `date_default_timezone_set` from the entire plugin and used 'dateTime object' against it, it prevents bugs that could have been occurred because we were setting timezone of the server.
* Added: Meta `generator` tag added
* Added: Added wcst version and WC version inside localized data to power better debugging.
* Fixed: Guarantee trigger on post meta CMB2 fields as well.
* Fixed: shortcode support added for out of stock text.
* Fixed: Review trigger, PHP closing tag was left in CSS rendering function, making browsers unable to apply CSS.
* Fixed: Meta query for the shortcode function modified to not pass the query for show_on param, making it possible to work when all  three locations are turned off for the trigger still shortcode executes without "trigger_ids" arg in the shortcode.

==  2.0.5 ==

* Fixed: CMB2 conditional script wasn't yet fully compatible with the tabs JS, Needed lcalizations.
* Fixed: wc_timezone_string function get replaced by the code from wc2.6.14.
* Fixed: transients ttl conflict , preventing sales activities to reset transients on thank you order event
* Fixed: Better conditions when loading assets for the admin layer.
* Fixed: Low stock Js , compatibility with WC3.0
* Fixed: Shortcode in variations, handled when wrong variation ID
* Fixed: Shortcode in products, handled when wrong product ID
* Fixed: Improvements in Guarantee help box

==  2.0.4 ==

* Remooved: session handling and header handling
* Renamed core file
* Fixed: Rule builder js issue , slug conflict

* Fixed: Removed XL promotions inclusions for the current verison
* Fixed: WooCommerce3.0 Compatibility "id" access replaced with get_id();
* Fixed: WooCommerce3.0 Compatibility "get_child" replaced with WCST_Product::get_instance($variation_ID)
* Fixed: WooCommerce3.0 Compatibility "woocommerce_stock_html" hook start having 2 args.
* Fixed: WooCommerce3.0 Compatibility "product_type" replaced with get_type()

* Fixed: Issue in rule builder UI , blocking selection for the rule type until stacked ajax call response comes back.
* Added: Xl-Promotions Added in Xl Core.

==  2.0.3 ==

* Added: WPML Support for WCST Triggers: Only selected Language Dependent trigger will appear instead of all languages  on a single product

* Fixed: Days left issue in guarantee cut_off_time merge tags.
* Fixed: email dynamic in support forms. 
* Fixed: XL core updated with the changes like new filters and corrections.
* Added: New Guarantee merge tag {{today}}
* Added: New Guarantee merge tag attribute for current_date and current_day
* Added: Guaratee trigger modifications added in HTML for better examples.
* Fixed: Switch field For CMB2 was not rendering on product-edit

* Fixed: CMB2 conditionals and CMB2 switch handling and support with wcst plugin.
* Added: Better UI rules meta boxes.
* Removed Session code : Placed to handle one view on the same page.
* Fixed: Stripped Whitespace with html classes on rendering


== 2.0.2 == 

* Fixed: guarantee help text pop up css issue.
* Fixed: best seller badge text styling issue.
* Fixed: End Date Label fixed.
* Fixed: CSS for cart page: Corrected  best seller category alignment issue On Xstore, Savoy and Basel
* Fixed: CSS: Styling of cart page and Label styling of support form
Before html changes
* Fixed: JS issue in conditional fields
* Fixed: Default format for time_left_countdown merge tags
* Fixed: Flatsome theme and reviews html . Fixed css for flatsome.
* Fixed: {{regular_price}} && {{sale_price}} merge tag to be printed with span for variations as well.
* on trigger deactivation hold rules meta data
* rule builder wasn't working, fixing calling
* Added: ThickBox help for the guarantee trigger new merge tags
* Fixed: hook for count slug not working
* Added: New hook to choose what html pattern for best sellers list.
* Added: New optional DIVs structure for Best sellers List
* Fixed: Undefined variable on js for hard text conversion.
* Added : apply_filter to modify ttl for transients for deal_expiry
* Added : page on classes to write css for specific call
* Fixed: Xl-support class file moved from admin to includes to be called and accessed publicly.
* Fixed: Best seller list  hyperlink category is not working over grid and cart pages.
* Added: Single product position to be printed with visibility column in listing in admin.
* Fixed: Sale snippet transient condition was misplaced.
* Fixed: Removed native woocommerce class in smarter reviews and added custom cass to the hyperlink instead, written a custom click handler for that class.
* Fixed: Supprt Form: Site URL field was disabled hence was not getting send after form submission, made it readonly.


==  2.0.1 == 

* Added : Language Translations added w.r.t v1.1.
* Fixed : Conditional content methods and variable name changes.
* Fixed : Better XL core start file, Fixed some db entries
* Fixed : Position select has some deprecated options.
* Added : Global Data tracking now combine trigger data with the basic info.
* Optimized: match_group for rules not received post-ID as argument rather than post object
* Fixed: get_product_sales query modified to get todays sales also.
* Added: Extended Options for date range picker dropdown.
* Fixed: bets seller list - exclude_cats handling for no overridden case.
* Fixed: Data gather functions now optimized to be work on post-ID rather than post object.
* Fixed : Deal Expiry Timer :Was Showing php date and after load get changed to the date calculated by JS.
* Fixed: Deal Expiry for variations now check if product is in stock.
* Added : Guarantee Trigger Color and Fonts css fixes.
* Fixed : Low Stock to always show assurance when manage stock is off.
* Fixed : Low stock is now compatible with 'grouped' product.
* Added/ Fixed: Timer class added for guarantee merge tags. CSS FIX
* Removed: Caching support for sold_item_count query for sales count.
* Fixed : Sale Snippet CSS text color and font size issue.
* Fixed : Savings to not work when the product is out of stock.
* Fixed: XL support file notification for license now iterate over all intstalled plugins and check if its pushed a notification.
* Fixed: CMB2 conditionals JS to be localized for each plugin and metabox of its own and initiated by the plugin js file but not the core JS.
* Fixed: Low stock JS, when stock quanity is null, handled to show blank when decoding merge tag.
* Fixed: Sales activities query No.2  have issue, using custom dates but not the date processed by date from - to logic.
* Removed: Drag-Drop functionality from Trigger listings, creating issues with the UX. 
* Added: New logging pattern added to trigger classes.
* Added: Way to force debug transient and debugging using query params with keys like `wcst_force_debug=yes` & `wcst_force_transients_remove=yes`
* Fixed: Styling issue in guarantee merge tags. 
* Added: JS support for merge tag `regular_price` AND `sale_price`
* Added: Variation product "savings" support for merge tag `regular_price` AND `sale_price`
* Fixed: Timestamp issue in time and date rule builder.
* Added: Save post hook to reset sales list transients
* Fixed: warnings were showing in smarter reviews modal function.
* Fixed: updated calling for `update_option` function to manage autoloading
* Optimise Queries and set transients for one hour for sales and best seller badge. 
* Guarantee merge tag support limited to guarantee trigger only for now. Returning blank for other triggers. 
* Fixed: low stock: out of stock not showing for variations
* Fixed: Low stock out of stock css not coming.
* Fixed: guarantee filter hook to handle 'none' and '' both. Constant conflict was happening.
* Fixed: wcst logging function datetimezone set on separate date object
* Added: Cron Job for Weekly license checks 
* Fixed: Guarantee trigger to work when either text OR heading is given.
* Added: Added support for booking addon for WooCommerce. 
* Fixed: Product price rule logic modified to work with variations and grouped product as well. 
* Fixed: Custom request for each trigger handled by abstract class. 
* Added: Options in trigger instances to show it on grid , cart and product.
* Added: Customizable shortcodes to show trigger anywhere you want. 
* Added Cart_item_key atribute to the cart shortcode for more accurate results for saving trigger. 
* Added: New metabox to add menu order to up and down the order for tge trigger instance. 
* Added: Drag and Drop table rows to reorder them and show them on front in that order. 
* Optimised: Cached custom and WP_Query using `wp_cache_set` for better performace. 
* Added: New rule_type: [date,day and time] in rule builder. 
* Fixed: rule_type product price now supports product variations. 
* Added: New merge tags for date and time to show date and timings in your way using format and modfier for guarantees.

== 2.0.0 ==
* Added: New merge tags for time left that supports reverse countdown as well for guarantee. 
* Added: New merge tags for Sale price and regular price added to work for deal expiry.    
* Added: Options in trigger instances to show it on grid.
* Added: Options in trigger instances to show it on cart table.
* Added: Options in trigger instances to show it on product pages with chosen location.
* Added: Customizable shortcodes to show trigger anywhere you want. Some Eg are :

		  [wcst_savings]
		  [wcst_deal_expiry location='product']
		  [wcst_deal_expiry trigger_ids='2270']
		  [wcst_deal_expiry location="cart"]
		  [wcst_deal_expiry trigger_ids='2233' product_id='482' variation_id = '2072']
* Added Cart_item_key atribute to the cart shortcode for more accurate results for saving trigger. For Eg: 
		  [wcst_savings product_id='482' location='cart' variation_id = '2072' cart_item_key='accc7dfdf98067254fc091db3d8ec17f']
* Added: New metabox to add menu order to up and down the order for tge trigger instance. 
* Added: Drag and Drop table rows to reorder them and show them on front in that order. 
* Optimised: Cached custom and WP_Query using `wp_cache_set` for better performace. 
* Added: New rule_type: [date,day and time] in rule builder. For Eg:
		- Show this trigger if day is Friday 
		- Show this trigger if date is greater than 2017-02-20 && Show this trigger if date is less than or equal to 2017-02-28
* Fixed: rule_type product price now supports product variations. 
* Added: New merge tags for date and time to show date and timings in your way using format and modfier for guarantees. 
			here are the possible merge tag syntax: 

			- {{current_date}} // will pick format fron your wp settings
			- {{current_date format="l, F j" }} // will show something like Friday, March 3  
			- {{current_date format="l, F j" adjustment="+10 Days"}} // will show something like Friday, March 13 

			- {{current_time}}
            - {{curent_time format="H:i"}} //any format that supports https://codex.wordpress.org/Formatting_Date_and_Time 
            - {{current_time adjustment="+2 hours"}} 
            - {{current_time format="g:i a" adjustment="30 minutes"}} //outputs as 12:45 pm

            - {{current_day}} // //outputs as Tuesday
            - {{current_day adjustment="+2 days"}}  //outputs as Thursday

            - {{cutoff_time_left cutoff="2017-08-15" timer="off"}} //HAS TO MAINTAIN THIS FORMAT yyyy-mm-dd
            - {{cutoff_time_left cutoff="02:00pm" timer="off"}}  HAS TO MAINTAIN THIS FORMAT hh:mm
            - {{cutoff_time_left cutoff="2017-08-15 07:00pm" timer="off"}}
            - {{cutoff_time_left cutoff="2017-08-15 07:00pm" format="%h hrs and %I minutes" timer="off"}}
            - {{cutoff_time_left cutoff="2017-08-15 07:00pm" format="%h hrs and %I minutes" timer="on"}}
          

* Added: New merge tags for Sale price and regular price added to work for deal expiry and savings. 
			- {{regular_price}}
			- {{sale_price}}



= 1.3 =
* Added: Did handling for WooCommerce new version 3.0.0

= 1.2 =
* Fixed: Out of stock condition fixed for variable product.
* Added: New hook added to filter best seller category.

= 1.1 =
* Fixed: PHP warnings on thank you page.

= 1.0.9 =
* Fixed: Countdown Timer Jquery Plugin conflict with some themes.
* Fixed: no-cache header getting send because of session_start.
* Fixed: Double slash coming in asset file URLs.
* Added: Translation added for `romanian` , `portuguese`, `swedish`, `danish`, `serbian`,`russian`,`polish` & `czech`.

= 1.0.8 =
* Added: 1 hr caching on Sales Count Trigger.
* Added: Caching reset after new WC order for Sales Activity triggers.
* Added: Real-time active users data for last 15 mins.
* Added: Filter hooks to modify hard frontend text.
* Fixed: contact plugin author page in xl sales triggers settings UI.
* Added: German, Italian, Spanish, French & Dutch translations

= 1.0.7 =
* Fixed: Calling of Triggers when local product level not override.
* Updated: CMB2 tabs get_instance function coded to remove conflicts.

= 1.0.6 =
* Removed: Snippet that was creating conflict with Visual Composer Editor JS [Compatible with VC]
* Added: 1 hr caching on Best Seller & Sales Snippet Trigger.

= 1.0.5 =
* Added: Localization done. POT file added
* Added: Blank index.php file in each folders

= 1.0.4 =
* Fixed: Best Seller List trigger: show when 'Hide category' condition met true
* Updated: Smarter Reviews trigger, code modified to integrate with any 3rd party review system.

= 1.0.3 =
* Fixed: Deal Expiry trigger: did handling for a case when deal is on but sale price not set
* Added: Low Stock trigger: Out of stock feature added.
* Added: 1 Day, 3 Days, 7 Days & 15 Days options added to Best Sellers & Sales Activity triggers.
* Added: Compatibility with WooCommerce Bookings plugin

= 1.0.2 =
* Fixed: "is_super_admin" condition removed from get_instance function.
* Added: Sales Activity trigger: debug logs added.
* Removed: new WC_Product with wc_get_product as product type wasn't returning.
* Updated: Best Seller trigger: modified query to include current date.
* Updated: add to cart hook, code modified.

= 1.0.1 =
* Fixed: Sales Snippet trigger: modified query to include current date.
* Updated: License page text.
* Added: description below position field under trigger.
* Updated: Sales Insight trigger: user text 'display information' condition modified.
* Updated: Best Seller Badge trigger css.
* Added: WCST_Core class self instance static function added.
* Updated: Rules admin UI, css improved.
* Fixed: js replace function only replace 1 occurrence not all. so regex added for multiple replace
* Added: delete permanently action added on wcst triggers listing view
* Added: debug logs on best seller query.

= 1.0.0 =
* Public Release

= 0.9.0 =
* Added: Helpful links in plugin settings page sidebar.
* Added: Sales activities trigger: Date range field added.
* Updated: Best sellers trigger: SQL query optimized & cached.
* Updated: All Inline styles changed to Internal.
* Updated: Internal conditional logic changes on the change of tab.
* Fixed: Styling of Rules area.
* Added: New post status introduced for WCST Trigger custom post type. 
* Updated: Sales activity trigger: Code modified for rules.
* Updated: Savings trigger: Code modified for rules.
* Added: Rules

= 0.7.0 =
* Fixed: Settings issue in Smart reviews trigger.
* Added: wp_parse_args on data modal function to pass it with default values.
* Moved: Default values to WC_Common Class

= 0.4.0 =
* Fixed: Product page: Corrected On page load tab issue.
* Added: Best Seller trigger: ‘top badge’ merge tag added.
* Added: You save trigger: filter added for decimal point change.
* Updated: Global settings. - Fixed: Responsiveness checked & corrected.
* Fixed: UI on single product page modified.
* Updated: All CMB2 configuration modified.
* Fixed: Plugin activation issue, [Compatibility with give wp plugin].
* Updated: Sales Insights trigger queries cached & modified.
* Updated: Few labels.
* Fixed: Some merge tags were wrong, so corrected.
* Updated: Data called for the core logic by the new structure.
* Fixed: Woocommerce doesn’t exist issue. Causing fatal errors.
* Added: Guarantee trigger: support for built-in icons and custom icon.
* Added: ‘Need Help?’ tab on single product page.
* Removed: Required attribute from settings.
* Updated You Save trigger handling with the variable product type.

= 0.1.0 =
* Internal Release
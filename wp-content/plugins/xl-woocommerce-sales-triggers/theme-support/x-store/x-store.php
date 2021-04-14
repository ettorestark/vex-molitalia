<?php
add_action('wp', 'wcst_theme_helper_x_store', 99);

function wcst_theme_helper_x_store() {
    $wcst_core = WCST_Core::get_instance();
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 17.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 41.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_above_title'), 2.3);
    remove_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_title'), 9.3);
    remove_action('woocommerce_after_single_product_summary', array($wcst_core, 'wcst_position_below_related_products'), 21.2);

    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_price'), 25.1);
    add_action('woocommerce_single_product_summary', array($wcst_core, 'wcst_position_below_meta'), 8);

    add_filter('woocommerce_show_page_title', function ($bool) {
        $wcst_core = WCST_Core::get_instance();
        $wcst_core->wcst_position_above_title();
        ?>
        <h1 class="title">
            <?php if (!etheme_get_option('product_name_signle') && is_single() && !is_attachment()): ?>
                <?php echo get_the_title(); ?>
            <?php elseif (!is_single()): ?>
                <?php woocommerce_page_title(); ?>
            <?php endif; ?>
        </h1>
        <?php
        $wcst_core->wcst_position_below_title();
    }, 999);
}

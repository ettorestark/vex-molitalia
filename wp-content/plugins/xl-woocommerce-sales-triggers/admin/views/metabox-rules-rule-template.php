<td class="rule-type"><?php
    // allow custom location rules
    $types = apply_filters('wcst_rules_get_rule_types', array());

    // create field
    $args = array(
        'input' => 'select',
        'name' => 'wcst_rule[<%= groupId %>][<%= ruleId %>][rule_type]',
        'class' => 'rule_type',
        'choices' => $types,
    );

    WCST_Input_Builder::create_input_field($args, 'product_select');
    ?>
</td>

<?php
WCST_Common::render_rule_choice_template(array(
    'group_id' => 0,
    'rule_id' => 0,
    'rule_type' => 'product_select',
    'condition' => false,
    'operator' => false
));
?>
<td class="loading" colspan="2" style="display:none;"><?php _e('Loading...', WCST_SLUG); ?></td>
<td class="add"><a href="#" class="wcst-add-rule button"><?php _e("AND", WCST_SLUG); ?></a></td>
<td class="remove"><a href="#" class="wcst-remove-rule wcst-button-remove" <?php _e('Remove condition', WCST_SLUG); ?>></a></td>

<?php //WCST_Conditional_Content::nonce_field('admin'); ?>

<?php
global $post;

// vars
$groups = get_post_meta($post->ID, 'wcst_rule', true);


// at lease 1 location rule
if (empty($groups)) {
    $default_rule_id = 'rule' . uniqid();
    $groups = array(
        'group0' => array(
            $default_rule_id => array(
                'rule_type' => 'general_always',
                'operator' => '==',
                'condition' => '',
            )
        )
    );
}
?>

<div class="wcst-rules-builder woocommerce_options_panel">

    <div class="label">
        <h4><?php _e("Rules", WCST_SLUG); ?></h4>
        <p class="description"><?php _e("Create a set of rules to determine when the trigger defined above will be displayed.", WCST_SLUG); ?></p>
    </div>

    <div id="wcst-rules-groups">
        <div class="wcst-rule-group-target">
            <?php if (is_array($groups)): ?>
                <?php
                $group_counter = 0;
                foreach ($groups as $group_id => $group):
                    if (empty($group_id)) {
                        $group_id = 'group' . $group_id;
                    }
                    ?>

                    <div class="wcst-rule-group-container" data-groupid="<?php echo $group_id; ?>">
                        <div class="wcst-rule-group-header">
                            <?php if ($group_counter == 0): ?>
                                <h4><?php _e('Apply this Trigger when these conditions are matched:', WCST_SLUG); ?></h4>
                            <?php else: ?>
                                <h4><?php _e("or", WCST_SLUG); ?></h4>
                            <?php endif; ?>
                            <a href="#" class="wcst-remove-rule-group button"></a>
                        </div>
                        <?php if (is_array($group)): ?>
                            <table class="wcst-rules" data-groupid="<?php echo $group_id; ?>">
                                <tbody>
                                    <?php
                                    foreach ($group as $rule_id => $rule) :
                                        if (empty($rule_id)) {
                                            $rule_id = 'rule' . $rule_id;
                                        }
                                        ?>
                                        <tr data-ruleid="<?php echo $rule_id; ?>" class="wcst-rule">
                                            <td class="rule-type"><?php
                                                // allow custom location rules
                                                $types = apply_filters('wcst_rules_get_rule_types', array());

                                                // create field
                                                $args = array(
                                                    'input' => 'select',
                                                    'name' => 'wcst_rule[' . $group_id . '][' . $rule_id . '][rule_type]',
                                                    'class' => 'rule_type',
                                                    'choices' => $types,
                                                );
                                                WCST_Input_Builder::create_input_field($args, $rule['rule_type']);
                                                ?>
                                            </td>

                                            <?php
                                            WCST_Common::ajax_render_rule_choice(array(
                                                'group_id' => $group_id,
                                                'rule_id' => $rule_id,
                                                'rule_type' => $rule['rule_type'],
                                                'condition' => isset($rule['condition']) ? $rule['condition'] : false,
                                                'operator' => $rule['operator']
                                            ));
                                            ?>
                                            <td class="loading" colspan="2" style="display:none;"><?php _e('Loading...', WCST_SLUG); ?></td>
                                            <td class="add">
                                                <a href="#" class="wcst-add-rule button"><?php _e("AND", WCST_SLUG); ?></a>
                                            </td>
                                            <td class="remove">
                                                <a href="#" class="wcst-remove-rule wcst-button-remove" title="<?php _e('Remove condition', WCST_SLUG); ?>"></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    <?php $group_counter++; ?>
                <?php endforeach; ?>
            </div>

            <h4 class="rules_or" style="<?php echo ($group_counter > 1 ? 'display:block;' : 'display:none'); ?>" ><?php _e('or when these conditions are matched', WCST_SLUG); ?></h4>
            <button class="button button-primary wcst-add-rule-group" title="<?php _e('Add a set of conditions', WCST_SLUG); ?>"><?php _e("OR", WCST_SLUG); ?></button>
        <?php endif; ?>
    </div>
</div>

<script type="text/template" id="wcst-rule-template">
    <?php include 'metabox-rules-rule-template.php'; ?>
</script>
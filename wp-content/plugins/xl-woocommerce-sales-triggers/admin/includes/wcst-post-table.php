<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class WCST_Post_Table extends WP_List_Table {

    public $per_page = 40;
    public $data;

    /**
     * Constructor.
     * @since  1.0.0
     */
    public function __construct($args = array()) {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'license', //singular name of the listed records
            'plural' => 'licenses', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
        $status = 'all';

        $page = $this->get_pagenum();

        $this->data = array();

        // Make sure this file is loaded, so we have access to plugins_api(), etc.
        require_once(ABSPATH . '/wp-admin/includes/plugin-install.php');

        parent::__construct($args);
    }

    /**
     * Text to display if no items are present.
     * @since  1.0.0
     * @return  void
     */
    public function no_items() {
        echo wpautop(__('No Trigger Available', WCST_SLUG));
    }

    /**
     * The content of each column.
     * @param  array $item The current item in the list.
     * @param  string $column_name The key of the current column.
     * @since  1.0.0
     * @return string              Output for the current column.
     */
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'check-column':
                return '&nbsp;';
            case 'status':
            case 'trigger_name':

                return $item[$column_name];
                break;
        }
    }

    /**
     * Content for the "product_name" column.
     * @param  array $item The current item.
     * @since  1.0.0
     * @return string       The content of this column.
     */
    public function column_status($item) {
        if ($item['trigger_status'] == WCST_Common::get_trigger_post_type_slug() . 'disabled') {
            $text = __('Deactivated', WCST_SLUG);
            $link = get_post_permalink($item['id']);
        } else {
            $text = __('Activated', WCST_SLUG);
            $link = get_post_permalink($item['id']);
        }
        return wpautop($text);
    }

    public function column_name($item) {
        $edit_link = get_edit_post_link($item['id']);
        $column_string = '<strong>';
        if ($item['trigger_status'] == "trash") {
            $column_string .= '' . _draft_or_post_title($item['id']) . '' . _post_states(get_post($item['id'])) . '</strong>';
        } else {
            $column_string .= '<a href="' . $edit_link . '" class="row-title">' . _draft_or_post_title($item['id']) . '</a>' . _post_states(get_post($item['id'])) . '</strong>';
        }


        $column_string .= '<div class=\'row-actions\'>';


        $count = count($item['row_actions']);
        $column_string .= '<span class="">ID:' . $item['id'] . '';
        $column_string .= "</span> | ";
        foreach ($item['row_actions'] as $k => $action) {
            $column_string .= '<span class="' . $action['action'] . '"><a href="' . $action['link'] . '" ' . $action['attrs'] . '>' . $action['text'] . '</a>';
            if ($k < $count - 1) {
                $column_string .= " | ";
            }
            $column_string .= "</span>";
        }


        return wpautop($column_string);
    }




    public function column_visibility($item) {
        return wpautop(implode(" | ", $item['showon']));
    }

    public function column_menu_order($item) {
        return wpautop($item['menu_order']);
    }



    public function column_trigger($item) {

        return wpautop('<b>' . WCST_Common::get_trigger_nice_name(get_post_meta($item['id'], '_wcst_data_choose_trigger', true), true) . '</b>');
    }

    /**
     * Retrieve an array of possible bulk actions.
     * @since  1.0.0
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array();
        return $actions;
    }

    /**
     * Prepare an array of items to be listed.
     * @since  1.0.0
     * @return array Prepared items.
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $total_items = count($this->data);

        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $total_items                   //WE have to determine how many items to show on a page
        ));
        $this->items = $this->data;
    }

    /**
     * Retrieve an array of columns for the list table.
     * @since  1.0.0
     * @return array Key => Value pairs.
     */
    public function get_columns() {

        $columns = array(
            'check-column' => __('&nbsp;', WCST_SLUG),
            'name' => __('Title', WCST_SLUG),
            'trigger' => __('Trigger Type', WCST_SLUG),
            'visibility' => __('Visibility', WCST_SLUG),
            'status' => __('Status', WCST_SLUG),
            'menu_order' => __('Priority', WCST_SLUG),
        );
        return $columns;
    }

    /**
     * Retrieve an array of sortable columns.
     * @since  1.0.0
     * @return array
     */
    public function get_sortable_columns() {
        return array();
    }

    public function get_table_classes() {
        $get_default_classes = parent::get_table_classes();
        array_push($get_default_classes, 'wcst-instance-table');
        return $get_default_classes;
    }

    public function single_row($item) {
        $tr_class = 'wcst_trigger_active';
        if ($item['trigger_status'] == 'wcst_triggerdisabled') {
            $tr_class = 'wcst_trigger_deactive';
        }
        echo '<tr id="post-'.$item['id'].'" class="' . $tr_class . '">';
        $this->single_row_columns($item);
        echo '</tr>';
    }




    /**
     * Print column headers, accounting for hidden and sortable columns.
     *
     * @since 3.1.0
     * @access public
     *
     * @staticvar int $cb_counter
     *
     * @param bool $with_id Whether to set the id attribute or not
     */
    public function print_column_headersss( $with_id = true ) {
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();


        $sortable['status'] = array('status',0);
        $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
        $current_url = remove_query_arg( 'paged', $current_url );

        if ( isset( $_GET['orderby'] ) ) {
            $current_orderby = $_GET['orderby'];
        } else {
            $current_orderby = '';
        }

        if ( isset( $_GET['order'] ) && 'desc' === $_GET['order'] ) {
            $current_order = 'desc';
        } else {
            $current_order = 'asc';
        }

        if ( ! empty( $columns['cb'] ) ) {
            static $cb_counter = 1;
            $columns['cb'] = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All' ) . '</label>'
                . '<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />';
            $cb_counter++;
        }

        foreach ( $columns as $column_key => $column_display_name ) {
            $class = array( 'manage-column', "column-$column_key" );

            if ( in_array( $column_key, $hidden ) ) {
                $class[] = 'hidden';
            }

            if ( 'cb' === $column_key )
                $class[] = 'check-column';
            elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ) ) )
                $class[] = 'num';

            if ( $column_key === $primary ) {
                $class[] = 'column-primary';
            }

            if ( isset( $sortable[$column_key] ) ) {
                list( $orderby, $desc_first ) = $sortable[$column_key];

                if ( $current_orderby === $orderby ) {
                    $order = 'asc' === $current_order ? 'desc' : 'asc';
                    $class[] = 'sorted';
                    $class[] = $current_order;
                } else {
                    $order = $desc_first ? 'desc' : 'asc';
                    $class[] = 'sortable';
                    $class[] = $desc_first ? 'asc' : 'desc';
                }

                $column_display_name = '<a href="' . esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ) . '"><span>' . $column_display_name . '</span><span class="sorting-indicator"></span></a>';
            }


            $tag = ( 'cb' === $column_key ) ? 'td' : 'th';
            $scope = ( 'th' === $tag ) ? 'scope="col"' : '';
            $id = $with_id ? "id='$column_key'" : '';

            if ( !empty( $class ) )
                $class = "class='" . join( ' ', $class ) . "'";

            echo "<$tag $scope $id $class>$column_display_name</$tag>";
        }
    }

}

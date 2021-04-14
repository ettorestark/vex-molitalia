<?php
/**
 * Visanet Peru - WooCommerce Gateway Visa Logo.
 *
 * @since   2.0.0
 * @package Visanet_Peru_WooCommerce_Gateway
 */

/**
 * Visanet Peru - WooCommerce Gateway Visa Logo class.
 *
 * @since 2.0.0
 */
class WGVP_Visa_Logo extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	protected $widget_slug = 'woocommerce-gateway-visanetperu-visa-logo';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  2.0.0
	 */
	protected $default_widget_title = '';

	/**
	 * Shortcode name for this widget
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	protected static $shortcode = 'woocommerce-gateway-visanetperu-visa-logo';

	/**
	 * Construct widget class.
	 *
	 * @since  2.0.0
	 */
	public function __construct() {

		$this->widget_name = esc_html__( 'Visa Logo', 'woocommerce-gateway-visanetperu' );
		$this->default_widget_title = esc_html__( 'New title', 'woocommerce-gateway-visanetperu' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'Visa Logo', 'woocommerce-gateway-visanetperu' ),
			)
		);

		// Clear cache on save.
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

		// Add a shortcode for our widget.
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}

	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 *
	 * @since  2.0.0
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $args     The widget arguments set up when a sidebar is registered.
	 * @param  array $instance The widget settings as set by user.
	 */
	public function widget( $args, $instance ) {
		// Set widget attributes.
		$atts = array(
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => $instance['title']
		);

		// Display the widget.
		echo self::get_widget( $atts ); // WPCS XSS OK.
	}

	/**
	 * Return the widget/shortcode output
	 *
	 * @since  2.0.0
	 *
	 * @param  array $atts Array of widget/shortcode attributes/args.
	 * @return string      Widget output
	 */
	public static function get_widget( $atts ) {
		$defaults = array(
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
			'title'         => ''
		);

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, self::$shortcode );

		// Start an output buffer.
		ob_start();

		// Start widget markup.
		echo $atts['before_widget']; // WPCS XSS OK.

		// Maybe display widget title.
		echo ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '' ; // WPCS XSS OK.

		// Show image
		echo __( '<img src="' . wc_gateway_visanetperu()->url( 'assets/images/visa.png' ) . '" width="60" height="29" />', 'woocommerce-gateway-visanetperu' );

		// End the widget markup.
		echo $atts['after_widget']; // WPCS XSS OK.

		// Return the output buffer.
		return ob_get_clean();
	}

	/**
	 * Update form values as they are saved.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $new_instance New settings for this instance as input by the user.
	 * @param  array $old_instance Old settings for this instance.
	 * @return array               Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		// Previously saved values.
		$instance = $old_instance;

		// Sanity check new data existing.
		$title = isset( $new_instance['title'] ) ? $new_instance['title'] : '';

		// Sanitize title before saving to database.
		$instance['title'] = sanitize_text_field( $title );

		// Flush cache.
		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Back-end widget form with defaults.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $instance Current settings.
	 */
	public function form( $instance ) {
		// Set defaults.
		$defaults = array(
			'title' => $this->default_widget_title
		);

		// Parse args.
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'woocommerce-gateway-visanetperu' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" />
		</p>
		<?php
	}
}
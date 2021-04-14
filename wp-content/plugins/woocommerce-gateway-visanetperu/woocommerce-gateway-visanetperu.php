<?php
/**
 * Plugin Name: Visanet Peru - WooCommerce Gateway
 * Plugin URI:  http://socialmediaspiders.com/product/woocommerce-visanet/
 * Description: Extends WooCommerce by Adding the Visanet Perú Gateway.
 * Version:     2.0.5
 * Author:      Vex Soluciones
 * Author URI:  https://www.vexsoluciones.com
 * Donate link: http://socialmediaspiders.com/product/woocommerce-visanet/
 * License:     GPL-2.0+
 * Text Domain: woocommerce-gateway-visanetperu
 * Domain Path: /languages
 *
 * @link    http://socialmediaspiders.com/product/woocommerce-visanet/
 *
 * @package Woocommerce_Gateway_VisanetPeru
 * @version 2.0.5
 *
 * Built using generator-plugin-wp (https://github.com/WebDevStudios/generator-plugin-wp)
 */

/**
 * Copyright (c) 2017 VexSoluciones (email : info@vexsoluciones.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * Autoloads files with classes when needed.
 *
 * @since  2.0.0
 * @param  string $class_name Name of the class being requested.
 */
function wc_gateway_visanetperu_autoload_classes( $class_name ) {
	// If our class doesn't have our prefix, don't load it.
	if ( 0 !== strpos( $class_name, 'WGVP_' ) ) {
		return;
	}

	// Set up our filename.
	$filename = strtolower( str_replace( '_', '-', substr( $class_name, strlen( 'WGVP_' ) ) ) );

	//echo $filename . ' <-> ' . $class_name . '<br>';

	// Include our file.
	Woocommerce_Gateway_VisanetPeru::include_file( 'includes/class-' . $filename );
}
spl_autoload_register( 'wc_gateway_visanetperu_autoload_classes' );

/**
 * Main initiation class.
 *
 * @since  2.0.0
 */
final class Woocommerce_Gateway_VisanetPeru {

	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	const VERSION = '2.0.5';

	/**
	 * URL of plugin directory.
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var    string
	 * @since  2.0.0
	 */
	protected $basename = '';

	/**
	 * Detailed activation error messages.
	 *
	 * @var    array
	 * @since  2.0.0
	 */
	protected $activation_errors = array();

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    Woocommerce_Gateway_VisanetPeru
	 * @since  2.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Instance of WGVP_Validate
	 *
	 * @since2.0.0
	 * @var WGVP_Validate
	 */
	protected $validate;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   2.0.0
	 * @return  Woocommerce_Gateway_VisanetPeru A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin.
	 *
	 * @since  2.0.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  2.0.0
	 */
	public function plugin_classes() {
		$this->validate = new WGVP_Validate( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  2.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'woocommerce_payment_gateways', array( $this, 'add_gateway' ), 0 );
		add_action( 'widgets_init', array( $this, 'register_visa_logo' ) );

		add_filter( 'plugin_action_links_' . $this->basename, array( $this, 'action_links' ) );
	}

	public function register_visa_logo() {
		register_widget( 'WGVP_Visa_Logo' );
	}

	public function action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=visanet-pe' ) . '">' . __( 'Settings', 'woocommerce-gateway-visanetperu' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}

	public function add_gateway( $methods ){
		$methods[] = 'WGVP_Gateway_Visanet_Peru';
		return $methods;
	}

	/**
	 * Activate the plugin.
	 *
	 * @since  2.0.0
	 */
	public function _activate() {
		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin.
	 * Uninstall routines should be in uninstall.php.
	 *
	 * @since  2.0.0
	 */
	public function _deactivate() {
		// Add deactivation cleanup functionality here.
	}

	/**
	 * Init hooks
	 *
	 * @since  2.0.0
	 */
	public function init() {

		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Load translated strings for plugin.
		load_plugin_textdomain( 'woocommerce-gateway-visanetperu', false, dirname( $this->basename ) . '/languages/' );

		// Initialize plugin classes.
		$this->plugin_classes();
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  2.0.0
	 *
	 * @return boolean True if requirements met, false if not.
	 */
	public function check_requirements() {

		// Bail early if plugin meets requirements.
		if ( $this->meets_requirements() ) {
			return true;
		}

		// Add a dashboard notice.
		add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

		// Deactivate our plugin.
		add_action( 'admin_init', array( $this, 'deactivate_me' ) );

		// Didn't meet the requirements.
		return false;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  2.0.0
	 */
	public function deactivate_me() {

		// We do a check for deactivate_plugins before calling it, to protect
		// any developers from accidentally calling it too early and breaking things.
		if ( function_exists( 'deactivate_plugins' ) ) {
			deactivate_plugins( $this->basename );
		}
	}

	/**
	 * Check that all plugin requirements are met.
	 *
	 * @since  2.0.0
	 *
	 * @return boolean True if requirements are met.
	 */
	public function meets_requirements() {
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			array_push( $this->activation_errors, __( '- WooCommerce is required', 'woocommerce-gateway-visanetperu' ) );
			return false;
		}

		// Do checks for required classes / functions or similar.
		// Add detailed messages to $this->activation_errors array.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met.
	 *
	 * @since  2.0.0
	 */
	public function requirements_not_met_notice() {

		// Compile default message.
		$default_message = sprintf( __( 'Visanet Peru - WooCommerce Gateway is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'woocommerce-gateway-visanetperu' ), admin_url( 'plugins.php' ) );

		// Default details to null.
		$details = null;

		// Add details if any exist.
		if ( $this->activation_errors && is_array( $this->activation_errors ) ) {
			$details = '<small>' . implode( '</small><br /><small>', $this->activation_errors ) . '</small>';
		}

		// Output errors.
		?>
		<div id="message" class="error">
			<p><?php echo wp_kses_post( $default_message ); ?></p>
			<?php echo wp_kses_post( $details ); ?>
		</div>
		<?php
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $field Field to get.
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'validate':
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $filename Name of the file to be included.
	 * @return boolean          Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( $filename . '.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path.
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path.
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the Woocommerce_Gateway_VisanetPeru object and return it.
 * Wrapper for Woocommerce_Gateway_VisanetPeru::get_instance().
 *
 * @since  2.0.0
 * @return Woocommerce_Gateway_VisanetPeru  Singleton instance of plugin class.
 */
function wc_gateway_visanetperu() {
	return Woocommerce_Gateway_VisanetPeru::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( wc_gateway_visanetperu(), 'hooks' ) );

// Activation and deactivation.
register_activation_hook( __FILE__, array( wc_gateway_visanetperu(), '_activate' ) );
register_deactivation_hook( __FILE__, array( wc_gateway_visanetperu(), '_deactivate' ) );
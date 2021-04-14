<?php
/**
 * Plugin Name: Vexsoluciones Peru Ubigeo
 * Plugin URI:  https://www.pasarelasdepagos.com
 * Description: Agrega campos de ubigeo para el formulario del checkout de wordpress
 * Version:     1.0
 * Author:      vexsoluciones
 * Author URI:  https://www.pasarelasdepagos.com
 * Donate link: https://www.pasarelasdepagos.com
 * License:     GPLv2
 * Text Domain: vexsoluciones-peru-ubigeo
 * Domain Path: /languages
 *
 * @link    https://www.pasarelasdepagos.com
 *
 * @package Vexsoluciones_Peru_Ubigeo
 * @version 1.0
 *
 * Built using generator-plugin-wp (https://github.com/WebDevStudios/generator-plugin-wp)
 */

/**
 * Copyright (c) 2019 vexsoluciones (email : https://www.pasarelasdepagos.com)
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


// Include additional php files here.
// require 'includes/something.php';

/**
 * Main initiation class.
 *
 * @since  1.0
 */
final class Vexsoluciones_Peru_Ubigeo {

	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  1.0
	 */
	const VERSION = '1.0';
        const DIR = WP_CONTENT_DIR . "/plugins/vexsoluciones-peru-ubigeo";
        const TEXT_DOMAIN = "vexsoluciones-peru-ubigeo";

	/**
	 * URL of plugin directory.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $basename = '';

	/**
	 * Detailed activation error messages.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $activation_errors = array();

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    Vexsoluciones_Peru_Ubigeo
	 * @since  1.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   1.0
	 * @return  Vexsoluciones_Peru_Ubigeo A single instance of this class.
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
	 * @since  1.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  1.0
	 */
	public function plugin_classes() {
		// $this->plugin_class = new VPU_Plugin_Class( $this );

	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  1.0
	 */
	public function hooks() {
            add_action( 'init', array( $this, 'init' ), 0 );
            add_action("wp_enqueue_scripts", [$this, "enqueueScripts"]);
	}

	/**
	 * Activate the plugin.
	 *
	 * @since  1.0
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
	 * @since  1.0
	 */
	public function _deactivate() {
		// Add deactivation cleanup functionality here.
	}

	/**
	 * Init hooks
	 *
	 * @since  1.0
	 */
	public function init() {

		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}
                
                require_once("includes/VexSolucionesUbigeos.php");
                require_once("includes/VexSolucionesUbigeosFormFields.php");
                new VexSolucionesUbigeosFormFields(); 

		// Load translated strings for plugin.
		load_plugin_textdomain( 'vexsoluciones-peru-ubigeo', false, dirname( $this->basename ) . '/languages/' );

		// Initialize plugin classes.
		$this->plugin_classes();
	}
        
      public function enqueueScripts() {
            if(is_checkout()) {
                wp_enqueue_style("olva-checkout", self::plugin_url() . '/assets/css/checkout.css');
                wp_enqueue_script('olva-checkout', self::plugin_url() . '/assets/js/checkout.js', array('jquery'), 1, true);
                wp_localize_script("olva-checkout", "wp", [
                    "checkoutFields" => VexSolucionesUbigeosFormFields::getCheckoutFields(),
                    "country" => VexSolucionesUbigeosFormFields::PERU,
                    "departaments" => VexSolucionesUbigeos::getDepartaments(),
                    "provinces" => VexSolucionesUbigeos::getProvinces(),
                    "districts" => VexSolucionesUbigeos::getDistricts(),
                    "googleApiKey" => Woocommerce_Olva_Courier::get_instance()->getSettings()->getApiKeyGoogle(),
                ]);
            }
        }

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  1.0
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
	 * @since  1.0
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
	 * @since  1.0
	 *
	 * @return boolean True if requirements are met.
	 */
	public function meets_requirements() {

		// Do checks for required classes / functions or similar.
		// Add detailed messages to $this->activation_errors array.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met.
	 *
	 * @since  1.0
	 */
	public function requirements_not_met_notice() {

		// Compile default message.
		$default_message = sprintf( __( 'Vexsoluciones Peru Ubigeo is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'vexsoluciones-peru-ubigeo' ), admin_url( 'plugins.php' ) );

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
	 * @since  1.0
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
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}
        
        public static function plugin_url() {
            return content_url() . "/plugins/vexsoluciones-peru-ubigeo";
        }
        
}

/**
 * Grab the Vexsoluciones_Peru_Ubigeo object and return it.
 * Wrapper for Vexsoluciones_Peru_Ubigeo::get_instance().
 *
 * @since  1.0
 * @return Vexsoluciones_Peru_Ubigeo  Singleton instance of plugin class.
 */
function vexsoluciones_peru_ubigeo() {
	return Vexsoluciones_Peru_Ubigeo::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( vexsoluciones_peru_ubigeo(), 'hooks' ) );

// Activation and deactivation.
register_activation_hook( __FILE__, array( vexsoluciones_peru_ubigeo(), '_activate' ) );
register_deactivation_hook( __FILE__, array( vexsoluciones_peru_ubigeo(), '_deactivate' ) );

<?php
/**
 * Plugin Name: WC Mix and Match Grid
 * Plugin URI:  http://github.com/
 * Description: Convert Mix and Match product options to a grid layout
 * Version:     1.0.0-beta
 * Author:      Kathy Darling
 * Author URI:  kathyisawesome.com
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wc-mnm-grid
 * Domain Path: /languages
 * Requires at least: 4.4.0
 * Tested up to: 4.4.2
 * WC requires at least: 3.0.0
 * WC tested up to: 3.2.0   
 */

/**
 * Copyright: © 2017 Kathy Darling.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */


/**
 * The Main WC_MNM_Grid class
 **/
if ( ! class_exists( 'WC_MNM_Grid' ) ) :

class WC_MNM_Grid {

	const VERSION = '1.0.0';
	const PREFIX  = 'WC_MNM_Grid';
	const REQUIRED_WC = '3.0.0';

	/**
	 * @var WC_MNM_Grid - the single instance of the class
	 * @since 1.0.0
	 */
	protected static $instance = null;            

	/**
	 * Plugin Path Directory
	 *
	 * @since 1.0.0
	 * @var string $path
	 */
	private $path = '';

	/**
	 * Plugin URL
	 *
	 * @since 1.0.0
	 * @var string $url
	 */
	private $url = '';


	/**
	 * Main WC_MNM_Grid Instance
	 *
	 * Ensures only one instance of WC_MNM_Grid is loaded or can be loaded.
	 *
	 * @static
	 * @see WC_MNM_Grid()
	 * @return WC_MNM_Grid - Main instance
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WC_MNM_Grid ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	public function __construct(){

		// Include autoloader.
		include_once 'includes/autoload.php';

		// check we're running the required version of WC
		if ( ! defined( 'WC_VERSION' ) || version_compare( WC_VERSION, self::REQUIRED_WC, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			return false;
		}

		// include the front-end functions
		if ( ! is_admin() ) {
			add_action( 'init', array( '\WC_MNM_Grid\Display', 'init' ) );
		}

		// Load translation files
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

	}


	/*-----------------------------------------------------------------------------------*/
	/* Notice */
	/*-----------------------------------------------------------------------------------*/



	/**
	 * Displays a warning message if version check fails.
	 * @return string
	 * @since  1.0.0
	 */
	public function admin_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WC Mix and Match Grid requires at least WooCommerce %s in order to function. Please upgrade WooCommerce.', 'wc-mnm-grid' ), self::REQUIRED_WC ) . '</p></div>';
	}


	/*-----------------------------------------------------------------------------------*/
	/* Localization */
	/*-----------------------------------------------------------------------------------*/


	/**
	 * Make the plugin translation ready
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wc-mnm-grid' , false , dirname( plugin_basename( __FILE__ ) ) .  '/languages/' );
	}


	/*-----------------------------------------------------------------------------------*/
	/* Helpers */
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Get plugin URL
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public function get_plugin_url() {
		if( $this->url == '' ) {
			$this->url = untrailingslashit( plugins_url( '/', __FILE__ ) );
		}
		return $this->url;
	}

	/**
	 * Get plugin path
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public function get_plugin_path() {
		if( $this->path == '' ) {
			$this->path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		}
		return $this->path;

	}

} //end class: do not remove or there will be no more guacamole for you

endif; // end class_exists check


/**
 * Returns the main instance of WC_MNM_Grid to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return WC_MNM_Grid
 */
function WC_MNM_Grid() {
	return WC_MNM_Grid::instance();
}

// Launch the whole plugin
add_action( 'woocommerce_loaded', 'WC_MNM_Grid' );
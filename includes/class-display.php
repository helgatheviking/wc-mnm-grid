<?php
/**
 * Functions related to front-end display
 *
 * @class 	WC_MNM_Grid_Display
 * @version 1.0.0
 * @since   1.0.0
 */

namespace WC_MNM_Grid;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Display {

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public static function init() {

		// Single Product Display
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_styles', 20 );
		add_filter( 'woocommerce_locate_template', __CLASS__ . '::locate_template', 10, 3 );

		// Move the add to cart function for MNM products.
		add_action( 'woocommerce_single_product_summary', __CLASS__ . '::maybe_switch_add_to_cart', 10 );

	}


	/*-----------------------------------------------------------------------------------*/
	/* Single Product Display Functions */
	/*-----------------------------------------------------------------------------------*/


	/**
	 * Register the script
	 *
	 * @return void
	 */
	public static function register_styles() {
		wp_deregister_style( 'wc-mnm-frontend' );
	}


	/**
	 * Switch Templates to this plugin.
	 *
	 * @return  void
	 * @since 1.0.0
	 */
	public static function locate_template( $template, $template_name, $template_path ) {
 
 		// Only bother looking to replace our specific templates
 		if( in_array( $template_name, 
 			array( 
 				'single-product/add-to-cart/mnm.php', 
 				'single-product/mnm/mnm-item.php', 
 				'single-product/mnm/mnm-product-thumbnail.php', 
 				'single-product/mnm/mnm-product-title.php' 
 				) 
 			) ) {
	 		
	 		// Original Template.
			$_template = $template;
		 
		 	// This plugin's template path.
			$plugin_path  = WC_MNM_Grid()->get_plugin_path() . '/templates/';
		 
			// Look within passed path within the theme - this is priority.
			$template = locate_template(
				array(
					$template_path . $template_name,
					$template_name
				)
			);
		 	 
			// Modification: Get the template from this plugin, if it exists.
			if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
				$template = $plugin_path . $template_name;
			}
		 
			// Use default template.
			if ( ! $template ) {	 
				$template = $_template;
			}

		}
	 	 
		// Return what we found.
	 	return $template;
	 
	}


	/**
	 * When displaying a type that doesn't need to be moved, call 'woocommerce_template_single_add_to_cart' as usual.
	 * Hooked into 'woocommerce_single_product_summary', same position as before.
	 */
	public static function maybe_switch_add_to_cart() {

		global $product;

		if( $product->is_type( 'mix-and-match' ) ) {
			// Unhook 'woocommerce_template_single_add_to_cart' from 'woocommerce_single_product_summary' 30.
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

			// Hook woocommerce_template_single_add_to_cart into 'woocommerce_after_single_product_summary' 
			add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_add_to_cart', 5 );

			// Unhook 'woocommerce_template_mnm_product_attributes' from 'woocommerce_mnm_row_item_description' 30.
			remove_action( 'woocommerce_mnm_row_item_description', 'woocommerce_template_mnm_product_attributes', 20 );

		}

	}



} //end class

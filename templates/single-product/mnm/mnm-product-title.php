<?php
/**
 * MNM Item Product Title + Attributes
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly	
}
?>
<label for="mnm-<?php echo WC_MNM_Core_Compatibility::get_id( $mnm_product ); ?>">
	
	<?php
	$title = $mnm_product->is_type( 'variation' ) ? $mnm_product->get_title() . ' : ' . wc_get_formatted_variation( $mnm_product, true, false ) : $mnm_product->get_title();
	?>
	
	<?php echo ( ! $mnm_product->is_type( 'variation' ) && $mnm_product->is_visible() ) || ( $mnm_product->is_type( 'variation' ) && $mnm_product->variation_is_visible() ) ? '<a href="' . $mnm_product->get_permalink() . '" target="_blank">' . $title . '</a>' : $title; ?>
</label>
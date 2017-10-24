<?php
/**
 * MNM Grid Item Product Thumbnail
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ){
	exit; // Exit if accessed directly
}

?>

<div class="mnm_grid_image">
	<?php echo $mnm_product->get_image( apply_filters( 'woocommerce_mnm_product_thumbnail_size', 'shop_catalog' ) ); ?>
</div>

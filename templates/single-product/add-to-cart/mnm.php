<?php
/**
 * Mix & Match product add to cart
 *
 * @author 		Kathy Darling
 * @version     1.0.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ){
	exit;
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}
?>

<?php if ( $product->has_available_children() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form method="post" enctype="multipart/form-data" class="mnm_form cart cart_group">

		<ul class="products">

			<?php

				global $woocommerce_loop;
				
				// Stash to WooCommerce loop.
				$loop = isset( $woocommerce_loop['loop'] ) ? $woocommerce_loop['loop'] : 0;

				// Reset the loop/
				$woocommerce_loop['loop'] = 0;

				foreach ( $product->get_available_children() as $mnm_product ) {

					// Load the table row for each item.
					wc_get_template(
						'single-product/mnm/mnm-item.php',
						array(
							'product'     => $product,
							'mnm_product' => $mnm_product
						),
						'',
						WC_MNM_Grid()->get_plugin_path() . '/templates/'
					);

					//wc_get_template_part( 'content', 'product' );

					// Restore the original loop.
					$woocommerce['loop'] = $loop;
				}
			?>
		

		</ul>

		<div class="mnm_cart cart" <?php echo $product->get_data_attributes(); ?>>

			<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<div class="mnm_button_wrap" style="display:block">

				<div class="mnm_price"></div>

				<div class="mnm_message"><div class="mnm_message_content woocommerce-info"><?php echo wc_mnm_get_quantity_message( $product ); ?></div></div>
				<?php

				// MnM Availability.
				$availability = $product->get_availability();

				if ( $availability[ 'availability' ] ){
					echo apply_filters( 'woocommerce_stock_html', '<p class="stock ' . $availability[ 'class' ] . '">' . $availability[ 'availability' ] . '</p>', $availability[ 'availability' ] );
				}

		 		if ( ! $product->is_sold_individually() ){
		 			woocommerce_quantity_input( array(
		 				'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
		 				'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
		 			) );
		 		}
		 		?>

		 		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( WC_MNM_Core_Compatibility::get_id( $product ) ); ?>" />

				<button type="submit" class="single_add_to_cart_button mnm_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>

			</div>
		</div>
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php else : ?>

	<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability[ 'availability' ] ) ? '' : '<p class="stock ' . esc_attr( $availability[ 'class' ] ) . '">' . esc_html( $availability[ 'availability' ] ) . '</p>';

	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability[ 'availability' ], $product );
?>

<?php endif; ?>

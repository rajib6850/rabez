<?php
/**
 * WooCommerce Loop Product Card Template (RABEZ Custom Skin)
 * Copy to: your-child-theme/woocommerce/content-product.php
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_id   = $product->get_id();
$sleeve_terms = wc_get_product_terms( $product_id, 'pa_sleeve', array( 'fields' => 'names' ) );
$sleeve_label = ! empty( $sleeve_terms ) ? $sleeve_terms[0] : 'Half Sleeve';
$club_terms   = wc_get_product_terms( $product_id, 'product_cat', array( 'fields' => 'names' ) );
$club_label   = ! empty( $club_terms ) ? $club_terms[0] : 'Club Kit';
$sizes        = array( 'S', 'M', 'L', 'XL', 'XXL' );
?>
<li <?php wc_product_class( 'rabez-product-card', $product ); ?> data-product-id="<?php echo esc_attr( $product_id ); ?>" data-selected-size="L">

	<div class="rabez-product-card__media">
		<div class="rabez-product-card__badges">
			<?php if ( $product->is_featured() ) : ?>
				<span class="rabez-badge rabez-badge--hot"><?php esc_html_e( 'Best Seller', 'rabez' ); ?></span>
			<?php endif; ?>
			<span class="rabez-badge rabez-badge--sleeve"><?php echo esc_html( $sleeve_label ); ?></span>
		</div>

		<button type="button" class="rabez-quickview-trigger js-quickview-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Quick View', 'rabez' ); ?>">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
				<circle cx="12" cy="12" r="3"></circle>
			</svg>
		</button>

		<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
			<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ); ?>
		</a>
	</div>

	<div class="rabez-product-card__body">
		<div class="rabez-product-card__meta">
			<span class="rabez-product-card__club"><?php echo esc_html( $club_label ); ?></span>
			<span>180 GSM</span>
		</div>

		<h3 class="woocommerce-loop-product__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<span class="price"><?php echo $product->get_price_html(); ?></span>

		<div class="rabez-product-card__sizes" aria-label="<?php esc_attr_e( 'Select Size', 'rabez' ); ?>">
			<div class="rabez-size-swatches">
				<?php foreach ( $sizes as $size ) : ?>
					<button type="button"
						class="rabez-size-swatch js-select-card-size <?php echo ( 'L' === $size ) ? 'is-selected' : ''; ?>"
						data-size="<?php echo esc_attr( $size ); ?>">
						<?php echo esc_html( $size ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="rabez-product-card__footer">
			<button type="button"
				class="button product_type_variable add_to_cart_button ajax_add_to_cart js-add-to-cart"
				data-product_id="<?php echo esc_attr( $product_id ); ?>">
				<span><?php esc_html_e( 'Add to Bag', 'rabez' ); ?></span>
			</button>
		</div>
	</div>

</li>

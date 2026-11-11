<?php
/**
 * RABEZ Custom Elementor Widget Boilerplate (WooCommerce Jersey Grid)
 * Drop this into your child theme or a custom plugin to register RABEZ widgets in Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rabez_Elementor_Jersey_Grid_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'rabez_jersey_grid';
	}

	public function get_title() {
		return esc_html__( 'RABEZ Jersey Product Grid', 'rabez' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return [ 'general', 'woocommerce-elements' ];
	}

	public function get_style_depends() {
		return [ 'rabez-variables', 'rabez-woo-compat', 'rabez-widget-product-grid' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Jersey Query Settings', 'rabez' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Number of Jerseys', 'rabez' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 8,
			]
		);

		$this->add_control(
			'product_cat',
			[
				'label'       => esc_html__( 'Category Slug (e.g. full-sleeve, half-sleeve)', 'rabez' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'all',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<p>Please activate WooCommerce.</p>';
			return;
		}

		$settings = $this->get_settings_for_display();
		$args     = [
			'post_type'      => 'product',
			'posts_per_page' => intval( $settings['posts_per_page'] ),
			'post_status'    => 'publish',
		];

		if ( ! empty( $settings['product_cat'] ) ) {
			$args['product_cat'] = sanitize_text_field( $settings['product_cat'] );
		}

		$loop = new \WP_Query( $args );

		echo '<div class="woocommerce"><ul class="products columns-4">';
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) {
				$loop->the_post();
				wc_get_template_part( 'content', 'product' );
			}
		}
		echo '</ul></div>';
		wp_reset_postdata();
	}
}


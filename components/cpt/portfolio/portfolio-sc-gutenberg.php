<?php
/**
 * ThemeREX Addons Custom post type: Portfolio (Gutenberg support)
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.5
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}



// Gutenberg Block
//------------------------------------------------------

// Add scripts and styles for the editor
if ( ! function_exists( 'trx_addons_gutenberg_sc_portfolio_editor_assets' ) ) {
	add_action( 'enqueue_block_editor_assets', 'trx_addons_gutenberg_sc_portfolio_editor_assets' );
	function trx_addons_gutenberg_sc_portfolio_editor_assets() {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			// Scripts
			wp_enqueue_script(
				'trx-addons-gutenberg-editor-block-portfolio',
				trx_addons_get_file_url( TRX_ADDONS_PLUGIN_CPT . 'portfolio/gutenberg/portfolio.gutenberg-editor.js' ),
				array( 'wp-blocks', 'wp-editor', 'wp-i18n', 'wp-element', 'trx_addons-admin', 'trx_addons-utils', 'trx_addons-gutenberg-blocks' ),
				filemtime( trx_addons_get_file_dir( TRX_ADDONS_PLUGIN_CPT . 'portfolio/gutenberg/portfolio.gutenberg-editor.js' ) ),
				true
			);
		}
	}
}

// Block register
if ( ! function_exists( 'trx_addons_sc_portfolio_add_in_gutenberg' ) ) {
	add_action( 'init', 'trx_addons_sc_portfolio_add_in_gutenberg' );
	function trx_addons_sc_portfolio_add_in_gutenberg() {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			register_block_type(
				'trx-addons/portfolio', array(
					'attributes'      => array_merge(
						array(
							'type'               => array(
								'type'    => 'string',
								'default' => 'default',
							),
							'more_text'          => array(
								'type'    => 'string',
								'default' => esc_html__( 'Read more', 'trx_addons' ),
							),
							'pagination'         => array(
								'type'    => 'string',
								'default' => 'none',
							),
							'cat'                => array(
								'type'    => 'string',
								'default' => '0',
							),
						),
						trx_addons_gutenberg_get_param_query(),
						trx_addons_gutenberg_get_param_slider(),
						trx_addons_gutenberg_get_param_title(),
						trx_addons_gutenberg_get_param_button(),
						trx_addons_gutenberg_get_param_id()
					),
					'render_callback' => 'trx_addons_gutenberg_sc_portfolio_render_block',
				)
			);
		}
	}
}

// Block render
if ( ! function_exists( 'trx_addons_gutenberg_sc_portfolio_render_block' ) ) {
	function trx_addons_gutenberg_sc_portfolio_render_block( $attributes = array() ) {
		return trx_addons_sc_portfolio( $attributes );
	}
}

// Return list of allowed layouts
if ( ! function_exists( 'trx_addons_gutenberg_sc_portfolio_get_layouts' ) ) {
	add_filter( 'trx_addons_filter_gutenberg_sc_layouts', 'trx_addons_gutenberg_sc_portfolio_get_layouts', 10, 1 );
	function trx_addons_gutenberg_sc_portfolio_get_layouts( $array = array() ) {
		$array['trx_sc_portfolio'] = apply_filters( 'trx_addons_sc_type', trx_addons_components_get_allowed_layouts( 'cpt', 'portfolio', 'sc' ), 'trx_sc_portfolio' );

		return $array;
	}
}

// Add shortcode's specific vars to the JS storage
if ( ! function_exists( 'trx_addons_gutenberg_sc_portfolio_params' ) ) {
	add_filter( 'trx_addons_filter_gutenberg_sc_params', 'trx_addons_gutenberg_sc_portfolio_params' );
	function trx_addons_gutenberg_sc_portfolio_params( $vars = array() ) {
		if ( trx_addons_exists_gutenberg() && trx_addons_get_setting( 'allow_gutenberg_blocks' ) ) {
			// Dishes categories
			$vars['sc_portfolio_cat']    = trx_addons_get_list_terms( false, TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY );
			$vars['sc_portfolio_cat'][0] = esc_html__( '- Select category -', 'trx_addons' );

			return $vars;
		}
	}
}

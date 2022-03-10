<?php

namespace ProDevign\BlockMeister;

use ProDevign\BlockMeister\Pattern_Builder\Pattern_Builder;

class Utils {


	public static function camel2dashed( $word ) {
		return strtolower( preg_replace( '/([A-Z])/', '-$1', $word ) );
	}


	/**
	 * Check if the current screen is the list table for blockmeister_pattern post type.
	 * Note: this method can be used even before the global current_screen is available!
	 *
	 * @return bool True if this is the blockmeister_pattern list table, else false
	 */
	public static function is_blockmeister_pattern_list_table_screen() {
		global $pagenow, $plugin_page;
		$is_blockmeister_pattern_list_table = is_admin() &&
		                                      $pagenow === 'edit.php' &&
		                                      is_null( $plugin_page ) &&
		                                      isset( $_GET['post_type'] ) && $_GET['post_type'] === Pattern_Builder::POST_TYPE;

		return $is_blockmeister_pattern_list_table;
	}

	/**
	 * Return whether the post can be edited in the block editor.
	 *
	 * Note: This is an alternative to the core $current_screen->is_block_editor. But
	 *       this version can be called in an action admin_init handler when the global current_screen
	 *       is not yet filled.
	 *
	 * @param int|\WP_Post $post ost ID or WP_Post object
	 *
	 * @return bool Whether the post can be edited in the block editor.
	 */
	public static function is_block_editor( $post ) {
		$is_block_editor = false;

		/** This filter is documented in wp-admin/post.php */
		$replace_editor = apply_filters( 'replace_editor', false, $post );

		if ( ! $replace_editor ) {
			$is_block_editor = use_block_editor_for_post( $post );
		}

		return $is_block_editor;
	}


	public static function get_pattern_name_sans_namespace( $pattern_name ) {
		return preg_replace( '/^.+\//', '', $pattern_name ); // remove namespace
	}

	/**
	 * Add multiple filters to a closure
	 *
	 * @param $tags
	 * @param $function_to_add
	 * @param int $priority
	 * @param int $accepted_args
	 *
	 * @return bool true
	 */
	public static function add_filters($tags, $function_to_add, $priority = 10, $accepted_args = 1) {
		//If the filter names are not an array, create an array containing one item
		if(!is_array($tags))
			$tags = array($tags);

		//For each filter name
		foreach($tags as $index => $tag)
			add_filter($tag, $function_to_add, (int)(is_array($priority) ? $priority[$index] : $priority), (int)(is_array($accepted_args) ? $accepted_args[$index] : $accepted_args));

		return true;
	}


	/**
	 * Add multiple actions to a closure
	 *
	 * @param $tags
	 * @param $function_to_add
	 * @param int $priority
	 * @param int $accepted_args
	 *
	 * @return bool true
	 */
	public static function add_actions($tags, $function_to_add, $priority = 10, $accepted_args = 1) {
		//add_action() is just a wrapper around add_filter(), so we do the same
		return self::add_filters($tags, $function_to_add, $priority, $accepted_args);
	}


	/**
	 * Register a custom post type
	 *
	 * @param $post_type_name
	 * @param $labels
	 * @param $icon
	 * @param bool $free_license
	 *
	 * @return \WP_Error|\WP_Post_Type
	 */
	public static function register_post_type( $post_type_name, $labels, $icon ) {
		$is_preview = isset( $_GET['preview'] ) && $_GET['preview'] === 'true';

		$args = array(
			'labels'              => $labels,
			'supports'            => [
				'title',   // required, else our custom block name won't be available in REST
				'editor',
				'excerpt', // required, else our custom block description won't be available in REST
				'revisions',
				'author',
				'custom-fields',
			],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'menu_position'       => 65, // below plugins
			'menu_icon'           => $icon,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => is_admin() || $is_preview,
			// set to true to enable 'Preview in new tab' under 'Preview' in list table. False for front-end unless preview.
			'rewrite'             => false,
			'capability_type'     => array( $post_type_name, $post_type_name . 's' ),
			'map_meta_cap'        => true,
			'show_in_rest'        => true,
		);

		return register_post_type( $post_type_name, $args );
	}


	/**
	 * @param string $notice The notice to display
	 * @param string $type Either 'success', 'error', 'warning', 'info', default 'success'
	 * @param bool $is_dismissible Default true
	 */
	public static function add_admin_notice( $notice, $type = 'success', $is_dismissible = true ) {
		add_action( 'admin_notices', function () use ( $type, $notice, $is_dismissible ) {
			$is_dismissible_class = $is_dismissible ? 'is-dismissible' : '';
			echo "<div class='notice notice-{$type} {$is_dismissible_class}'>";
			echo "  <p>{$notice}</p>";
			echo "</div>";
		} );
	}


	/**
	 * By default any custom or registered pattern are considered active.
	 * Inactive patten names are stored in option 'blockmeister_inactive_patterns'
	 *
	 * @param $pattern
	 *
	 * @return bool
	 */
	public static function is_pattern_active( $pattern ) {
		$inactive_patterns = get_option( 'blockmeister_inactive_patterns', [] );

		return ! in_array( $pattern, $inactive_patterns, true );
	}

}
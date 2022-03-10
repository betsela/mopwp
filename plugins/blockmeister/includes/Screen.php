<?php

namespace ProDevign\BlockMeister;

class Screen {


	public static function is_blockmeister_pattern_list_table() {
		global $pagenow;

		$is_blockmeister_pattern_list_table =
			is_admin() &&
			isset( $pagenow ) && $pagenow === "edit.php" &&
			isset( $_GET['post_type'] ) && $_GET['post_type'] === "blockmeister_pattern";


		return $is_blockmeister_pattern_list_table;
	}

	public static function is_blockmeister_pattern_block_editor() {
		$is_blockmeister_pattern_block_editor = self::is_post_type_block_editor( 'blockmeister_pattern' );
		return $is_blockmeister_pattern_block_editor;
	}


	/**
	 * @return bool True if the current screen is a block editor for any other post type, other than 'blockmeister_pattern', else false.
	 */
	public static function is_other_post_type_block_editor() {
		$is_post_type_block_editor = self::is_post_type_block_editor();
		$is_not_blockmeister_pattern_post_type = ! isset( $_GET["post"] ) || $_GET["post"] !== 'blockmeister_pattern';
		$is_other_post_type_block_editor = $is_post_type_block_editor && $is_not_blockmeister_pattern_post_type;
		return $is_other_post_type_block_editor;
	}


	/**
	 * @param string $post_type Optional post_type to check for. Leave empty to check fro any post_type.
	 *
	 * @return bool True if the current screen is a block editor for any other post type, other than ' blockmeister_patter', else false.
	 */
	public static function is_post_type_block_editor( $post_type = '') {
		global $pagenow;

		if ( ! is_admin() || ! isset( $pagenow ) ) {
			return false;
		}

		$is_new_post_screen = $pagenow === "post-new.php";
		$is_edit_post_screen = $pagenow === "post.php" && isset( $_GET["action"] ) && $_GET['action'] === 'edit';

		if ( ! $is_new_post_screen && ! $is_edit_post_screen ) {
			return false;
		}

		$current_post_type = isset( $_GET["post"] ) ? get_post_type( $_GET["post"] ) : ( $is_new_post_screen ? 'post' : false ); // post-new.php defaults to 'post' for post qs param'

		if ( $current_post_type === false || ( $post_type !== '' && $current_post_type !== $post_type ) ) {
			return false;
		}

		$is_block_editor_enabled_for_current_post_type = self::use_block_editor_for_post_type( $current_post_type );

		return $is_block_editor_enabled_for_current_post_type;
	}

	public static function is_blockmeister_pattern_category_editor() {
		global $pagenow;

		$is_blockmeister_pattern_category_editor =
			is_admin() &&
			isset( $pagenow ) && $pagenow === "term.php" &&
			isset( $_GET["taxonomy"] ) && $_GET['taxonomy'] === 'pattern_category' &&
			isset( $_GET["post_type"] ) && $_GET['post_type'] === 'blockmeister_pattern';

		return $is_blockmeister_pattern_category_editor;
	}

	public static function is_block_editor() {
		global $current_screen;

		$is_block_editor = ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() );

		return $is_block_editor;
	}

	public static function is_blockmeister_pattern_category_list_table() {
		global $pagenow;

		$is_blockmeister_pattern_category_list_table =
			is_admin() &&
			isset( $pagenow ) && $pagenow === "edit-tags.php" &&
			isset( $_GET["taxonomy"] ) && $_GET['taxonomy'] === 'pattern_category' &&
			isset( $_GET["post_type"] ) && $_GET['post_type'] === 'blockmeister_pattern';

		return $is_blockmeister_pattern_category_list_table;
	}

	/**
	 * Return whether a post type is set to be edited with the block editor.
	 *
	 * The block editor depends on the REST API, and if the post type is not shown in the
	 * REST API, then it won't work with the block editor.
	 *
	 * Note: This method is a copy of post.php::use_block_editor_for_post_type.
	 * The reason we replicated it here is the original seems to be not
	 * yet loaded at the point we need it.
	 * Since WP 5.9 | Block Meister 3.0.4 this line has been replaced by a true return
	 *   else this was leading to not yet exising/loaded code:
	 *   apply_filters( 'use_block_editor_for_post_type', true, $post_type ); line
	 *
	 * @param string $post_type The post type.
	 * @return bool Whether the post type can be edited with the block editor.
	 */
	private static function use_block_editor_for_post_type( $post_type ) {

		if ( ! post_type_exists( $post_type ) ) {
			return false;
		}

		if ( ! post_type_supports( $post_type, 'editor' ) ) {
			return false;
		}

		$post_type_object = get_post_type_object( $post_type );
		if ( $post_type_object && ! $post_type_object->show_in_rest ) {
			return false;
		}

		return true; // note: in WP original code this was filtered but that lead to an error (cause core code was not yet loaded)
	}

}
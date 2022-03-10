<?php

namespace ProDevign\BlockMeister\Pattern_Builder;

use ProDevign\BlockMeister\Screen;
use WP_Term;

/**
 * Defines and registers Custom taxonomy for post type: 'blockmeister_pattern'
 */
class BlockMeister_Pattern_Category_Taxonomy {

	const TAXONOMY_NAME = 'pattern_category';
	private $is_synced = false;

	public function __construct() {
	}

	public function init() {
		// Init with a high prio so other plugins/theme can register there block pattern categories first.
		// Note: if in future a even higher prio is set, remember to increase the prio of Custom_Block_Pattern_Registry->init() too!
		add_action( 'init', [ $this, 'register_blockmeister_pattern_category_taxonomy' ], 1001 );
		add_action( 'init', [ $this, 'synchronize_pattern_category_terms_with_registered_pattern_categories' ], 1001 );
		//add_action( 'current_screen', [ $this, 'synchronize_pattern_category_terms_with_registered_pattern_categories' ], 1001 ); // to catch remote loaded ,org patterns = problematic timing for other hooks
		add_action( 'create_term', [ $this, 'on_create_term_set_source' ], 10, 3 );
		add_filter( "get_pattern_category", [ $this, 'filter_term_name' ] );
		add_filter( 'pattern_category_row_actions', [ $this, 'allow_row_actions_only_for_user_added_term' ], 10, 2 );
		add_filter( 'get_edit_term_link', [ $this, 'get_edit_term_link_filter' ], 10, 3 );
		add_filter( 'manage_edit-pattern_category_columns', [ $this, 'manage_pattern_category_columns_filter' ] );
		add_action( 'manage_pattern_category_custom_column', [ $this, 'render_source_column' ], 10, 3 );
		add_filter( 'map_meta_cap', [ $this, 'allow_delete_only_for_user_added_terms' ], 10, 4 );
		add_action( 'admin_head', [ $this, 'hide_term_parent_input_via_css' ] );
	}

	public function register_blockmeister_pattern_category_taxonomy() {

		$capabilities = [
			'manage_terms' => 'manage_blockmeister_pattern_category',
			'edit_terms'   => 'manage_blockmeister_pattern_category',
			'delete_terms' => 'manage_blockmeister_pattern_category',
			'assign_terms' => 'edit_blockmeister_patterns',
		];

		$args = array(
			'default_term'      => [
				'name'        => esc_html__( 'Default', 'blockmeister' ),
				// will be set after registration, this is to prevent multiple terms when site name is changed
				'slug'        => 'default',
				'description' => esc_html__( 'Default category', 'blockmeister' ),
			], // see: https://core.trac.wordpress.org/ticket/43517
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
			'show_in_rest'      => true,
			'capabilities'      => $capabilities,
		);

		register_taxonomy( self::TAXONOMY_NAME, 'blockmeister_pattern', $args );

		// set current site name as name for the default term. This handles also when the site name is changed by the user.
		$site_name    = esc_html( get_bloginfo( 'name' ) );
		$default_term = get_term_by( 'slug', 'default', self::TAXONOMY_NAME );

		if ( $site_name !== $default_term->name) {
			wp_update_term( $default_term->term_id, self::TAXONOMY_NAME, [	'name' => $site_name ] );
		}

	}

	/**
	 * Synchronizes categories registered by core/theme/plugins and user added (custom categories)
	 */
	public function synchronize_pattern_category_terms_with_registered_pattern_categories() {

		if ( ! $is_applicable_screen = Screen::is_blockmeister_pattern_category_list_table() ||
		                               Screen::is_blockmeister_pattern_list_table() ||
		                               Screen::is_post_type_block_editor() ) {
			return;
		}

		$wp_pattern_category_registry  = \WP_Block_Pattern_Categories_Registry::get_instance();
		$registered_pattern_categories = $wp_pattern_category_registry->get_all_registered();

		$pattern_category_terms = get_terms( array(
			'taxonomy'   => self::TAXONOMY_NAME,
			'hide_empty' => false,
		) );


		// Get all pattern categories registered with register_block_pattern_category() by
		// core, plugins or themes and make sure they are added as pattern category terms
		foreach ( $registered_pattern_categories as $registered_category ) {
			$is_none_existing_term = ! term_exists( $registered_category['name'], 'pattern_category' );
			if ( $is_none_existing_term ) { // add new
				$inserted_term = wp_insert_term(
					$registered_category['label'],
					self::TAXONOMY_NAME,
					[ 'slug' => sanitize_title( $registered_category['name'] ), ]
				);
				add_term_meta( $inserted_term['term_id'], 'source', 'site', true );
			}
		}

		// Remove all terms sourced by site (core/theme/plugin) that are no longer being registered by core/theme/plugin
		// This auto cleans the taxonomy e.g after the admin user switches themes or removes plugins.
		foreach ( $pattern_category_terms as $pattern_category_term ) {
			$source = get_term_meta( $pattern_category_term->term_id, 'source', true );
			if ( $source === 'site' ) {
				// look term slug up in $registered_pattern_categories
				$is_term_still_being_registered_by_site_source = false;
				foreach ( $registered_pattern_categories as $registered_pattern_category ) {
					if ( $registered_pattern_category['name'] === $pattern_category_term->slug ) {
						$is_term_still_being_registered_by_site_source = true;
						break;
					}
				};
				if ( ! $is_term_still_being_registered_by_site_source ) { // remove term
					wp_delete_term( $pattern_category_term->term_id, self::TAXONOMY_NAME );
				}
			}
		}

		// Register (using register_block_pattern_category) all custom pattern_category terms
		foreach ( $pattern_category_terms as $pattern_category_term ) {
			if ( ! $wp_pattern_category_registry->is_registered( $pattern_category_term->slug ) ) {
				register_block_pattern_category(
					sanitize_title( $pattern_category_term->slug ),
					[ 'label' => sanitize_textarea_field( $pattern_category_term->name ) ]
				);
				$source = $pattern_category_term->slug === 'default' ? "blockmeister" : "user";
				add_term_meta( $pattern_category_term->term_id, 'source', $source, true );
			}
		}

		$this->is_synced = true;
	}


	/**
	 * Add source (=user) meta to term.
	 * Note: this action is only run after synchronization has been done.
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	public function on_create_term_set_source( $term_id, $tt_id, $taxonomy ) {
		if ( $taxonomy === self::TAXONOMY_NAME && $this->is_synced ) {
			add_term_meta( $term_id, 'source', 'user', true );
		}
	}

	/**
	 * Filters a blockmeister_pattern category taxonomy term object.
	 * This is only done for terms that originate from registered patters from core, themes and third party plugins.
	 * Those pattern categories may come with translations. This filter handles those translations, if applicable.
	 *
	 *
	 * @param WP_Term $pattern_category_term Term object.
	 *
	 * @return WP_Term
	 */
	public function filter_term_name( WP_Term $pattern_category_term ) {
		$wp_pattern_category_registry = \WP_Block_Pattern_Categories_Registry::get_instance();
		if ( $wp_pattern_category_registry->is_registered( $pattern_category_term->slug ) ) {
			$registered_category = $wp_pattern_category_registry->get_registered( $pattern_category_term->slug );
			if ( $pattern_category_term->name !== $registered_category['label'] ) {
				$pattern_category_term->name = $registered_category['label'];
			}
		}

		return $pattern_category_term;
	}


	/**
	 * Filters the action links displayed for each term in the terms list table.
	 * If the source of a term is not 'user' then the the row actions wil be removed.
	 *
	 * @param string[] $actions An array of action links to be displayed. Default
	 *                          'Edit', 'Quick Edit', 'Delete', and 'View'.
	 * @param WP_Term $tag Term object.
	 *
	 * @return array|string[]
	 */
	public function allow_row_actions_only_for_user_added_term( $actions, $tag ) {

		$term_meta_source = get_term_meta( $tag->term_id, 'source', true );
		if ( $term_meta_source !== 'user' ) {
			$actions = [ esc_html__( 'Not editable', 'blockmeister' ) ];
		}

		return $actions;
	}

	/**
	 * Filters the edit link for a term.
	 * If the source of a term is not 'user' then the edit link is removed.
	 *
	 * @param string $location The edit link.
	 * @param int $term_id Term ID.
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return string
	 */
	public function get_edit_term_link_filter( $location, $term_id, $taxonomy ) {
		if ( $taxonomy === self::TAXONOMY_NAME ) {
			$term_meta_source = get_term_meta( $term_id, 'source', true );
			if ( $term_meta_source !== 'user' ) {
				$location = "";
			}
		}

		return $location;
	}


	/**
	 * Filters the columns.
	 *
	 * @param $columns
	 *
	 * @return array $columns (sans the posts (count) columns
	 */
	public function manage_pattern_category_columns_filter( $columns ) {
		$posts = $columns['posts'];
		unset( $columns['posts'] );
		$columns['source'] = esc_html__( 'Source', 'blockmeister' );
		$columns['posts']  = $posts;

		return $columns;
	}


	public function render_source_column( $post_id, $column, $term_id ) {
		if ( $column === 'source' ) {
			$term_meta_source = get_term_meta( $term_id, 'source', true );
			switch ( $term_meta_source ) {
				case 'blockmeister':
					$source = "BlockMeister";
					break;
				case 'user':
					$source = esc_html__( 'User', 'blockmeister' );
					break;
				case 'site':
					$source = esc_html__( 'WordPress/Theme/Plugin', 'blockmeister' );
					break;
				default:
					$source = esc_html__( 'Unknown', 'blockmeister' );
			}
			echo $source;
		}
	}


	/**
	 * Filters a user's capabilities depending on specific context and/or privilege.
	 * If the term has its meta field 'source' set to 'user', then allow else disallow.
	 *
	 * @param string[] $caps Array of the user's capabilities.
	 * @param string $cap Capability name.
	 * @param int $user_id The user ID.
	 * @param array $args Adds the context to the cap. Typically the object ID.
	 *
	 * @return string[] $caps
	 */
	public function allow_delete_only_for_user_added_terms( $caps, $cap, $user_id, $args ) { //allow_delete_term_filter
		if ( $cap === 'delete_term' && $args ) {
			$term_id = $args[0];
			$term    = get_term( $term_id );
			if ( $term->taxonomy === 'pattern_category' ) {
				$term_meta_source = get_term_meta( $term_id, 'source', true );
				if ( $term_meta_source !== 'user' ) {
					$caps = [ 'do_not_allow' ];
				}
			}
		}

		return $caps;
	}

	public function hide_term_parent_input_via_css() {
		echo "<style>" .
		     "  .taxonomy-pattern_category .form-field.term-parent-wrap { display: none; }" .
		     "</style>";
	}

}

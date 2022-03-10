<?php

namespace ProDevign\BlockMeister\Pattern_Builder;

use  ProDevign\BlockMeister\Utils ;
use function  ProDevign\BlockMeister\blockmeister_license ;
/**
 * Defines and registers Custom post type: 'blockmeister_pattern'
 */
class BlockMeister_Pattern_Post_Type
{
    const  POST_TYPE_NAME = 'blockmeister_pattern' ;
    /**
     * BlockMeister_Block_Post_Type constructor.
     */
    public function __construct()
    {
    }
    
    public function init()
    {
        add_action( 'init', [ $this, 'register_blockmeister_pattern_post_types' ] );
        add_filter( 'wp_' . self::POST_TYPE_NAME . '_revisions_to_keep', [ $this, 'revisions_to_keep_filter' ] );
    }
    
    public function revisions_to_keep_filter()
    {
        $blockmeister_block_pattern_revisions = (int) get_option( 'blockmeister_block_pattern_revisions', 0 );
        return $blockmeister_block_pattern_revisions;
    }
    
    /**
     * Register our custom post types
     *
     * @return void
     */
    public function register_blockmeister_pattern_post_types()
    {
        // Note: since Gutenberg 8.7 and WP 5.6 the 'singular_name' label is also used for the Tab label in the inspector!
        //       In earlier versions that had a fixed name of 'Document'
        $labels = [
            'name'                     => esc_html__( 'Patterns', 'blockmeister' ),
            'singular_name'            => esc_html__( 'Pattern', 'blockmeister' ),
            'add_new'                  => esc_html__( 'Add New', 'blockmeister' ),
            'add_new_item'             => esc_html__( 'Add New Pattern', 'blockmeister' ),
            'edit_item'                => esc_html__( 'Edit Pattern', 'blockmeister' ),
            'new_item'                 => esc_html__( 'New Pattern', 'blockmeister' ),
            'view_item'                => esc_html__( 'View Pattern', 'blockmeister' ),
            'view_items'               => esc_html__( 'View Patterns', 'blockmeister' ),
            'search_items'             => esc_html__( 'Search Patterns', 'blockmeister' ),
            'not_found'                => esc_html__( 'No pattern found.', 'blockmeister' ),
            'not_found_in_trash'       => esc_html__( 'No patterns found in Trash.', 'blockmeister' ),
            'all_items'                => esc_html__( 'All Patterns', 'blockmeister' ),
            'archives'                 => esc_html__( 'Pattern Archives', 'blockmeister' ),
            'attributes'               => esc_html__( 'Pattern Attributes', 'blockmeister' ),
            'insert_into_item'         => esc_html__( 'Insert into pattern', 'blockmeister' ),
            'uploaded_to_this_item'    => esc_html__( 'Uploaded to this pattern', 'blockmeister' ),
            'filter_items_list'        => esc_html__( 'Filter pattern list', 'blockmeister' ),
            'items_list_navigation'    => esc_html__( 'Patterns list navigation', 'blockmeister' ),
            'items_list'               => esc_html__( 'Patterns list', 'blockmeister' ),
            'item_published'           => esc_html__( 'Pattern published.', 'blockmeister' ),
            'item_published_privately' => esc_html__( 'Pattern published privately.', 'blockmeister' ),
            'item_reverted_to_draft'   => esc_html__( 'Pattern reverted to draft.', 'blockmeister' ),
            'item_scheduled'           => esc_html__( 'Pattern scheduled.', 'blockmeister' ),
            'item_updated'             => esc_html__( 'Pattern updated.', 'blockmeister' ),
            'menu_name'                => esc_html__( 'Block Patterns', 'blockmeister' ),
            'name_admin_bar'           => esc_html__( 'Block Pattern', 'blockmeister' ),
        ];
        Utils::register_post_type(
            self::POST_TYPE_NAME,
            $labels,
            'dashicons-screenoptions',
            true
        );
    }

}
<?php

namespace ProDevign\BlockMeister\Pattern_Builder;

use  ProDevign\BlockMeister\Utils ;
use function  ProDevign\BlockMeister\blockmeister_license ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
final class Pattern_Builder
{
    const  POST_TYPE = 'blockmeister_pattern' ;
    /**
     * @var Pattern_Builder
     */
    private static  $instance = null ;
    /**
     * Constructor for the Pattern_Builder class
     */
    private function __construct()
    {
    }
    
    /**
     * @return Pattern_Builder
     */
    public static function get_instance()
    {
        return Pattern_Builder::$instance;
    }
    
    /**
     * Runs (and initializes) Pattern_Builder
     *
     * @return void
     */
    public static function init()
    {
        
        if ( is_null( Pattern_Builder::$instance ) ) {
            $pattern_builder = Pattern_Builder::$instance = new Pattern_Builder();
            $is_backend = is_admin();
            $is_frontend = !is_admin();
            // Init context specific classes, based on request type
            
            if ( $is_backend ) {
                $pattern_builder->init_admin();
            } elseif ( defined( 'DOING_AJAX' ) ) {
                $pattern_builder->init_ajax();
            } elseif ( defined( 'REST_REQUEST' ) ) {
                $pattern_builder->init_rest();
            } elseif ( $is_frontend ) {
                $pattern_builder->init_frontend();
            }
        
        }
    
    }
    
    private function init_admin()
    {
        ( new Assets() )->init();
        ( new BlockMeister_Pattern_Post_Type() )->init();
        ( new BlockMeister_Pattern_Category_Taxonomy() )->init();
        ( new BlockMeister_Pattern_Keywords_Taxonomy() )->init();
        ( new BlockMeister_Pattern_Post_Meta_Fields() )->init();
        ( new Admin\Block_Pattern_Registry() )->init();
        ( new Admin\BlockMeister_Pattern_List_Table() )->init();
        ( new Admin\BlockMeister_Pattern_Editor() )->init();
        ( new Admin\BlockMeister_Settings() )->init();
        //blockmeister_license()->add_filter( 'show_admin_notice', [ $this, 'show_admin_notice_only_in_blockmeister_context_filter' ], 10, 2 );
    }
    
    private function init_frontend()
    {
        ( new BlockMeister_Pattern_Post_Type() )->init();
        ( new BlockMeister_Pattern_Post_Meta_Fields() )->init();
        ( new BlockMeister_Pattern_Category_Taxonomy() )->init();
        ( new BlockMeister_Pattern_Keywords_Taxonomy() )->init();
    }
    
    private function init_ajax()
    {
    }
    
    private function init_rest()
    {
        ( new BlockMeister_Pattern_Post_Type() )->init();
        ( new BlockMeister_Pattern_Category_Taxonomy() )->init();
        ( new BlockMeister_Pattern_Keywords_Taxonomy() )->init();
        ( new BlockMeister_Pattern_Post_Meta_Fields() )->init();
    }
    
    /**
     * Filter makes sure the Freemius admin notices are only shown on:
     * - the blockmeister_pattern table list screen
     * - the plugins table list screen.
     *
     * @param bool  $show
     * @param array $msg {
     *     @var string $message The actual message.
     *     @var string $title An optional message title.
     *     @var string $type The type of the message ('success', 'update', 'warning', 'promotion').
     *     @var string $id The unique identifier of the message.
     *     @var string $manager_id The unique identifier of the notices manager. For plugins it would be the plugin's slug, for themes - `<slug>-theme`.
     *     @var string $plugin The product's title.
     *     @var string $wp_user_id An optional WP user ID that this admin notice is for.
     * }
     * @return bool
     */
    public function show_admin_notice_only_in_blockmeister_context_filter( $show, $msg )
    {
        global  $plugin_page, $pagenow ;
        $is_blockmeister_pattern_list_table_screen = $pagenow && $pagenow === 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === Pattern_Builder::POST_TYPE && $plugin_page !== "Settings";
        $in_context = $pagenow && $pagenow === 'plugins.php' || $is_blockmeister_pattern_list_table_screen;
        if ( !$in_context ) {
            $show = false;
        }
        return $show;
    }

}
// Pattern_Builder
<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://torbara.com
 * @since      1.3.0
 *
 * @package    Ang_Timeline
 * @subpackage Ang_Timeline/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.3.0
 * @package    Ang_Timeline
 * @subpackage Ang_Timeline/includes
 * @author     Aleksandr Glovatskyy <aleksand1278@gmail.com>
 */
class Ang_Timeline {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      Ang_Timeline_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.3.0
	 */
	public function __construct() {

		$this->plugin_name = 'ang-timeline';
		$this->version = '1.3.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ang_Timeline_Loader. Orchestrates the hooks of the plugin.
	 * - Ang_Timeline_i18n. Defines internationalization functionality.
	 * - Ang_Timeline_Admin. Defines all hooks for the admin area.
	 * - Ang_Timeline_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ang-timeline-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ang-timeline-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ang-timeline-admin.php';
                
        /**
         * The class responsible for timeline custom post type
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ang-timeline-post-type.php';
        
        /**
         * The class responsible for widgets of this plugin
         */
        foreach ( glob( plugin_dir_path( dirname( __FILE__ ) ). 'public/widgets/*.php' ) as $widget ){ require_once $widget; } //include widget files
        
        
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ang-timeline-public.php';

		$this->loader = new Ang_Timeline_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ang_Timeline_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ang_Timeline_i18n();
            $plugin_i18n->set_domain( 'ang-timeline' ); //ang
                
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Ang_Timeline_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        // Timeline custom post type
        $timeline_post_type = new Ang_Timeline_Post_Type();

        $this->loader->add_action( 'init', $timeline_post_type, 'register_timeline_post_type' );
        $this->loader->add_action( 'init', $timeline_post_type, 'register_timeline_event_taxonomy' );
        
        /*
         * add theme support post thumbnails in timeline listing for admin
         */
        if ( is_admin() ) {
            global $pagenow;
            if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && esc_attr( $_GET['post_type'] ) == $timeline_post_type->post_type_name ) {
                $this->loader->add_filter( 'manage_edit-' . $timeline_post_type->post_type_name . '_sortable_columns', $timeline_post_type, 'register_custom_sortable_column_headings' );
                $this->loader->add_filter( 'manage_edit-' . $timeline_post_type->post_type_name . '_columns', $timeline_post_type, 'register_custom_column_headings' );
                $this->loader->add_action( 'manage_' . $timeline_post_type->post_type_name . '_posts_custom_column', $timeline_post_type, 'register_custom_column' );
            }
        }
        $this->loader->add_action( 'admin_menu', $timeline_post_type, 'add_timeline_meta_box' );
        $this->loader->add_action( 'save_post', $timeline_post_type, 'save_timeline_meta_box' );
        
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Ang_Timeline_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
                
                // register widget
                $this->loader->add_action('widgets_init', $plugin_public, 'register_timeline_widgets' );
                
// not needed for now, will enable in future version
        // $this->loader->add_action( 'init', $plugin_public, 'register_timeline_shortcodes' );
         //$this->loader->add_filter( 'ang_timeline_events', $plugin_public, 'ang_timeline_event_terms' );
        
// not needed for now
// ang
//         if ( class_exists('Vc_Manager') ) {
//             $this->loader->add_action( 'vc_before_init', $plugin_public, 'integrate_shortcode_with_vc' );
//         }
        
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.3.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.3.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.3.0
	 * @return    Ang_Timeline_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.3.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

    /**
     * To log any thing for debugging purposes
     *
     * @since   1.3.0
     *
     * @param   mixed   $message    message to be logged
     */
    public static function log( $message ) {
        if( WP_DEBUG === true ){
            if( is_array( $message ) || is_object( $message ) ){
                error_log( print_r( $message, true ) );
            } else {
                error_log( $message );
            }
        }
    } //ang
    
}

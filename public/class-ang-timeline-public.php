<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://torbara.com
 * @since      1.3.0
 *
 * @package    Ang_Timeline
 * @subpackage Ang_Timeline/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ang_Timeline
 * @subpackage Ang_Timeline/public
 * @author     Aleksandr Glovatskyy <aleksand1278@gmail.com>
 */
class Ang_Timeline_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.3.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.3.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ang_Timeline_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ang_Timeline_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ang-timeline-public.css', array(), $this->version, 'all' );
		//wp_enqueue_style( 'warp-css-uikit', plugin_dir_url( __FILE__ ) . 'css/uikit.warp.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.3.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ang_Timeline_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ang_Timeline_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
         // not needed for now    
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ang-timeline-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'uikit', plugin_dir_url( __FILE__ ) . 'js/uikit.js', array( 'jquery' ), $this->version, true );

	}
        
    /**
    * Register timeline widgets
    *
    * @since   1.3.0
    */
    public function register_timeline_widgets() {
        register_widget( 'ANG_Timeline_Vertical' );
    }
    
   /**
    * Register timeline shortcodes
    *
    * @since   1.3.0
    */
    public function register_timeline_shortcodes() {
        add_shortcode( 'ang_timeline', array( $this, 'display_ang_timeline_list') );
    }
    
    /**
     * Display timeline in a list
     *
     * @since   1.3.0
     * @param   array   $attr     Array of attributes
     * @return  string  generated html by shortcode
     */
    public function display_ang_timeline_list( $attr ) {
        
    }
    
    
    /**
     * Used in the widgets by appending the registered image sizes
     *
     * @since 1.0
     */
    
    public function ang_get_thumbnail_sizes() {
            global $_wp_additional_image_sizes;
            $sizes = array();
            foreach( get_intermediate_image_sizes() as $s ) {
                    $sizes[ $s ] = array( 0, 0 );
                    if( in_array( $s, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
                            $sizes[ $s ][0] = get_option( $s . '_size_w' );
                            $sizes[ $s ][1] = get_option( $s . '_size_h' );
                            $sizes[ $s ]['crop'] = get_option( $s . '_crop' ) ? 'crop' : 'no-crop';
                    } else {
                            if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
                                    $sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'], );
                            }
                    }
            }
            return $sizes;
    }


    /**
     * Integrate shortcode with Visual Composer
     *
     * @since   1.0.1
     */
    public function ang_timeline_event_terms( $events_array ) {

        // timeline event
        $timeline_events_array = array();
        $timeline_events = get_terms( 'event' );
        if ( ! empty( $timeline_events ) && ! is_wp_error( $timeline_events ) ){
            foreach ( $timeline_events as $timeline_event ) {
                $timeline_events_array[ $timeline_event->name ] = $timeline_event->slug;
            }
        }

        return $timeline_events_array;
    }
    
}

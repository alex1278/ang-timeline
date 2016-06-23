<?php


/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://torbara.com
 * @since      1.3.0
 *
 * @package    Ang_Timeline
 * @subpackage Ang_Timeline/admin
 */

/**
 * Timeline custom post type class.
 *
 * @package    Ang_Timeline
 * @subpackage Ang_Timeline/admin
 * @author     Aleksandr Glovatskyy <aleksand1278@gmail.com>
 */
class Ang_Timeline_Post_Type {
    public $post_type_name;

    /**
     * public constructor function.
     *
     * @since   1.3.0
     */
    public function __construct() {
        $this->post_type_name = 'timeline';
    }


    /**
     * Register Timeline custom post type
     *
     * @since     1.3.0
     */
    
    public function register_timeline_post_type() {

	$labels = array(
		'name'                  => _x( 'Timelines', 'Post Type General Name', 'ang-timeline' ),
		'singular_name'         => _x( 'Timeline', 'Post Type Singular Name', 'ang-timeline' ),
		'menu_name'             => __( 'Timeline', 'ang-timeline' ),
		'name_admin_bar'        => __( 'Timeline', 'ang-timeline' ),
		'parent_item_colon'     => __( 'Parent Timeline:', 'ang-timeline' ),
		'all_items'             => __( 'All Timelines', 'ang-timeline' ),
		'add_new_item'          => __( 'Add New Timeline', 'ang-timeline' ),
		'add_new'               => __( 'Add New', 'ang-timeline' ),
		'new_item'              => __( 'Timeline', 'ang-timeline' ),
		'edit_item'             => __( 'Edit Timeline', 'ang-timeline' ),
		'update_item'           => __( 'Update Timeline', 'ang-timeline' ),
		'view_item'             => __( 'View Timeline', 'ang-timeline' ),
		'search_items'          => __( 'Search Timeline', 'ang-timeline' ),
		'not_found'             => __( 'Timeline Not found', 'ang-timeline' ),
		'not_found_in_trash'    => __( 'Timeline Not found in Trash', 'ang-timeline' ),
		'items_list'            => __( 'Timelines list', 'ang-timeline' ),
		'items_list_navigation' => __( 'Timelines list navigation', 'ang-timeline' ),
		'filter_items_list'     => __( 'Filter Timelines list', 'ang-timeline' ),
	);
	$args = array(
		'label'                 => __( 'Timeline', 'ang-timeline' ),
		'description'           => __( 'Custom Timeline', 'ang-timeline' ),
		'labels'                => apply_filters( 'ang_timeline_labels', $labels),
                'supports'              => apply_filters( 'ang_timeline_supports', array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments' ) ),
                'taxonomies'            => array( 'event' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 12,
                'menu_icon'             => 'dashicons-chart-line',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
        
        register_post_type( $this->post_type_name, apply_filters( 'register_ang_timeline_arguments', $args) );
    
    }

    /**
     * Register Timeline Event custom taxonomy
     *
     * @since     1.3.0
     */
    public function register_timeline_event_taxonomy() {
        
        $labels = array(
		'name'                       => _x( 'Events', 'Taxonomy General Name', 'ang-timeline' ),
		'singular_name'              => _x( 'Event', 'Taxonomy Singular Name', 'ang-timeline' ),
		'menu_name'                  => __( 'Events', 'ang-timeline' ),
		'all_items'                  => __( 'All Events', 'ang-timeline' ),
		'parent_item'                => __( 'Parent Event', 'ang-timeline' ),
		'parent_item_colon'          => __( 'Parent Event:', 'ang-timeline' ),
		'new_item_name'              => __( 'New Event Name', 'ang-timeline' ),
		'add_new_item'               => __( 'Add New Event', 'ang-timeline' ),
		'edit_item'                  => __( 'Edit Event', 'ang-timeline' ),
		'update_item'                => __( 'Update Event', 'ang-timeline' ),
		'view_item'                  => __( 'View Event', 'ang-timeline' ),
		'separate_items_with_commas' => __( 'Separate Events with commas', 'ang-timeline' ),
		'add_or_remove_items'        => __( 'Add or remove Events ', 'ang-timeline' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ang-timeline' ),
		'popular_items'              => __( 'Popular Events', 'ang-timeline' ),
		'search_items'               => __( 'Search Events', 'ang-timeline' ),
		'not_found'                  => __( 'Not Found', 'ang-timeline' ),
		'items_list'                 => __( 'Events list', 'ang-timeline' ),
		'items_list_navigation'      => __( 'Events list navigation', 'ang-timeline' ),
	);
	$args = array(
		'labels'                     => apply_filters( 'ang_timeline_event_labels', $labels ),
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
        
        register_taxonomy( 'event', array( $this->post_type_name ), apply_filters( 'register_ang_timeline_event_arguments', $args ) );

    }

    /**
     * Add custom column headings for timeline image
     * Add theme support post thumbnails in timeline listing for admin
     *
     * @param   array   $defaults
     * @since   1.3.0
     * @return  array   $defaults
     */
    
    public function register_custom_column_headings($default1) {
        $default1['post_thumbnails'] = __('Image ', 'ang-timeline' );
        return $default1;
    }

    // sortable columns
    public function register_custom_sortable_column_headings($sortable_columns){
        $sortable_columns['post_thumbnails'] = __('Image ', 'ang-timeline' );
        return $sortable_columns;
    }
    /**
     * Register custom column for image.
     *
     * @access  public
     * @param   string $column_name
     * @since   1.3.0
     * @return  void
     */
    
    //image size
    public function register_custom_column($row_label) {
        if ($row_label === 'post_thumbnails') :
            print the_post_thumbnail(array(85,85));
        endif;
    }
   
    /**
     * Add meta box to collect timeline meta information
     *
     * @access public
     * @since  1.3.0
     * @return void
     */
    public function add_timeline_meta_box () {
        add_meta_box( 'timeline', __( 'Timeline', 'ang-timeline' ), array( $this, 'generate_meta_box' ), $this->post_type_name, 'normal', 'high' );
    }

    /**
     * Generate meta box markup on admin side
     *
     * @access public
     * @since  1.3.0
     * @return void
     */
    public function generate_meta_box () {
        global $post_id;
        $fields = get_post_custom( $post_id );
        $field_data = $this->get_timeline_fields_settings();

        $html = '';

        $html .= '<input type="hidden" name="ang_' . $this->post_type_name . '_nonce" id="ang_' . $this->post_type_name . '_nonce" value="' . wp_create_nonce( ANG_TIMELINE_BASE ) . '" />';

        if ( 0 < count( $field_data ) ) {
            $html .= '<table class="form-table">' . "\n";
            $html .= '<tbody>' . "\n";

            foreach ( $field_data as $k => $v ) {
                $data = $v['default'];
                if ( isset( $fields['_' . $k] ) && isset( $fields['_' . $k][0] ) ) {
                    $data = $fields['_' . $k][0];
                }

                $html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="text" id="' . esc_attr( $k ) . '" class="regular-text" value="' . esc_attr( $data ) . '" />' . "\n";
                $html .= '<p class="description"><i>' . $v['description'] . '</i></p>' . "\n";
                $html .= '</td><tr/>' . "\n";
            }

            $html .= '</tbody>' . "\n";
            $html .= '</table>' . "\n";
        }

        echo $html;
    }

    /**
     * Save timeline meta box
     *
     * @access public
     * @since  1.3.0
     * @param int $post_id
     * @return int/void
     */
    public function save_timeline_meta_box ( $post_id ) {

        // Verify
        if ( ( get_post_type() != $this->post_type_name ) || ! wp_verify_nonce( $_POST['ang_' . $this->post_type_name . '_nonce'], ANG_TIMELINE_BASE ) ) {
            return $post_id;
        }

        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        // Check, if autosave -> do noting with data of our form.
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            } //ang
        

        $field_data = $this->get_timeline_fields_settings();
        $field_keys = array_keys( $field_data );

        foreach ( $field_keys as $f ) {

            ${$f} = strip_tags( trim( $_POST[$f] ) );

            // Escape the URLs.
            if ( 'url' == $field_data[$f]['type'] ) {
                ${$f} = esc_url( ${$f} );
            }

            // update database
            if ( get_post_meta( $post_id, '_' . $f ) == '' ) {
                // add
                add_post_meta( $post_id, '_' . $f, ${$f}, true );
            } else if( ${$f} != get_post_meta( $post_id, '_' . $f, true ) ) {
                // update
                update_post_meta( $post_id, '_' . $f, ${$f} );
            } else if ( ${$f} == '' ) {
                // delete
                delete_post_meta( $post_id, '_' . $f, get_post_meta( $post_id, '_' . $f, true ) );
            }
        }
    }

    /**
     * Get the settings for timeline custom fields.
     * @since  1.3.0
     * @return array
     */
    public function get_timeline_fields_settings () {
        $fields = array();

        $fields['timeline'] = array(
            'name' => __( 'Timeline value', 'ang-timeline' ),
            'description' => __( 'Provide a date, year or a phrase for time tree identification (for example: "22 june, 2011" or "New IT department opened").', 'ang-timeline' ),
            'type' => 'text',
            'default' => '',
            'section' => 'info'
        );

        return $fields;
    }

}
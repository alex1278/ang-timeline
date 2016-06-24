<?php
/*
  Plugin Name: ANG Timeline widget
  Plugin URI: https://github.com/alex1278/ang-timelime
  Description: Timeline tree for your posts
  Author: Aleksandr Glovatskyy
  Version: 1.3.0
  Date: 20.09.2015
  Author URI: Author URI: http://torbara.com
 */

class ANG_Timeline_Vertical extends WP_Widget {
    
    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'ANG-Timeline', // Base ID
            __('ANG Timeline', 'ang-timeline'), // Name
            array( 'description' => __( 'Displays posts in Timeline mode', 'ang-timeline' ), ) // Args
        );
    }
    
    function form($instance) { ?>

        <!--        Widget title -->
        
        <?php $title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : __('Timeline', 'ang-timeline'); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('Title:', 'ang-timeline'); ?>
                <input class="widefat" 
                        id="<?php echo $this->get_field_id('title'); ?>" 
                        name="<?php echo $this->get_field_name('title'); ?>" 
                        type="text" 
                        value="<?php echo $title; ?>" />
            </label>
        </p>
        
        <!--        Description textarea -->
        
        <?php $descr = isset( $instance['descr']) ?  $instance['descr'] : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id('descr'); ?>">
                <?php _e('Description:', 'ang-timeline'); ?>
                <textarea class="widefat" rows="5" cols="10" 
                        id="<?php echo $this->get_field_id('descr'); ?>" 
                        name="<?php echo $this->get_field_name('descr'); ?>"><?php echo $descr; ?></textarea>
            </label>
        </p>
        
        <!--   ADD Extra class -->
        
        <?php $extra_class = isset( $instance['extra_class']) ? esc_attr( $instance['extra_class'] ) : ''; ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('extra_class')); ?>">
                <?php _e('Extra class:', 'ang-timeline'); ?>
                <input  
                        id="<?php echo esc_attr($this->get_field_id('extra_class')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('extra_class')); ?>" 
                        type="text" 
                        value="<?php echo esc_attr($extra_class); ?>" />
            </label>
        </p>
        
<!--     Returns all registered post types-->
            
    <?php $p_post_type = isset( $instance[ 'p_post_type' ] ) ? $instance[ 'p_post_type' ] : 'timeline'; ?>
        <p>
            <label for="<?php echo $this->get_field_id('p_post_type'); ?>">
                <br>
                <?php _e('Select post type:', 'ang-timeline'); ?>
                    <?php
                    $args=array(
                                'public'   => true,
                              );
                $post_types = get_post_types($args,'names'); 
                
                ?><select class="widefat" 
                          id="<?php echo esc_attr($this->get_field_id('p_post_type')); ?>" 
                          name="<?php echo esc_attr($this->get_field_name('p_post_type')); ?>" ><?php
                foreach ($post_types as $post_type){
                    ?><option value="<?php echo esc_attr($post_type); ?>" <?php if($post_type==$p_post_type){echo 'selected=""';} ?>><?php echo $post_type; ?></option><?php
                }
                ?>
                </select>
            </label>
        </p>
        
        
        <!--             Returns  timeline taxonomy -->
        
        <?php $TaxEvent = isset( $instance[ 'TaxEvent' ] ) ? $instance[ 'TaxEvent' ] : '';
        
                $args = array(
                    'type'                     => 'timeline',
                    'child_of'                 => 0,
                    'parent'                   => '',
                    'orderby'                  => 'name',
                    'order'                    => 'ASC',
                    'hide_empty'               => 1,
                    'hierarchical'             => 1,
                    'exclude'                  => '',
                    'include'                  => '',
                    'number'                   => '',
                    'taxonomy'                 => 'event',
                    'pad_counts'               => false 
                );
                
        $tax_events = get_categories( $args );
            if( $tax_events ){
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('TaxEvent'); ?>">
                <?php _e('Timeline tax (if timeline type selected):', 'ang-timeline'); ?>
                    <select class="widefat" 
                          id="<?php echo esc_attr($this->get_field_id('TaxEvent')); ?>" 
                          name="<?php echo esc_attr($this->get_field_name('TaxEvent')); ?>" ><?php
                foreach ($tax_events as $tax_event){
                    ?><option value="<?php echo esc_attr($tax_event->term_id); ?>" <?php if($tax_event->term_id==$TaxEvent){echo 'selected=""';} ?>><?php echo $tax_event->name; ?></option><?php
                }
                ?>
                </select>
            </label>
        </p>
        <?php } ?>
        
        <!--             Returns  post type categories-->
        
        <?php $CatID = isset( $instance[ 'CatID' ] ) ? $instance[ 'CatID' ] : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id('CatID'); ?>">
                <?php _e('Post Category (if post type selected):', 'ang-timeline'); ?>
                    <?php
  
                $args = array(
                    'type'                     => 'post',
                    'child_of'                 => 0,
                    'parent'                   => '',
                    'orderby'                  => 'name',
                    'order'                    => 'ASC',
                    'hide_empty'               => 1,
                    'hierarchical'             => 1,
                    'exclude'                  => '',
                    'include'                  => '',
                    'number'                   => '',
                    'taxonomy'                 => 'category',
                    'pad_counts'               => false 
                );
                
                $cats = get_categories( $args );
                
                ?><select class="widefat" 
                          id="<?php echo esc_attr($this->get_field_id('CatID')); ?>" 
                          name="<?php echo esc_attr($this->get_field_name('CatID')); ?>" ><?php
                foreach ($cats as $cat){
                    ?><option value="<?php echo esc_attr($cat->term_id); ?>" <?php if($cat->term_id==$CatID){echo 'selected=""';} ?>><?php echo $cat->name; ?></option><?php
                }
                ?>
                </select>
            </label>
        </p>
        
        
        <!--        Select date and time format -->
        <?php 
		$date_formats = array(
                        'YEAR' => 'Y',
			'Month dd, YEAR' => 'F j, Y',
			'dd.mm.YEAR' => 'm.j.y',
			'Month, YEAR' => 'F, Y',
			'dd Month, YEAR' => 'j F, Y',
                        'Month, dd, YEAR hh:mm AM' => 'F, j, Y g:i A'
		);
		
                ?>
        
       <?php $p_time_format = isset( $instance[ 'p_time_format' ] ) ? $instance[ 'p_time_format' ] : __('F j, Y', 'ang-timeline'); ?>
        <p>
            <label for="<?php echo $this->get_field_id('p_time_format'); ?>">
                <?php _e('Prefered time ', 'ang-timeline'); ?>  <a target="_blank" href="http://php.net/manual/en/function.date.php"><?php _e('format', 'ang-timeline'); ?></a> :
            </label> 
            <br>
            
            <select id="<?php echo esc_attr($this->get_field_id('p_time_format')); ?>"
                    name="<?php echo esc_attr($this->get_field_name('p_time_format')); ?>">
                <?php foreach($date_formats as $date_format =>$value){ ?>             
                <option value="<?php echo $value; ?>" <?php if ($value == $p_time_format){ echo 'selected=""';}?> name="<?php echo esc_attr($this->get_field_name('p_time_format')); ?>" ><?php echo $date_format ; ?></option>
            <?php } ?>
                
            </select>
        </p>
               
        <!--        Number of posts to show -->
        
        <?php $PostsCount = isset( $instance[ 'PostsCount' ] ) ? $instance[ 'PostsCount' ] : 6; ?>
        <p>
            <label for="<?php echo $this->get_field_id('PostsCount'); ?>">
                <?php _e('Number of posts:', 'ang-timeline'); ?>
                <input 
                        id="<?php echo $this->get_field_id('PostsCount'); ?>" 
                        name="<?php echo $this->get_field_name('PostsCount'); ?>" 
                        type="number"
                        value="<?php echo $PostsCount; ?>" />
            </label>
        </p>
        
        
        <!--        Number of words per post -->
        
        <?php $p_number_words = isset( $instance[ 'p_number_words' ] ) ? $instance[ 'p_number_words' ] : 40; ?>
        <p>
            <label for="<?php echo $this->get_field_id('p_number_words'); ?>">
                <?php _e('Words per each post:', 'ang-timeline'); ?>
                <input 
                        id="<?php echo $this->get_field_id('p_number_words'); ?>" 
                        name="<?php echo $this->get_field_name('p_number_words'); ?>" 
                        type="number"
                        min="20"
                        value="<?php echo $p_number_words; ?>" />
            </label>
        </p>
        
<!--        Select image size-->

        <?php $image_size = isset( $instance[ 'image_size' ] ) ? $instance[ 'image_size' ] : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Size:', 'ang-timeline'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>">
                <option class="widefat" value="" <?php if('' == $image_size){echo 'selected="selected"';} ?>><?php _e('-- Default (100 X 100) --', 'ang-timeline'); ?></option>
                <?php
                    $sizes = Ang_Timeline_Public::ang_get_thumbnail_sizes();
                    foreach ($sizes as $k => $v) {
                            $v = implode(" x ", $v);
                            echo '<option class="widefat" value="' . $k . '" id="' . $k . '"', $image_size == $k ? ' selected="selected"' : '', '>', __($k, 'ang-timeline') . ' (' . __($v, 'ang-timeline') . ' )', '</option>';
                    }
                ?>
            </select>
        </p>
                
                
        <!--   Order type extended -->
        
        <?php 
		$p_orders = array(
                        'None' => 'none',
                        'Random' => 'rand',
			'Post ID' => 'ID',
			'Post Author' => 'author',
			'Post title' => 'title',
			'Post slug' => 'name',
                        'Publication date' => 'date',
                        'Modified date' => 'modified',
			'Post_type' => 'type',
			'Parent field' => 'parent',
			'Comments count' => 'comment_count',
                        'Menu order' => 'menu_order',
		);
		
                ?>
        
       <?php $p_order_by = isset( $instance[ 'p_order_by' ] ) ? $instance[ 'p_order_by' ] : 'none'; ?>
        <p>
            <label for="<?php echo $this->get_field_id('p_order_by'); ?>">
                <?php _e('Order type format:', 'ang-timeline'); ?>
            </label> 
            <br>
            
            <select id="<?php echo esc_attr($this->get_field_id('p_order_by')); ?>" name="<?php echo esc_attr($this->get_field_name('p_order_by')); ?>">
                <?php foreach($p_orders as $p_order =>$value){ ?>
                <option value="<?php echo $value; ?>" <?php if ($value == $p_order_by){ echo 'selected=""';}?> name="<?php echo esc_attr($this->get_field_name('p_order_by')); ?>" ><?php echo $p_order ; ?></option>
            <?php } ?>
                
            </select>
        </p>
        
        <!--   Order type -->
        
        <?php $p_order_type = isset( $instance['p_order_type'] ) ? $instance['p_order_type'] : __('ASC', 'ang-timeline'); ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'p_order_type' )); ?>">
                <?php _e('Posts order type:', 'ang-timeline'); ?>
            </label> 
            <br>
            <input type="radio" id="<?php echo esc_attr($this->get_field_id('p_order_type')."_ASC"); ?>" name="<?php echo esc_attr($this->get_field_name('p_order_type')); ?>" value="ASC" <?php if($p_order_type=="ASC"){ echo "checked"; }?>><?php _e('ASC', 'ang-timeline');?> &nbsp;&nbsp;&nbsp;
            <input type="radio" id="<?php echo esc_attr($this->get_field_id('p_order_type')."_DESC"); ?>" name="<?php echo esc_attr($this->get_field_name('p_order_type')); ?>" value="DESC" <?php if($p_order_type=="DESC"){ echo "checked"; }?>><?php _e('DESC', 'ang-timeline');?>
        </p>
        
         <!--   Checkbox Hide the post title -->
         
         <?php $p_title = isset( $instance['p_title'] ) ? $instance['p_title'] : false; ?>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('p_title'); ?>" name="<?php echo $this->get_field_name('p_title'); ?>" <?php if ($p_title) echo 'checked'; ?> />
            <label for="<?php echo $this->get_field_id('p_title'); ?>"><?php _e('Hide the post title', 'ang-timeline'); ?></label>
        </p>
        
         <!--   Checkbox Hide the featured image -->
         
        <?php $p_image = isset( $instance['p_image'] ) ? $instance['p_image'] : false; ?>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('p_image'); ?>" name="<?php echo $this->get_field_name('p_image'); ?>" <?php if ($p_image) echo 'checked'; ?> />
            <label for="<?php echo $this->get_field_id('p_image'); ?>"><?php _e('Hide the featured image', 'ang-timeline'); ?></label>
        </p>
        
         <!--   Checkbox Hide the post link -->
         
        <?php $p_link = isset( $instance['p_link'] ) ? $instance['p_link'] : false ; ?>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('p_link'); ?>" name="<?php echo $this->get_field_name('p_link'); ?>" <?php if ($p_link) echo 'checked'; ?> />
            <label for="<?php echo $this->get_field_id('p_link'); ?>"><?php _e('Hide the post link', 'ang-timeline'); ?></label>
        </p>
        
         <!--   Checkbox Hide the date -->
         
        <?php $p_date_hide = isset( $instance['p_date_hide'] ) ? $instance['p_date_hide'] : false ; ?>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('p_date_hide'); ?>" name="<?php echo $this->get_field_name('p_date_hide'); ?>" <?php if ($p_date_hide) echo 'checked'; ?> />
            <label for="<?php echo $this->get_field_id('p_date_hide'); ?>"><?php _e('Hide the date', 'ang-timeline'); ?></label>
        </p>
        <?php
        
    }
    
    function update($new_instance, $old_instance) { 
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['descr'] = $new_instance['descr'];
        $instance['extra_class'] = $new_instance['extra_class'];
        $instance['PostsCount'] = $new_instance['PostsCount'];
        $instance['CatID'] = $new_instance['CatID'];
        $instance['TaxEvent'] = $new_instance['TaxEvent'];
        
        $instance['p_post_type'] = $new_instance['p_post_type'];
        $instance['p_time_format'] = $new_instance['p_time_format'];
        $instance['p_number_words'] = $new_instance['p_number_words'];
        $instance['image_size'] = $new_instance['image_size'];
        $instance['p_order_by'] = $new_instance['p_order_by'];
        $instance['p_order_type'] = $new_instance['p_order_type'];
        $instance['p_title'] = $new_instance['p_title'];
        $instance['p_image'] = $new_instance['p_image'];
        $instance['p_link'] = $new_instance['p_link'];
        $instance['p_date_hide'] = $new_instance['p_date_hide'];
        
        
       
        return $instance;
    }
    
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        
         $p_title = $instance['p_title'] ? true : false;
         $p_image = $instance['p_image'] ? true : false;
         $p_link = $instance['p_link'] ? true : false;
         $p_date_hide = $instance['p_date_hide'] ? true : false;
        
         $image_size = $instance['image_size'] ? $instance['image_size'] : array(100, 100);
       
        $args = array(
            'posts_per_page'   => $instance['PostsCount'],
            'offset'           => 0,
            //'category'         => $cat_typeID,
            'orderby'          => $instance['p_order_by'],
            'order'            => $instance['p_order_type'],
            'post_status'      => 'publish',
            'post_type'        => $instance[ 'p_post_type' ],
            'suppress_filters' => true 
        );
        
        if($instance[ 'p_post_type' ] == 'timeline'){
        /*
         * Check Post type timeline and taxonomy
         */
            $args['tax_query'][] = array(
                    'taxonomy' => 'event',
                    'field' => 'term_id',
                    'terms' => $instance['TaxEvent']
            );
        }elseif($instance[ 'p_post_type' ] == 'post'){
        /*
         * Check Post type post and category
         */
           $args['category'] = $instance['CatID'];
        }else{
            $args['category'] = '';
        }
        
        $list = get_posts( $args );   ?>
        
        <div class="ang-timeline-wrapp <?php if(isset($instance['extra_class'])) { echo $instance['extra_class']; } ?>">
            <?php if($instance['descr'] != NULL) : ?>
            <div class="uk-width-1-1 tm-widget-descr">
                <p class="tm-widget-title-content"><?php echo $instance['descr']; ?></p>
            </div>
            <?php endif; ?>
            <div class="uk-width-1-1 ang-timeline-entry">
                <ul class="feed-timeline-vertical uk-clearfix">
                <?php 
                    $count=0;
                    foreach ($list as $post) {
                      $count+=1;?>  
                    <li class="<?php if($count % 2 == 0) : ?>even<?php else: ?>odd<?php endif; ?>"   data-uk-scrollspy="{cls:'<?php if($count % 2 == 0) : ?>uk-animation-slide-right<?php else: ?>uk-animation-slide-left<?php endif; ?>', repeat: false, delay:<?php if($count % 2 == 0) : ?>600<?php else: ?>400<?php endif; ?>}">
                        <div class="entry-wrapp uk-clearfix">
                            <div class="timeline-year">
                                <time><?php echo (get_post_meta($post->ID, 'timeline', true)) ? get_post_meta($post->ID, 'timeline', true) : get_post_meta($post->ID, '_timeline', true) ?></time>
                            </div>

                            <div class="timeline-icon-wrapp" data-uk-scrollspy="{cls:'uk-animation-fade', repeat: false, delay:<?php if($count % 2 == 0) : ?>600<?php else: ?>400<?php endif; ?>}">
                                <div class="timeline-icon">                              
                                </div>
                            </div>
                            <div class="entry uk-clearfix">
                                <?php
                                if($p_date_hide != true) { ?>
                                <div class="timeline-time">
                                    <?php 
                                        $date = '<time datetime="'.get_the_time($instance['p_time_format'], $post->ID).'">'.get_the_time($instance['p_time_format'], $post->ID).'</time>';
                                        echo $date;
                                    ?>
                                </div>
                                <?php } ?>

                                <div class="panel timeline-content">
                                    <div class="panel-body uk-text-left">
                                        <?php 
                                        if($p_title != true){ ?>
                                        <h5 class="uk-panel-title uk-text-left"><a class="" href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h5>
                                       <?php } ?>
                                        <?php 
                                       if ($p_image != true){
                                            if (has_post_thumbnail( $post->ID ) ){ ?>
                                                    <a class="tm-timeline-ava" href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_post_thumbnail ($post->ID, $image_size, array('class' => 'alignleft tm-timeline-thumb')); ?></a>
                                                <?php 
                                            }
                                        }
                                        ?>

                                        <?php
                                            if($p_link != true){

                                                    echo '<p>'. wp_trim_words($post->post_content, $instance['p_number_words'], '') .'</p> <a  class="uk-button uk-button-primary uk-float-right" href="'. get_permalink($post->ID) .'" title="'.$post->post_title.'">Read more</a>';

                                            }else{
                                                    echo '<p>'. wp_trim_words($post->post_content, $instance['p_number_words'], '') .'</p>';
                                            }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php    
                    }
                    wp_reset_postdata();
                    ?>
                </ul>
            </div>
        </div>
       
        <?php
        
        echo $after_widget;
    }
}
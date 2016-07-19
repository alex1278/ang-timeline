<?php

/**
 *
 * @link              http://torbara.com
 * @since             1.3.0
 * @package           Ang_Timeline
 *
 * @wordpress-plugin
 * Plugin Name:       ANG Timeline
 * Plugin URI:        https://github.com/alex1278/ang-timeline
 * Description:       Timeline tree for WordPress posts and CPT.
 * Tags:              timeline, custom post type, custom taxonomy, timeline type, images, custom fields timiline tab
 * Version:           1.3.5
 * Date: 26.11.2015
 * Author:            Aleksandr Glovatskyy
 * Author URI:        http://torbara.com
 * Author e-mail:     alex1278@list.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ang-timeline
 * Domain Path:       /languages
 * 
 * ANG Timeline is free software: you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation, either version 2 of the License, or 
 * any later version.
 *
 * ANG Timeline is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * A copy of the GNU General Public License has been included with
 * ANG Timeline.
 *
 * @subpackage  Widget/Timeline
 * @copyright  Copyright (c) 2013, ANG
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ANG_TIMELINE_BASE', plugin_basename(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ang-timeline-activator.php
 */
function activate_ang_timeline() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ang-timeline-activator.php';
	Ang_Timeline_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ang-timeline-deactivator.php
 */
function deactivate_ang_timeline() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ang-timeline-deactivator.php';
	Ang_Timeline_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ang_timeline' );
register_deactivation_hook( __FILE__, 'deactivate_ang_timeline' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ang-timeline.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.3.0
 */
function run_ang_timeline() {

	$plugin = new Ang_Timeline();
	$plugin->run();

}
run_ang_timeline();

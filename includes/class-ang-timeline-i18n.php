<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://torbara.com
 * @since      1.3.0
 *
 * @package    Ang_Timeline
 * @subpackage Ang_Timeline/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.3.0
 * @package    Ang_Timeline
 * @subpackage Ang_Timeline/includes
 * @author     Aleksandr Glovatskyy <aleksand1278@gmail.com>
 */
class Ang_Timeline_i18n {
        
    /**
     * The domain specified for this plugin.
     *
     * @since    1.3.0
     * @access   private
     * @var      string    $domain    The domain identifier for this plugin.
     */
    private $domain; //ang

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.3.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->domain, //ang
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

    /**
     * Set the domain equal to that of the specified domain.
     *
     * @since    1.3.0
     * @param    string    $domain    The domain that represents the locale of this plugin.
     */
    public function set_domain( $domain ) {
            $this->domain = $domain;
    } //ang


}

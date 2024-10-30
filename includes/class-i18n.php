<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/baonguyenyam/wp-best-css-compiler
 * @since      1.0.0
 *
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/includes
 * @author     Nguyen Pham <baonguyenyam@gmail.com>
 */
class Best_Css_Compiler_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'best-css-compiler',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

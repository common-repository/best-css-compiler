<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/baonguyenyam/wp-best-css-compiler
 * @since      1.0.0
 *
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/includes
 * @author     Nguyen Pham <baonguyenyam@gmail.com>
 */
class Best_Css_Compiler_Activator {

	public function __construct() {
		self::iniDB_Create();
	}

	private function iniDB_Create() {
		
		// WP Globals
		global $table_prefix, $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$tblGroup = $table_prefix . BEST_CSS_COMPILER_PREFIX . '_data';
		if( $wpdb->get_var( "show tables like '$tblGroup'" ) != $tblGroup ) {

			// Query - Create Table

			$sql = "CREATE TABLE $tblGroup (
				compiler_id int(11) NOT NULL AUTO_INCREMENT,
				compiler_title text NOT NULL,
				compiler_type int(11) NOT NULL,
				compiler_order int(11) NOT NULL,
				compiler_content text NOT NULL,
				PRIMARY KEY (compiler_id)
			) $charset_collate;";

			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			self::iniDB_InsertGroup($tblGroup);

		}	
	}

	private function iniDB_InsertGroup($tblGroup) {
		global $wpdb;
		$wpdb->insert( 
			$tblGroup, 
			array( 
				'compiler_id' => 1, 
				'compiler_title' => 'style', 
				'compiler_type' => 1, 
				'compiler_order' => 10, 
				'compiler_content' => ''
			) 
		);
		$wpdb->insert( 
			$tblGroup, 
			array( 
				'compiler_id' => 2, 
				'compiler_title' => 'style', 
				'compiler_type' => 2, 
				'compiler_order' => 10, 
				'compiler_content' => ''
			) 
		);
	}

}

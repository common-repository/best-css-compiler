<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/baonguyenyam/wp-best-css-compiler
 * @since      1.0.0
 *
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/admin/partials
 */

global $table_prefix, $wpdb;
$tblGroup = $table_prefix . BEST_CSS_COMPILER_PREFIX . '_data';
$resultsGroup = $wpdb->get_results( "SELECT * FROM $tblGroup" );

if(isset($_GET['action']) && ($_GET['action'] === 'edit' || $_GET['action'] === 'add' || $_GET['action'] === 'delete' || $_GET['action'] === 'editor')) {
    include 'compiler.php';
} else {
    include 'home.php';
}

?>
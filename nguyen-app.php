<?php
/**
 *
 * @link              https://baonguyenyam.github.io/
 * @since             1.0.0
 * @package           BEST_CSS_COMPILER
 *
 * @wordpress-plugin
 * Plugin Name:       WOW Best CSS Compiler
 * Plugin URI:        https://wow-wp.com
 * Description:       Best CSS Compiler is a CSS preprocessor, a superset of CSS that puts in features that aren’t functional in regular CSS. Best CSS Compiler puts in features to CSS and gets collected into legal CSS
 * Version:           2.0.2
 * Author:            WOW WordPress
 * Author URI:        https://github.com/baonguyenyam/wp-best-css-compiler
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       best-css-compiler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BEST_CSS_COMPILER_NICENAME', 'Best CSS Compiler' );
define( 'BEST_CSS_COMPILER_PREFIX', 'best_css_compiler' );
define( 'BEST_CSS_COMPILER_VERSION', '2.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
function activate_bestCssCompiler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	new Best_Css_Compiler_Activator();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function deactivate_bestCssCompiler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	new Best_Css_Compiler_Deactivator();
}

register_activation_hook( __FILE__, 'activate_bestCssCompiler' );
register_deactivation_hook( __FILE__, 'deactivate_bestCssCompiler' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bestCssCompiler() {

	$plugin = new Best_Css_Compiler();
	$plugin->run();

}
run_bestCssCompiler();

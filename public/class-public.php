<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/baonguyenyam/wp-best-css-compiler
 * @since      1.0.0
 *
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/public
 * @author     Nguyen Pham <baonguyenyam@gmail.com>
 */

class Best_Css_Compiler_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $cssCompiler    The ID of this plugin.
	 */
	private $cssCompiler;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $cssCompiler       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $cssCompiler ) {

		$this->cssCompiler = $cssCompiler;

		add_action( 'carbon_fields_fields_registered', array( $this, '__best_css_compiler_init' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $wp_filesystem;
		global $table_prefix, $wpdb;
		$tblGroup = $table_prefix . BEST_CSS_COMPILER_PREFIX . '_data';
		$resultsGroup = $wpdb->get_results("SELECT * FROM {$tblGroup}");
		
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if(isset($resultsGroup) && is_array($resultsGroup) && count($resultsGroup) > 0) {
			foreach($resultsGroup as $result) {
				$file = $wp_filesystem->wp_content_dir() . 'compiler/'.$result->compiler_title.'-'.$result->compiler_id.'.css';
				if($wp_filesystem->get_contents($file)) {
					wp_enqueue_style( $this->cssCompiler['domain']. '-' . md5($result->compiler_id),  content_url() . '/compiler/'.$result->compiler_title.'-'.$result->compiler_id.'.css'  , array(), $this->cssCompiler['version'], 'all' );
				}
			}
		}


	}

	public function enqueue_styles_concat() {

		global $wp_filesystem;
		global $table_prefix, $wpdb;
		$tblGroup = $table_prefix . BEST_CSS_COMPILER_PREFIX . '_data';
		$resultsGroup = $wpdb->get_results("SELECT * FROM {$tblGroup}");
		
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if(isset($resultsGroup) && is_array($resultsGroup) && count($resultsGroup) > 0) {
			$countID = '';
			$file_content = '';
			foreach($resultsGroup as $result) {
				$file = $wp_filesystem->wp_content_dir() . 'compiler/'.$result->compiler_title.'-'.$result->compiler_id.'.css';
				if($wp_filesystem->get_contents($file)) {
					$countID .= md5($wp_filesystem->get_contents($file));
					$file_content .= $wp_filesystem->get_contents($file);
				}
			}
			if( $wp_filesystem ) {
				$filename = $wp_filesystem->wp_content_dir() . 'compiler/'.(carbon_get_theme_option('__best_css_compiler_name')?carbon_get_theme_option('__best_css_compiler_name'):'compiler-concat').'-'.md5($countID).'.css';
				if(!$wp_filesystem->get_contents($filename)) {
					$contentdir = trailingslashit( $wp_filesystem->wp_content_dir() ); 
					$wp_filesystem->mkdir( $contentdir. 'compiler' );
					$wp_filesystem->put_contents( $filename, $file_content, FS_CHMOD_FILE);
				}
			}
			if(carbon_get_theme_option('__best_css_compiler_name')) {
				wp_enqueue_style( $this->cssCompiler['domain']. '-' . md5($result->compiler_id),  content_url() . '/compiler/'.carbon_get_theme_option('__best_css_compiler_name').'-'.md5($countID).'.css'  , array(), $this->cssCompiler['version'], 'all' );
			} else {
				wp_enqueue_style( $this->cssCompiler['domain']. '-' . md5($result->compiler_id),  content_url() . '/compiler/compiler-concat-'.md5($countID).'.css'  , array(), $this->cssCompiler['version'], 'all' );
			}
		}


	}

	public function enqueue_styles_inline() {

		global $wp_filesystem;
		global $table_prefix, $wpdb;
		$tblGroup = $table_prefix . BEST_CSS_COMPILER_PREFIX . '_data';
		$resultsGroup = $wpdb->get_results("SELECT * FROM {$tblGroup}");
		
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if(isset($resultsGroup) && is_array($resultsGroup) && count($resultsGroup) > 0) {
			foreach($resultsGroup as $result) {
				$file = $wp_filesystem->wp_content_dir() . 'compiler/'.$result->compiler_title.'-'.$result->compiler_id.'.css';
				if($wp_filesystem->get_contents($file)) {
					$data = $wp_filesystem->get_contents($file);
					echo "<style>".esc_html($wp_filesystem->get_contents($file))."</style>";
				}
			}
		}
	}

	public function enqueue_styles_inline_concat() {

		global $wp_filesystem;
		global $table_prefix, $wpdb;
		$tblGroup = $table_prefix . BEST_CSS_COMPILER_PREFIX . '_data';
		$resultsGroup = $wpdb->get_results("SELECT * FROM {$tblGroup}");
		
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if(isset($resultsGroup) && is_array($resultsGroup) && count($resultsGroup) > 0) {
			$file_content = '';
			foreach($resultsGroup as $result) {
				$file = $wp_filesystem->wp_content_dir() . 'compiler/'.$result->compiler_title.'-'.$result->compiler_id.'.css';
				if($wp_filesystem->get_contents($file)) {
					$file_content .= $wp_filesystem->get_contents($file);
				}
			}
			echo "<style>".esc_html($file_content)."</style>";
		}


	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Best_Css_Compiler_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Best_Css_Compiler_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->cssCompiler['domain'], plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), $this->cssCompiler['version'], false );

	}

	public function __best_css_compiler_init() {
		if(carbon_get_theme_option('___best_css_compiler_inline')) {
			if(carbon_get_theme_option('___best_css_compiler_concat')) {
				if(carbon_get_theme_option('__best_css_compiler_position')) {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_inline_concat'), carbon_get_theme_option('__best_css_compiler_position') );
				} else {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_inline_concat'), 9999999 );
				}
			} else {
				if(carbon_get_theme_option('__best_css_compiler_position')) {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_inline'), carbon_get_theme_option('__best_css_compiler_position') );
				} else {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_inline'), 9999999 );
				}
			}
		} else {
			if(carbon_get_theme_option('___best_css_compiler_concat')) {
				if(carbon_get_theme_option('__best_css_compiler_position')) {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_concat'), carbon_get_theme_option('__best_css_compiler_position') );
				} else {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_concat'), 9999999 );
				}
			} else {
				if(carbon_get_theme_option('__best_css_compiler_position')) {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles'), carbon_get_theme_option('__best_css_compiler_position') );
				} else {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles'), 9999999 );
				}
			}
		}
	}

}


<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use ScssPhp\ScssPhp\Compiler;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/baonguyenyam/wp-best-css-compiler
 * @since      1.0.0
 *
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/admin
 * @author     Nguyen Pham <baonguyenyam@gmail.com>
 */

class Best_Css_Compiler_Admin {

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
	 * @param      string    $cssCompiler       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $cssCompiler ) {

		$this->cssCompiler = $cssCompiler;
		add_action( 'carbon_fields_register_fields', array( $this, '___app_option_attach_theme_options' ));
		add_action( 'after_setup_theme', array( $this, '__settingUP' ));

		add_action( 'admin_menu', array( $this, '___addPluginAdminMenu' ));   
		add_action( 'admin_post_submit_data', array( $this, '__submitData') );
		add_filter( 'plugin_action_links',  array( $this, '__compiler_boxesadd_setting_link') , 10, 2 );
	}

	// ADD SETTING LINK 

	public function __compiler_boxesadd_setting_link( $links, $file ) {
		if( $file === 'wp-best-css-compiler/nguyen-app.php' ||  $file === 'best-css-compiler/nguyen-app.php' ){
			$link = '<a href="'.admin_url('admin.php?page=crb_carbon_fields_container_compiler_settings.php').'">'.esc_html__('Settings', 'best-css-compiler' ).'</a>';
			array_unshift( $links, $link ); 
		}
		return $links;
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->cssCompiler['domain'] .'-codemirrortheme', plugin_dir_url( __FILE__ ) . 'css/monokai.css', array(), $this->cssCompiler['version'], 'all' );
		wp_enqueue_style( $this->cssCompiler['domain'] .'-codemirrorhint', plugin_dir_url( __FILE__ ) . 'css/show-hint.css', array(), $this->cssCompiler['version'], 'all' );
		wp_enqueue_style( $this->cssCompiler['domain'], plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->cssCompiler['version'], 'all' );

	}

	public function codemirror_enqueue_scripts() {
		$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
		wp_localize_script('jquery', 'cm_settings', $cm_settings);
		wp_enqueue_script('wp-theme-plugin-editor');
		wp_enqueue_style('wp-codemirror');
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->cssCompiler['domain'], plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->cssCompiler['version'], false );

	}

	public function __submitData() {
		global $table_prefix, $wpdb;
		global $wp_filesystem;
		WP_Filesystem();
		$compiler = new Compiler();
		$tblGroup = $table_prefix . BEST_CSS_COMPILER_PREFIX . '_data';

		$id = isset($_POST['id']) ? (int)$_POST['id'] : '';
		$type = sanitize_text_field(isset($_POST['type']) ? $_POST['type'] : '');
		$posttype = sanitize_text_field(isset($_POST['posttype']) ? $_POST['posttype'] : '');
		$groupName = sanitize_text_field(isset($_POST['groupName']) ? $_POST['groupName'] : '');
		$groupType = sanitize_text_field(isset($_POST['groupType']) ? $_POST['groupType'] : 1);
		$groupOrder = sanitize_text_field(isset($_POST['groupOrder']) ? $_POST['groupOrder'] : 1);
		$groupContent = sanitize_textarea_field(isset($_POST['groupContent']) ? $_POST['groupContent'] : '');

		if($posttype === 'csscompiler') {
			if(isset($type) && $type != '' && $type != null) {
				if($type === 'edit') {
					$wpdb->update(
						$tblGroup,
						array(
							'compiler_title' => $groupName,
							'compiler_order' => $groupOrder,
							'compiler_type' => $groupType
						),
						array('compiler_id'=>$id)
					);
					wp_redirect('admin.php?page=best-css-compiler');
				}
				if($type === 'delete') {
					$wpdb->delete(
						$tblGroup,
						array(
							'compiler_id'=> $id
						),
						array('%d')
					);
					wp_redirect('admin.php?page=best-css-compiler');
				}
				if($type === 'add') {
					$wpdb->insert(
						$tblGroup,
						array( 
							'compiler_title' => $groupName,
							'compiler_order' => $groupOrder,
							'compiler_type' => $groupType
						),
						array( '%s' )
					);
					wp_redirect('admin.php?page=best-css-compiler');
				}
				if($type === 'editor') {
					// Compiler to CSS 
					if( empty( $wp_filesystem ) ) {
						require_once( ABSPATH .'/wp-admin/includes/file.php' );
						WP_Filesystem();
					}
					if( $wp_filesystem ) {
						$filename = $wp_filesystem->wp_content_dir() . 'compiler/'.$groupName.'-'.$id.'.css';

						$stt = false;
						try {
							$scss = new Compiler();
							$css = $scss->compileString($groupContent)->getCss();
							$stt = true;
						} catch (\Exception $e) {
							$css = false;
							$err = $e->getMessage();
						}

						if ($stt) {
							$css = $compiler->compileString($groupContent)->getCss();
							$contentdir = trailingslashit( $wp_filesystem->wp_content_dir() ); 
							$wp_filesystem->mkdir( $contentdir. 'compiler' );
							$wp_filesystem->put_contents( $filename, $css, FS_CHMOD_FILE);
							$wpdb->update(
								$tblGroup,
								array(
									'compiler_content' => $groupContent,
								),
								array('compiler_id'=>$id)
							);
							wp_redirect('admin.php?page=best-css-compiler&id='.$id.'&action=editor');
							exit();
						} else {
							$wpdb->update(
								$tblGroup,
								array(
									'compiler_content' => $groupContent,
								),
								array('compiler_id'=>$id)
							);
							wp_redirect('admin.php?page=best-css-compiler&id='.$id.'&action=editor&showerror='.$err);
							exit();
						}

					}
				}
			}
		}
	
	}


	public function ___addPluginAdminMenu() {
		add_menu_page(  $this->cssCompiler['nicename'],  esc_html__( 'CSS Compiler', 'best-css-compiler' ) , 'administrator', $this->cssCompiler['domain'], array( $this, '___displayPluginAdminDashboard' ), 'dashicons-editor-code', 30 );
	}
	public function ___displayPluginAdminDashboard() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/admin-display.php';
	}

	public function ___app_option_attach_theme_options() {
		$basic_options_container =  Container::make( 'theme_options', esc_html__( 'Compiler Settings', 'best-css-compiler' ) )
		->set_page_parent(  $this->cssCompiler['domain'] )
			->add_tab( esc_html__( 'Output Settings', 'best-css-compiler' ), self::__compilerApp() )
			->add_tab( esc_html__( 'Copyright', 'best-css-compiler' ), self::__copyright() )
			;
	}
	
	public function __settingUP() {
		require_once plugin_dir_path( __DIR__  ) .'vendor/autoload.php';
		\Carbon_Fields\Carbon_Fields::boot();
	}

	public function __compilerApp() {
		$data = array();
		$data = array(
			Field::make(
			'checkbox', 
			'___best_css_compiler_inline',
			esc_html__('Insert CSS into head tag', 'best-css-compiler')
			)->set_option_value( 'no' ),
			Field::make(
				'checkbox', 
				'___best_css_compiler_concat',
				esc_html__('Concat CSS', 'best-css-compiler')
				)->set_option_value( 'yes' ),
			Field::make( 'text', '__best_css_compiler_name', esc_html__( 'Concat CSS File name', 'best-css-compiler' ) )
			->set_default_value('public')
			->set_width(50),
			Field::make( 'text', '__best_css_compiler_position', esc_html__( 'Position', 'best-css-compiler' ) )
			->set_default_value('99999999')
			->set_width(50),
		);
		return $data;
	}

	public function __copyright() {
		$data = array();
		$data = array(
	
			Field::make( 'html', 'crb_html_2', esc_html__( 'Section Description', 'best-css-compiler' ) )
					->set_html('
					
					<h1>'.esc_html__('Best CSS Compiler', 'best-css-compiler' ).'</h1>
					<p>'.esc_html__('Best CSS Compiler is a CSS preprocessor, a superset of CSS that puts in features that aren’t functional in regular CSS. Best CSS Compiler puts in features to CSS and gets collected into legal CSS', 'best-css-compiler' ).'</p>
					
					'),
					Field::make( 'separator', 'crb_separator_1', esc_html__( 'Copyright', 'best-css-compiler' ) ),

			Field::make( 'html', 'crb_html_1', esc_html__( 'Section Description', 'best-css-compiler' ) )
					->set_html('
					<p>'.esc_html__( 'Version ', 'best-css-compiler' ) . BEST_CSS_COMPILER_VERSION.'</p>
					<p style="margin-top:0;margin-bottom:0"><strong>'.esc_html__( 'Author', 'best-css-compiler' ).':</strong> <a href="https://baonguyenyam.github.io/" target="_blank">Nguyen Pham</a></p>
					
					'),
	
		);
		return $data;
	}
	

}



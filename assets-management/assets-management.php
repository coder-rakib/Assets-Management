<?php
/**
 * Plugin Name: Assets Management
 * Plugin URI:  
 * Version:     1.0.0
 * Author:      Rakib
 * Author URI:  
 * License:     GPL v2 or later
 * Text Domain: assetsm
 * Domain Path: /languages
 */
define("ASSETSM_PUBLIC_ASSETS", plugin_dir_url( __FILE__ )."assets/public/js");
define("ASSETSM_ADMIN_ASSETS", plugin_dir_url( __FILE__ )."assets/admin/js");

class AssetsManagement{

	function __construct(){
		add_action("plugins_loaded", [$this, "load_textdomain"]);
		add_action("wp_enqueue_scripts", [$this, "load_public_assets"]);
		add_action("wp_enqueue_scripts", [$this, "myplugin_inline_style_public"]);
		add_action("admin_enqueue_scripts", [$this, "load_admin_assets"]);
		add_action("login_enqueue_scripts", [$this, "myplugin_enqueue_style_login"]);
		add_action("init", [$this, "assetsm_init"]);
	}



	/**
	 * Assets loading dependencies
	 * */
	function load_public_assets(){
		wp_enqueue_script('assetsm-script-1', ASSETSM_PUBLIC_ASSETS . "/script-1.js", array("jquery", "assetsm-script-3", "assetsm-script-2"), time(), true);
		wp_enqueue_script('assetsm-script-2', ASSETSM_PUBLIC_ASSETS . "/script-2.js", array("jquery", "assetsm-script-3"), time(), true);
		wp_enqueue_script('assetsm-script-3', ASSETSM_PUBLIC_ASSETS . "/script-3.js", array("jquery"), time(), true);

		/**
		 * Pass data from PHP to JS
		 * */
		$data = array(
			'name' => __('Your name', 'assetsm'),
			'profession' => __('Your Profession', 'assetsm')
		);
		wp_localize_script( 'assetsm-script-3', 'assetsm_data', $data );

	}



	/**
	 * Loading assets for Admin
	 *
	 * */
	function load_admin_assets($screen){

		/**
		 * Use print_r() to ensure which screen you are on
		 * 
		 * print_r($screen);
		 * */

		if( ('toplevel_page_home_settings' == $screen) || ('kaku-theme-options_page_submenu_settings' == $screen) ){
			wp_enqueue_script('assetsm-admin-script-1', ASSETSM_ADMIN_ASSETS . "/script-1.js", array("jquery"), time(), true);
		}
	}



	/**
	 * Register, Deregister (style & scripts)
	 * */
	function assetsm_init(){
		wp_deregister_style( 'handle-name' );
		wp_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' );//use same handle as deregister to avoid any issue

		wp_deregister_script( 'handle-name' );
		wp_register_script( $handle, $src, $deps = array(), $ver = false, $in_footer = false );//use same handle as deregister to avoid any issue
	}



	/**
	 * Add inline style and scripts
	 * 
	 * For admin just change hook to "admin_enqueue_scripts"
	 * */
	function myplugin_inline_style_public() {

		/**
		 * inline style
		 * */
		// Step 1 - First enqueue CSS file.
		wp_enqueue_style( 'myplugin-public', plugin_dir_url( __FILE__ ) .'PATH', array(), null, 'all' );

		// Step 2 - add inline CSS using the CSS file handle.
		$css = 'body { color: red !important; }';
		wp_add_inline_style( 'myplugin-public', $css );


		/**
		 * inline scripts
		 * */

		// Step 1 - First enqueue JS file.
		wp_enqueue_script( 'myplugin-public', plugin_dir_url( __FILE__ ) .'PATH', array(), null, false );

		// Step 2 - add inline JavaScript using the JS file handle.
		$js = 'alert("Hello world!");';
		wp_add_inline_script( 'myplugin-public', $js );
	}



	/**
	 * Loading assets for Login User
	 * */
	function myplugin_enqueue_style_login() {

		wp_enqueue_style( 'myplugin-login', plugin_dir_url( __FILE__ ) .'PATH', array(), null, 'all' );
	}



	/**
	 * Load plugin textdomain
	 * */
	function load_textdomain(){
		load_plugin_textdomain( "assetsm", false, dirname(__FILE__) . "/languages" );
	}

}
new AssetsManagement();





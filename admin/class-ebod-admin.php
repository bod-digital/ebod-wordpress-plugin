<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bod.digital
 * @since      1.0.0
 *
 * @package    Ebod
 * @subpackage Ebod/admin
 */

if( ! function_exists('ebod_admin_page')) {
	function ebod_admin_page() {
		$ebodToken = get_user_meta(EBOD_UID, 'ebod-token', true);
		if ( $ebodToken === false ) {
			$ebodToken = '';
		}
		
		$ebodAuth = get_user_meta(EBOD_UID, 'ebod-auth', true);
		if ( $ebodAuth === false ) {
			$ebodAuth = '';
		}

		$ebodAuthCreated = get_user_meta(EBOD_UID, 'ebod-auth-created', true);
		if ( $ebodAuthCreated === false ) {
			$ebodAuthCreated = time();
		}
			
		$isWoocommerceActive = is_woocommerce_activated();
				
		include('partials/ebod-admin-display.php');
	}
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ebod
 * @subpackage Ebod/admin
 * @author     EBOD <info@bod.digital>
 */
class Ebod_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Ebod_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ebod_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ebod-admin.css', array(), $this->version, 'all' );

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
		 * defined in Ebod_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ebod_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ebod-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function ebod_menu() {
		add_menu_page(
			'EBOD', 
			'EBOD Tracking', 
			'manage_options', 
			'ebod', 
			'ebod_admin_page', 
			'dashicons-admin-generic'
		);			
	}
	
	public function ebod_submit() {
		if( isset( $_POST['ebod-custom-message'] ) &&  wp_verify_nonce( $_POST['ebod-custom-message'], 'ebod-settings-save' ) ) {
				
			$checkToken = get_user_meta(EBOD_UID, 'ebod-token', true);
			if($checkToken !== false) {
				update_user_meta(EBOD_UID, 'ebod-token', trim(strip_tags($_POST['ebod-token'])));
			} else {
				add_user_meta(EBOD_UID, 'ebod-token', trim(strip_tags($_POST['ebod-token'])), true);
			}

			$checkAuth = get_user_meta(EBOD_UID, 'ebod-auth', true);
			if($checkAuth !== false) {
				update_user_meta(EBOD_UID, 'ebod-auth', trim(strip_tags($_POST['ebod-auth'])));
			} else {
				add_user_meta(EBOD_UID, 'ebod-auth', trim(strip_tags($_POST['ebod-auth'])), true);
				add_user_meta(EBOD_UID, 'ebod-auth-created', time(), true);
			}

			if($checkAuth && $checkAuth != trim($_POST['ebod-auth'])) {
				update_user_meta(EBOD_UID, 'ebod-auth-created', time(), true);
			}
			
			wp_redirect( 'admin.php?success_notice=1&page=' . $this->plugin_name );
			exit;
			
		} else {
			wp_die(__( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
				'response' 	=> 403,
				'back_link' => 'admin.php?page=' . $this->plugin_name,
			));
		}
		
	}

	public function ebod_admin_notice() {
		if (! empty($_GET['success_notice'])) {
			echo '<div class="notice notice-success is-dismissible"><p>Successfully saved.</p></div>';
		}
	}

}

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://bod.digital
 * @since      1.0.0
 *
 * @package    Ebod
 * @subpackage Ebod/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ebod
 * @subpackage Ebod/public
 * @author     EBOD <info@bod.digital>
 */
class Ebod_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ebod-public.css', array(), $this->version, 'all' );

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
		 * defined in Ebod_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ebod_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ebod-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Get the auth token from user meta.
	 */
	private function getAuthToken() {
		return get_user_meta(EBOD_UID, 'ebod-auth', true);
	}

	/**
	 * Get the website token from user meta.
	 */
	private function getWebsiteToken() {
		return get_user_meta(EBOD_UID, 'ebod-token', true);
	}

	/**
	 * Call API request to EBOD tracking system.
	 * 
	 * @param integer $order_id
	 */
	public function ebod_order_completed($order_id) {

		// Get an instance of the WC_Order object
		$order = wc_get_order( $order_id );

		// Get the user email
		$user_email = $order->get_billing_email();

		// Get the order total
		$order_total = $order->get_total();

		// Get the order currency
		$order_currency = $order->get_currency();

		// Get the order status
		$order_status = $order->get_status();

		// Get the auth token 
		$auth_token = $this->getAuthToken();

		// Get the website token
		$website_token = $this->getWebsiteToken();

		// Check auth token and website token
		if( $auth_token === false || $website_token === false ) {
			return;
		}

		// Call API request to EBOD tracking system
		$curl = curl_init(sprintf('%s/api/%s/beacon/%s', EBOD_BASE_URL, EBOD_API_VERSION, $website_token));
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $auth_token,
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
  		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
			"event" => "ebod_purchase",
			"externalId" => $order_id,
			"email" => $user_email,
			"totalPrice" => $order_total,
			"currency" => $order_currency
		)));
		$curl_response = curl_exec($curl);
		curl_close($curl);

		if ($curl_response === false) {
			$error = curl_error($curl);
			error_log($error);
		} else {
			$response = json_decode($curl_response, true);
			if(!empty($response['data']['id'])) {
				// Save the event id to order meta
				$order->update_meta_data('ebod_event_id', $response['data']['id']);
				$order->save_meta_data();
				
				// Call api to confirm the event
				$this->confirm_event($website_token, $response, $auth_token);
			} else {
				error_log( json_encode($response) );
			}
		}
	}

	/**
	 * Call API request to EBOD tracking system to confirm the event.
	 * 
	 * @param string $website_token
	 * @param array $response
	 * @param string $auth_token
	 */
	private function confirm_event($website_token, $response, $auth_token) {
		$curl = curl_init(sprintf('%s/api/%s/beacon/%s/%s', EBOD_BASE_URL, EBOD_API_VERSION, $website_token, $response['data']['id']));
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $auth_token,
			'Content-Type: application/json'
		));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
  		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
			"event" => "ebod_purchase_confirm"
		)));
		$curl_response = curl_exec($curl);				
		curl_close($curl);
	}

}

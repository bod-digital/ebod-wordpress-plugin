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
		add_options_page( 
			'EBOD Tracking', 
			'EBOD Tracking', 
			'manage_options', 
			'ebod', 
			'ebod_options_page' 
		);			
	}

	public function ebod_admin_settings_init() {
		register_setting('ebod', 'ebod_settings');

		add_settings_section(
			'ebod_settings_section',
			'EBOD Tracking Configuration', 
			'ebod_settings_section_callback',
			'ebod'
		);

		add_settings_field(
			'ebod_apitoken_field',
			'Authorization API Token', 
			'ebod_apitoken_field_callback',
			'ebod',
			'ebod_settings_section'
		);
		
		add_settings_field(
			'ebod_webtoken_field',
			'Website Token', 
			'ebod_webtoken_field_callback',
			'ebod',
			'ebod_settings_section'
		);
		
		add_settings_field(
			'ebod_timecreated_field',
			'', 
			'ebod_timecreated_field_callback',
			'ebod',
			'ebod_settings_section'
		);
	}

}

function ebod_settings_section_callback() {
	?>
	<p>
		Track easily the orders on your webpage and the user actions!<br>
		Plugin info at <a href="https://app.ebod.digital/merchant/login/" target="_blank">https://app.ebod.digital/merchant/login/</a>.
	</p>
	<?php
}

function ebod_timecreated_field_callback() {
	$setting = get_option('ebod_settings');
	?>
	<input type="hidden" name="ebod_settings[ebod_timecreated_field]" value="<?php echo isset( $setting['ebod_timecreated_field'] ) ? esc_attr( $setting['ebod_timecreated_field'] ) : time(); ?>">
	<?php
}

function ebod_apitoken_field_callback() {
	$setting = get_option('ebod_settings');
	?>
    <fieldset>
        <div id="cn_app_id">
            <input type="text" name="ebod_settings[ebod_apitoken_field]" value="<?php echo isset( $setting['ebod_apitoken_field'] ) ? esc_attr( $setting['ebod_apitoken_field'] ) : ''; ?>">

            <p class="description">Authorization token for authentication. You can find the token adminitration <a href="https://app.ebod.digital/admin/tokens" target="_blank">here</a>.</p>
                <p class="description" style="color:orange;">
                <?php
					if(isset($setting['ebod_timecreated_field'])) {
						$timestamp = $setting['ebod_timecreated_field'];
						$timestamp180DaysLater = $timestamp  + (EBOD_TOKEN_VALIDITY_DAYS * 24 * 60 * 60);
						$remainingDays = floor(($timestamp180DaysLater - time()) / (24 * 60 * 60));

						echo 'Your token will expire in '.$remainingDays.' days. ';

						if ($currentTimestamp >= ( $timestamp180DaysLater - (30 * 24 * 60 * 60) )) {
							echo 'Please <a href="https://app.ebod.digital/admin/tokens" target="_blank">generate a new token</a> before it expires!';
						}
					}
                ?>
            </p> 
        </div>
    </fieldset>
    <?php
}

function ebod_webtoken_field_callback() {
	$setting = get_option('ebod_settings');
	?>
	<fieldset>
        <div id="cn_app_id">
			<input type="text" name="ebod_settings[ebod_webtoken_field]" value="<?php echo isset( $setting['ebod_webtoken_field'] ) ? esc_attr( $setting['ebod_webtoken_field'] ) : ''; ?>">                                            <p class="description">This steps is an essential prerequisite for identifying your customers and tracking their orders.</p>
            <?php if(!is_woocommerce_activated()): ?>
                <p class="description" style="color:red;">
                    Before setup EBOD tracking, please install and activate Woocommerce plugin first! 
                </p> <a href="/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term">Install woocommerce now</a>
            <?php endif; ?>
        </div>
    </fieldset>
    <?php
}

function ebod_options_page(  ) {			
	include('partials/ebod-admin-display.php');
}


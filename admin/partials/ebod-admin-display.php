<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://bod.digital
 * @since      1.0.0
 *
 * @package    Ebod
 * @subpackage Ebod/admin/partials
 */


?>

<div class="wrap">
        <h1>EBOD Tracking Setup</h1>

	    <div class="ebod-notice-settings">	
	        <div class="ebod-notice-credits">
			    <div class="inside">
					<div class="inner">			
						<h1><b>Unlock Rewards with EBOD!</b></h1>
						<h2>The Ultimate Platform for Earning Rewards.</h2>
						<div class="cn-lead">
							<p>Discover a Revolutionary Way to Earn Rewards and Enhance Your Experience with EBOD, Where Engagement Transforms into Benefits – Join Our Vibrant Community Today and Embrace a Future of Rewarding Interactions!</p>
						</div>
						<img src="https://ebod.digital/assets/images/all-img/hand-mocup.png">
						<a href="https://app.ebod.digital/merchant/signup/" target="_blank" class="cn-btn cn-run-upgrade">Free Sign Up</a>
						<br><br>
						<a href="https://ebod.digital" target="_blank">read more about ebod <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-external-link" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
   <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path>
   <path d="M11 13l9 -9"></path>
   <path d="M15 4h5v5"></path>
</svg></a>
					</div>
				</div>
			</div>

            <form method="post" action="<?php echo esc_html( admin_url( 'options.php' ) ); ?>">
				<?php  
				settings_fields( 'ebod' );
				?>
                <div class="card">
                    <?php if(!ebod_is_woocommerce_activated()): ?>
                    <div class="notice notice-error">
                        <p>
                        Woocommerce plugin is not installed and active!  <a href="/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term">Install woocommerce now</a>.
                        </p>
                    </div>
                    <?php endif; ?>
					<?php		
					do_settings_sections( 'ebod' );
					?>
                </div>
                    
                <?php
                submit_button();
                ?>
            </form>
		</div>
</div>
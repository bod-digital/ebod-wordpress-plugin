<?php

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
        <p>Track easily the orders on your webpage and the user actions! Plugin info at <a href="https://app.bod.digital/merchant/login/" target="_blank">https://app.bod.digital/merchant/login/</a>.</p>
	
	    <div class="ebod-notice-settings">	
	        <div class="ebod-notice-credits">
			    <div class="inside">
					<div class="inner">			
						<h1><b>Unlock Rewards with EBOD!</b></h1>
						<h2>The Ultimate Platform for Earning Rewards.</h2>
						<div class="cn-lead">
							<p>Discover a Revolutionary Way to Earn Rewards and Enhance Your Experience with EBOD, Where Engagement Transforms into Benefits – Join Our Vibrant Community Today and Embrace a Future of Rewarding Interactions!</p>
						</div>
						<img src="//cns2-53eb.kxcdn.com/screen-dashboard-small.png">
						<a href="https://app.bod.digital/merchant/signup/" target="_blank" class="cn-btn cn-run-upgrade">Free Sign Up</a>
						<br><br>
						<a href="https://bod.digital" target="_blank">read more about ebod <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-external-link" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path>
                        <path d="M11 13l9 -9"></path>
                        <path d="M15 4h5v5"></path>
                        </svg></a>
					</div>
				</div>
			</div>

            <form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
                <div class="card">

                    <h2 class="title">EBOD Configuration</h2>
                    
                    <?php if(!$isWoocommerceActive): ?>
                    <div class="notice notice-error">
                        <p>
                        Woocommerce plugin is not installed and active!  <a href="https://mamsikovneruky.sk/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term">Install woocommerce now</a>.
                        </p>
                    </div>
                    <?php endif; ?>

                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">Authorization API Token</th>
                                <td>
                                    <fieldset>
                                        <div id="cn_app_id">
                                            <input <?php echo($isWoocommerceActive ? '' : 'disabled="disabled"') ?> type="text" class="regular-text"  name="ebod-auth"  value="<?php echo $ebodAuth; ?>" />
                                            <p class="description">Authorization token for authentication. You can find the token adminitration <a href="https://app.bod.digital/admin/tokens" target="_blank">here</a>.</p>
                                                <p class="description" style="color:orange;">
                                                    <?php
                                                    $timestamp = $ebodAuthCreated;
                                                    $timestamp180DaysLater = $timestamp  + (EBOD_TOKEN_VALIDITY_DAYS * 24 * 60 * 60);
                                                    $remainingDays = floor(($timestamp180DaysLater - time()) / (24 * 60 * 60));

                                                    echo 'Your token will expire in '.$remainingDays.' days. ';

                                                    if ($currentTimestamp >= ( $timestamp180DaysLater - (30 * 24 * 60 * 60) )) {
                                                        echo 'Please <a href="https://app.bod.digital/admin/tokens" target="_blank">generate a new token</a> before it expires!';
                                                    }
                                                    ?>
                                                     
                                                </p> 
                                        </div>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Website Token</th>
                                <td>
                                    <fieldset>
                                        <div id="cn_app_id">
                                            <input <?php echo($isWoocommerceActive ? '' : 'disabled="disabled"') ?> type="text" class="regular-text"  name="ebod-token"  value="<?php echo $ebodToken; ?>" />
                                            <p class="description">This steps is an essential prerequisite for identifying your customers and tracking their orders.</p>
                                            <?php if(!$isWoocommerceActive): ?>
                                                <p class="description" style="color:red;">
                                                    Before setup EBOD tracking, please install and activate Woocommerce plugin first! 
                                                </p> <a href="https://mamsikovneruky.sk/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term">Install woocommerce now</a>
                                            <?php endif; ?>
                                        </div>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                    
                <input type="hidden" name="action" value="admin_ebod_form_submit">

                <?php
                wp_nonce_field( 'ebod-settings-save', 'ebod-custom-message' );
                submit_button();
                ?>

            </form>
		</div>
</div>
<?php

$license = get_option( 'modula_pro_license_key' );
$status  = get_option( 'modula_pro_license_status', false );

$messages = array(
	'no-license' => esc_html__( 'Enter your license key', 'modula-pro' ),
	'activate-license' => esc_html__( 'Activate your license key', 'modula-pro' ),
	'all-good' => __( 'Your license is active until <strong>%s</strong>', 'modula-pro' ),
	'lifetime' => __( 'Your license is active <strong>forever</strong>', 'modula-pro' ),
);

?>
<div class="row">
	<?php do_action( 'modula_license_errors' ) ?>
	<form method="post" action="options.php">

		<?php settings_fields('modula_pro_license_key'); ?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e('License Key', 'modula-pro'); ?>
					</th>
					<td>
						<input id="modula_pro_license_key" name="modula_pro_license_key" type="password" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
						<label class="description modula-license-label" for="modula_pro_license_key">
							<?php
								if ( '' == $license ) {
									echo $messages['no-license'];
								}elseif ( '' != $license && $status === false ) {
									echo $messages['activate-license'];
								}elseif ( '' != $license && $status !== false && isset( $status->license ) && $status->license == 'valid' ) {
									$date_format = get_option( 'date_format' );

									if ( 'lifetime' == $status->expires ) {
										echo $messages['lifetime'];
									}else{
										$license_expire = date( $date_format, strtotime( $status->expires ) );
										echo sprintf( $messages['all-good'], $license_expire );
									}
								}
									
							?>	
						</label>
					</td>
				</tr>
				<?php if( false !== $license ) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Activate License', 'modula-pro'); ?>
						</th>
						<td>
							<?php if( $status !== false && isset( $status->license ) && $status->license == 'valid' ) { ?>
								<?php wp_nonce_field( 'modula_pro_license_nonce', 'modula_pro_license_nonce' ); ?>
								<input type="submit" class="button-secondary" name="modula_pro_license_deactivate" value="<?php _e('Deactivate License', 'modula-pro'); ?>"/>
							<?php } else {
								wp_nonce_field( 'modula_pro_license_nonce', 'modula_pro_license_nonce' ); ?>
								<input type="submit" class="button-secondary" name="modula_pro_license_activate" value="<?php _e('Activate License', 'modula-pro'); ?>"/>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php submit_button(); ?>

	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function ($) {

	    $(document).on('contextmenu dragstart', function () {
	        return false;
	    });

	/**
	     * Monitor which keys are being pressed
	     */
	    var modula_protection_keys = {
	        'alt': false,
	        'shift': false,
	        'meta': false,
	    };

	    $(document).on('keydown', function (e) {

	        // Alt Key Pressed
	        if (e.altKey) {
	            modula_protection_keys.alt = true;
	        }

	        // Shift Key Pressed
	        if (e.shiftKey) {
	            modula_protection_keys.shift = true;
	        }

	        // Meta Key Pressed (e.g. Mac Cmd)
	        if (e.metaKey) {
	            modula_protection_keys.meta = true;
	        }

	        if (e.ctrlKey && '85' == e.keyCode) {
	            modula_protection_keys.ctrl = true;
	        }


	    });
	    $(document).on('keyup', function (e) {

	        // Alt Key Released
	        if (!e.altKey) {
	            modula_protection_keys.alt = false;
	        }

	        // Shift Key Released
	        if (e.shiftKey) {
	            modula_protection_keys.shift = false;
	        }

	        // Meta Key Released (e.g. Mac Cmd)
	        if (!e.metaKey) {
	            modula_protection_keys.meta = false;
	        }

	        if (!e.ctrlKey) {
	            modula_protection_keys.ctrl = false;
	        }

	    });

	    /**
	     * Prevent automatic download when Alt + left click
	     */
	    jQuery(document).on('click', '#modula_pro_license_key', function (e) {
	        if (modula_protection_keys.alt || modula_protection_keys.shift || modula_protection_keys.meta || modula_protection_keys.ctrl) {
	            // User is trying to download - stop!
	            e.preventDefault();
	            return false;
	        }
	    });

	    jQuery(document).on('keydown click',function(e){
	        if (modula_protection_keys.ctrl) {
	            // User is trying to view source
	            e.preventDefault();
	            return false;
	        }
	    });
	});
</script>
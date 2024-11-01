<?php if ( $type == 'new-key-valid' ) :?>
<div class="wrap alert active">
	<h3 class="key-status"><?php esc_html_e('Your API key is set. Now sit back and relax!', 'spamifyguard'); ?></h3>
</div>
<?php elseif ( $type == 'new-key-empty' ) :?>
<div class="wrap alert active">
	<h3 class="key-status"><?php esc_html_e( 'Spamify Guard will not longer protect your site!' , 'spamifyguard'); ?></h3>
</div>
<?php elseif ( $type == 'default-url-valid' ) :?>
<div class="wrap alert active">
	<h3 class="key-status"><?php esc_html_e( 'The redirect url is set to the default value' , 'spamifyguard'); ?></h3>
</div>
<?php elseif ( $type == 'new-url-valid' ) :?>
<div class="wrap alert active">
	<h3 class="key-status"><?php esc_html_e( 'The redirect url is set' , 'spamifyguard'); ?></h3>
</div>
<?php endif;?>
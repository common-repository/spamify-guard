<p class="spamify-p"><?php esc_html_e('Spamify Guard eliminates spam to your site.', 'spamifyguard'); ?></p>
<p class="spamify-p"><?php printf(esc_html__('To get started, you should register %s. Add your website and copy/paste the token in the form below.'), '<a href="https://app.spamifyguard.com/#/signup">here</a>'); ?></p>
<p class="spamify-p"><?php esc_html_e('When a spammer visits your site, he\'ll be redirected to the url that you can set in the form below.'); ?></p>
<p class="spamify-p"><?php printf(esc_html__('If you want to know more about us, click %s.', 'spamifyguard'), '<a href="https://www.spamifyguard.com/" target="_blank">here</a>'); ?></p>
<p class="spamify-p">&nbsp;</p>
<p class="spamify-p"><strong><?php esc_html_e('Let \'s get started:', 'spamifyguard'); ?></strong></p>

<div class="spamify-p">
	<form action="<?php echo esc_url( SpamifyGuard_Admin::get_page_url() ); ?>" method="post" id="spamifyguard-enter-api-key">
		<input id="key" name="key" type="text" size="15" value="<?php echo esc_attr( SpamifyGuard::get_api_key() ); ?>" class="regular-text code">
		<input type="hidden" name="action" value="enter-key">
		<?php wp_nonce_field( SpamifyGuard_Admin::NONCE ); ?>
		<input type="submit" name="submit" id="submit" class="button button-secondary" value="<?php esc_attr_e('Use this key', 'spamifyguard');?>">
	</form>
</div>

<div class="spamify-p">
	<form action="<?php echo esc_url( SpamifyGuard_Admin::get_page_url() ); ?>" method="post" id="spamifyguard-enter-redirect-url">
		<input id="url" name="url" type="url" size="15" value="<?php echo esc_attr( SpamifyGuard::get_redirect_url() ); ?>" class="regular-text code">
		<input type="hidden" name="action" value="enter-url">
		<?php wp_nonce_field( SpamifyGuard_Admin::NONCE ); ?>
		<input type="submit" name="submit" id="submit" class="button button-secondary" value="<?php esc_attr_e('Redirect to here', 'spamifyguard');?>">
	</form>
</div>

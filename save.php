<?php

function saveSendGridApiKey(){
	check_ajax_referer('sendgridnonce', 'nonce');
	parse_str($_POST['data'], $data);
	if ($data) {
		if (isset($data['sendgrid-api-key'])) {
			update_option( 'sendgrid-api-key', $data['sendgrid-api-key'], true);
		}
		if ($data['rementente-name']) {
			update_option( 'rementente-name', sanitize_text_field($data['rementente-name']), true);
		}
		if ($data['rementente-mail']) {
			update_option( 'rementente-mail', sanitize_email($data['rementente-mail']), true);
		}
	}
	wp_die();
}

add_action( 'wp_ajax_saveSendGridApiKey', 'saveSendGridApiKey');
add_action( 'wp_ajax_nopriv_saveSendGridApiKey', 'saveSendGridApiKey');


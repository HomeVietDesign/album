<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'google' => array(
    'type' => 'tab',
		'title' => 'Cài đặt google',
		'options' => array(
			'map_api_key' => array(
				'label' => 'Google map api key',
				'type'  => 'text',
			),

			'recaptcha_key' => array(
				'label' => 'Recaptcha key',
				'type'  => 'text',
			),
			'recaptcha_secret' => array(
				'label' => 'Recaptcha secret',
				'type'  => 'text',
			),

			'google_client_id' => array(
				'label' => 'Google client id',
				'type'  => 'text',
				'value'  => '',
			),
			'google_client_secret' => array(
				'label' => 'Google client secret',
				'type'  => 'password',
				'value'  => '',
			),
			'google_auth_uri' => array(
				'label' => 'Google Authorized redirect URIs',
				'type'  => 'text',
			),
		),
	),
);
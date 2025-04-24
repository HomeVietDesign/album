<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'facebook' => array(
    'type' => 'tab',
		'title' => 'Cài đặt facebook',
		'options' => array(
			'fbapp_id' => array(
				'label' => 'Facebook app id',
				'type'  => 'text',
			),
			'fbapp_secret' => array(
				'label' => 'Facebook app secret',
				'type'  => 'password',
			),
			'fbapp_auth_uri' => array(
				'label' => 'Facebook Authorized redirect URIs',
				'type'  => 'text',
			),
		),
	),
);
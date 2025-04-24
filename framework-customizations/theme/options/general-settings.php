<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}


$options = array(
	'general' => array(
		'type' => 'tab',
		'title' => 'Cài đặt chung',
		'options' => array(
			'favorite_page' => array(
			    'type'  => 'multi-select',
			    'label' => 'Trang danh sách lựa chọn',
			    /**
			     * Set population method
			     * Are available: 'posts', 'taxonomy', 'users', 'array'
			     */
			    'population' => 'posts',
			    /**
			     * Set post types, taxonomies, user roles to search for
			     *
			     * 'population' => 'posts'
			     * 'source' => 'page',
			     *
			     * 'population' => 'taxonomy'
			     * 'source' => 'category',
			     *
			     * 'population' => 'users'
			     * 'source' => array( 'editor', 'subscriber', 'author' ),
			     *
			     * 'population' => 'array'
			     * 'source' => '' // will populate with 'choices' array
			     */
			    'source' => 'page',
			    /**
			     * Set the number of posts/users/taxonomies that multi-select will be prepopulated
			     * Or set the value to false in order to disable this functionality.
			     */
			    'prepopulate' => 10,
			    /**
			     * Set maximum items number that can be selected
			     */
			    'limit' => 1,
			)
		),
	),
);
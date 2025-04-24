<?php if (!defined('FW')) {
	die('Forbidden');
}

$options = array(
	array(
		'type' => 'tab',
		'title' => __('General', 'fw'),
		'options' => array(
			'html_id' => array(
				'label' => esc_html__('Html ID', 'fw'),
				'desc' => esc_html__('Add Html ID', 'fw'),
				'type' => 'text',
			),
			'html_class' => array(
				'label' => esc_html__('Html Class', 'fw'),
				'desc' => esc_html__('Add Html Class', 'fw'),
				'type' => 'text',
			),
			'no_padding' => array(
				'value' => false,
				'label' => esc_html__('No padding', 'fw'),
				'type' => 'checkbox',
				'text' => esc_html__('If selected, there will be no left and right spacing.', 'fw'),
			),

		)
	),
	array(
		'type' => 'tab',
		'title' => __('Background', 'fw'),
		'options' => array(
			'background_color' => array(
				'label' => __('Background Color', 'fw'),
				'desc'  => __('Please select the background color', 'fw'),
				'type'  => 'color-picker',
			),
			'background_image' => array(
				'label'   => __('Background Image', 'fw'),
				'desc'    => __('Please select the background image', 'fw'),
				'type'    => 'background-image',
				'choices' => array(//	in future may will set predefined images
				)
			),
			'background_size' => array(
				'label'   => __('Background Size', 'fw'),
				'desc'    => __('Please select the background size', 'fw'),
				'type'    => 'select',
				'choices' => array(
					'default' => 'Mặc định',
					'auto' => 'Nguyên bản',
					'contain' => 'Cả khung',
					'cover' => 'Vừa khung',
				)
			),
			'background_position' => array(
				'label'   => __('Background Position', 'fw'),
				'desc'    => __('Please select the background position', 'fw'),
				'type'    => 'select',
				'choices' => array(
					'default' => 'Mặc định',
					'left top' => 'Trên bên trái',
					'center top' => 'Bên trên',
					'right top' => 'Trên bên phải',
					'center center' => 'Chính giữa',
					'left bottom' => 'Dưới bên trái',
					'center bottom' => 'Bên dưới',
					'right bottom' => 'Dưới bên phải',
				)
			),
			'background_repeat' => array(
				'label'   => __('Background Repeat', 'fw'),
				'desc'    => __('Please select the background repeat', 'fw'),
				'type'    => 'select',
				'choices' => array(
					'default' => 'Mặc định lặp',
					'repeat-x' => 'Lặp chiều ngang',
					'repeat-y' => 'Lặp chiều dọc',
					'no-repeat' => 'Không lặp'
				)
			),
			'background_show' => array(
				'type'  => 'switch',
				'value' => 'full',
				'label' => __('Show Background', 'fw'),
				'left-choice' => array(
					'value' => 'full',
					'label' => __('Full', 'fw'),
				),
				'right-choice' => array(
					'value' => 'inner',
					'label' => __('Inner', 'fw'),
				),
			),
		)
	),
	array(
		'type' => 'tab',
		'title' => __('Margin', 'fw'),
		'options' => array(
			'margin_top' => array(
				'label'        => __('Top', 'fw'),
				'type'         => 'text',
			),
			'margin_right' => array(
				'label' => __('Right', 'fw'),
				'type'  => 'text',
			),
			'margin_bottom' => array(
				'label' => __('Bottom', 'fw'),
				'type'  => 'text',
			),
			'margin_left' => array(
				'label' => __('Left', 'fw'),
				'type'  => 'text',
			)
		)
	),
	array(
		'type' => 'tab',
		'title' => __('Padding', 'fw'),
		'options' => array(
			'padding_top' => array(
				'label'        => __('Top', 'fw'),
				'type'         => 'text',
			),
			'padding_right' => array(
				'label' => __('Right', 'fw'),
				'type'  => 'text',
			),
			'padding_bottom' => array(
				'label' => __('Bottom', 'fw'),
				'type'  => 'text',
			),
			'padding_left' => array(
				'label' => __('Left', 'fw'),
				'type'  => 'text',
			)
		)
	),
);

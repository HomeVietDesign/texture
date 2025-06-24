<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/**
 * Framework options
 *
 * @var array $options Fill this array with options to generate framework settings form in backend
 */

$options = [
	'profile_file' => [
		'type' => 'upload',
		'label' => 'File hồ sơ năng lực (pdf)',
		'images_only' => false,
		'files_ext' => ['pdf'],
	],

	'website' => [
		'type' => 'text',
		'label' => 'Website',
		'desc' => 'URL website hoặc Facebook,... của nhà cung cấp.',
	],

	'content' => array(
		'label' => 'Thông tin thêm',
		'desc'  => '',
		'type'  => 'wp-editor',
		'value' => '',
		'size' => 'large',
		'editor_height' => '400'
	),

	'divider' => [
		'type' => 'html',
		'label' => '',
		'html' => '<p></p>'
	]
];
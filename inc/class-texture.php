<?php
namespace HomeViet;

/**
 * 
 */
class Texture extends Post {
	
	public $type = 'texture';

	public function get_image($size='full') {
		$image = '';

		if($this->get('images')) {
			$image = wp_get_attachment_image( $this->get('images')[0]['attachment_id'], $size );
		}

		return $image;
	}

	public function get_image_src($size='full') {
		$image_src = '';

		if($this->get('images')) {
			$image_srcs = wp_get_attachment_image_src( $this->get('images')[0]['attachment_id'], $size, false );
			$image_src = $image_srcs[0];
		}

		return $image_src;
	}

	public function get_image_file() {
		$image_file = '';

		if($this->get('images')) {
			$image_file = get_attached_file($this->get('images')[0]['attachment_id'], true);
		}

		return $image_file;
	}

}
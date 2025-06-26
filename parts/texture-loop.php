<?php
global $post, $texture;
//$texture->refresh();
//$texture = new \HomeViet\Texture($post);

$user = wp_get_current_user();

$ratings = $texture->get('ratings', []);
if (!is_array($ratings)) $ratings = [];

//debug($ratings);

?>
<article <?php post_class('col-sm-6 col-md-4 col-xl-3'); ?>>
	<div class="inner border bg-white h-100">
		<div class="images position-relative border-bottom">
			<div class="year position-absolute start-0 top-0 z-2 px-1"><?php echo esc_html(get_post_time( 'd/m/Y', false, $texture->post )); ?></div>
			<button class="texture-download position-absolute end-0 top-0 z-2 p-1 border-0 bg-transparent" type="button" data-id="<?=$texture->id?>"><span class="dashicons dashicons-download"></span></button>
			<?php
			if(has_role('administrator')) {
				?>
				<a class="edit-texture-button position-absolute start-0 bottom-0 z-2 p-1 lh-1 d-block text-info" href="<?php echo esc_url(get_edit_post_link( $texture->id )); ?>" target="_blank">
					<span class="dashicons dashicons-welcome-write-blog"></span>
				</a>
				<?php
			} else {
				$avg = round(floatval($texture->get_meta('average_rating')),1);
				if(!empty($ratings) && $avg) {
				?>
				<span class="votes position-absolute start-0 bottom-0 z-2 px-1"><?=$avg?><span class="dashicons dashicons-star-filled"></span> / <?=count($ratings)?><span class="dashicons dashicons-admin-users"></span></span>
				<?php
				}
			}
			?>
			<div class="ratio ratio-1x1 bg-dark-subtle z-1">
				<div class="thumbnail position-absolute w-100 h-100 start-0 end-0 pswp-gallery">
				<?php
					if(has_post_thumbnail()) {
						$src_full = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						?>
						<a class="d-block ratio ratio-1x1" href="<?=esc_url($src_full[0])?>" data-pswp-width="<?=$src_full[1]?>" data-pswp-height="<?=$src_full[2]?>">
							<?php the_post_thumbnail( 'large' ); ?>
						</a>
						<?php
					}
				?>
				</div>
			</div>
			<?php
			
				?>
				<div class="position-absolute end-0 bottom-0 z-2 px-1 d-flex">
				<?php
				
				?>
				</div>
				<?php

			?>
		</div>
		<?php
		//if(!isset($_GET['sup'])) {
			$suppliers = get_the_terms( $texture->post, 'supplier' );
			if($suppliers) {
			?>
			<div class="suppliers d-flex justify-content-center my-2">
				<div class="me-1">Nhà cung cấp:</div>
				<div class="d-flex flex-wrap fw-bold">
				<?php
					foreach($suppliers as $key => $value) {
						$url = fw_get_db_term_option($value->term_id, $value->taxonomy, 'website', '');
						if($key>0) {
							echo '<div class="me-1">,</div>';
						}
						echo '<a class="d-flex" href="'.esc_url($url?$url:'#').'">'.esc_html($value->description).'</a>';
					}
				?>
				</div>
			</div>
			<?php
			}
		//}
		?>
		<div class="info pb-3 text-center">
			<h6 class="title m-0">
				<a class="code d-block p-2 text-uppercase" href="<?php the_permalink(); ?>"><?php
				//echo esc_html($texture->id);
				the_title();
				?></a>
				<?php
				$content = wp_get_the_content(get_the_content(null, false, $texture->post));
				if($content!='') {
					?>
					<!-- <a class="text-info" href="#" data-bs-toggle="popover" data-bs-content="<?php echo esc_attr($content); ?>" data-bs-html="true" data-bs-sanitize="false" data-bs-trigger="hover focus click" data-bs-placement="top" data-bs-custom-class="texture-detail"><span class="dashicons dashicons-info"></span></a> -->
					<?php
				}
				?>
			</h6>
			<?php
			if(is_user_logged_in()) {
			$rating = (isset($ratings[$user->ID])) ? absint($ratings[$user->ID]) : 0;
			?>
			<div class="texture-rating text-secondary d-flex justify-content-center flex-wrap mb-2" data-url="<?=esc_attr($_SERVER['REQUEST_URI'])?>" data-id="<?=$texture->id?>" data-rating="<?=$rating?>">
				<div class="order-last d-block w-100 text-center"><span class="star lh-1 star-none" data-value="0" data-bs-toggle="tooltip" data-bs-title="Hủy đánh giá sao">⮿</span></div>
				<?php
				for ($i = 1; $i <= 10; $i++) {
					?>
					<span class="star lh-1<?php echo ($i<=$rating) ? ' voted' :''; ?>" data-value="<?=$i?>" data-bs-toggle="tooltip" data-bs-title="<?=$i?>">★</span>
					<?php
				}
				?>
			</div>
			<?php } ?>
			<div class="terms px-2 d-flex flex-wrap justify-content-center">
			<?php
			//if(!is_tax('design_type')) {
				$types = get_the_terms( $texture->post, 'design_type' );
				foreach($types as $value) {
					echo '<a class="p-1 m-1 border border-primary text-primary-emphasis rounded-1" href="'.esc_url(get_term_link( $value )).'">'.esc_html($value->name).'</a>';
				}
			//}

			//if(!isset($_GET['mat'])) {
				$materials = get_the_terms( $texture->post, 'material' );
				if($materials) {
					foreach($materials as $value) {
						echo '<span class="p-1 m-1 border border-danger text-danger-emphasis rounded-1">'.esc_html($value->name).'</span>';
					}
				}
			//}

			//if(!isset($_GET['pro'])) {
				$products = get_the_terms( $texture->post, 'texture_product' );
				if($products) {
					foreach($products as $value) {
						echo '<span class="p-1 m-1 border border-warning text-warning-emphasis rounded-1">'.esc_html($value->name).'</span>';
					}
				}
			//}

			//if(!isset($_GET['seg'])) {
				$segments = get_the_terms( $texture->post, 'segment' );
				if($segments) {
					foreach($segments as $value) {
						echo '<span class="p-1 m-1 border border-info-subtle text-info-emphasis rounded-1">'.esc_html($value->name).'</span>';
					}
				}
			//}
			?>
			</div>
		</div>
	</div>
</article>
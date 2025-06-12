<?php
global $post;
$texture = new \HomeViet\Texture($post);
//debug($texture);
?>
<article <?php post_class('m-2'); ?>>
	<div class="inner border bg-white">
		<div class="images position-relative border-bottom">
			<div class="year position-absolute start-0 top-0 z-2 px-1"><?php echo esc_html(get_post_time( 'd/m/Y', false, $texture->post )); ?></div>
			<button class="texture-download position-absolute end-0 top-0 z-2 p-1 border-0 bg-transparent" type="button" data-id="<?=$texture->id?>"><span class="dashicons dashicons-download"></span></button>
			<?php
			if(has_role('administrator')) {
				?>
				<a class="edit-texture-button position-absolute start-0 bottom-0 z-2 px-1 text-info" href="<?php echo esc_url(get_edit_post_link( $texture->id )); ?>" target="_blank">
					<span class="dashicons dashicons-welcome-write-blog"></span>
				</a>
				<?php
			} else {
				?>

				<?php
			}
			?>
			<div class="ratio ratio-1x1 bg-dark-subtle z-1">
			<?php
			if($texture->get('images')) {
				?>
				<div class="position-absolute w-100 h-100 start-0 end-0">
					<div class="texture-slider owl-carousel owl-theme pswp-gallery">
						<?php
						foreach ($texture->get('images') as $key => $value) {
							$src_full = wp_get_attachment_image_src( $value['attachment_id'], 'full' );
							?>
							<a class="d-block ratio ratio-1x1" href="<?=esc_url($src_full[0])?>" data-pswp-width="<?=$src_full[1]?>" data-pswp-height="<?=$src_full[2]?>">
								<?php
								echo wp_get_attachment_image($value['attachment_id'], 'large', false, ['class'=>'object-fit-cover']);
								?>	
							</a>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
			</div>
			<?php
			$suppliers = get_the_terms( $texture->post, 'supplier' );
			if($suppliers) {
				?>
				<div class="texture-suppliers position-absolute end-0 bottom-0 z-2 px-1 d-flex">
				<?php
				foreach ($suppliers as $key => $value) {
					?>
					<a class="zalo d-block bg-warning my-1 py-1 px-2 rounded-1 shadow" href="https://zalo.me/<?=esc_attr($value->name)?>" target="_blank"><?=esc_html($value->description)?></a>
					<?php
				}
				?>
				</div>
				<?php
			}
			?>
		</div>
		<div class="info px-2 py-3 text-center">
			<h6 class="title m-0">
				<!-- <a class="code text-dark" href="<?php echo esc_url(admin_url('admin-ajax.php?action=texture_detail')); ?>" data-bs-toggle="modal" data-bs-target="#texture-detail" data-id="<?=$texture->id?>" data-code="<?=esc_attr(get_the_title())?>"><?php the_title(); ?></a> -->
				<?php
				$content = wp_get_the_content(get_the_content(null, false, $texture->post));
				?>
				<a class="code text-dark" href="#"<?php
				if($content!='') {
					?> data-bs-toggle="popover" data-bs-content="Đang tải..." data-content="<?php echo esc_attr($content); ?>" data-bs-html="true" data-bs-sanitize="false" data-bs-trigger="hover focus click" data-bs-placement="top" data-bs-custom-class="texture-detail"<?php
				}
				?>><?php the_title(); ?></a>
			</h6>
			<div class="terms mt-2 d-flex flex-wrap justify-content-center">
			<?php
			if(is_home() || is_front_page()) {
				$types = get_the_terms( $texture->post, 'design_type' );
				foreach($types as $value) {
					echo '<a class="p-1 m-1 border rounded-1" href="'.esc_url(get_term_link( $value )).'">'.esc_html($value->name).'</a>';
				}
			}
			if(!isset($_GET['seg'])) {
				$segments = get_the_terms( $texture->post, 'segment' );
				if($segments) {
					foreach($segments as $value) {
						echo '<span class="p-1 m-1 border rounded-1">PK: '.esc_html($value->name).'</span>';
					}
				}
			}
			if(!isset($_GET['mat'])) {
				$materials = get_the_terms( $texture->post, 'material' );
				if($materials) {
					foreach($materials as $value) {
						echo '<span class="p-1 m-1 border rounded-1">'.esc_html($value->name).'</span>';
					}
				}
			}
			if(!isset($_GET['pro'])) {
				$products = get_the_terms( $texture->post, 'texture_product' );
				if($products) {
					foreach($products as $value) {
						echo '<span class="p-1 m-1 border rounded-1">'.esc_html($value->name).'</span>';
					}
				}
			}
			?>
			</div>
		</div>
	</div>
</article>
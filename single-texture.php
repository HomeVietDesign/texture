<?php
get_header();

while (have_posts()) {
	the_post();
	global $texture;

	$design_types = get_the_terms( $texture->post, 'design_type' );
	$segments = get_the_terms( $texture->post, 'segment' );
	$materials = get_the_terms( $texture->post, 'material' );
	$texture_products = get_the_terms( $texture->post, 'texture_product' );
	$suppliers = get_the_terms( $texture->post, 'supplier' );

	?>
	<section class="mb-5">
		<div class="page-header mb-4 border-bottom">
			<!-- <h4 class="mb-2 text-uppercase text-center fw-bold">#<?php the_ID(); ?></h4> -->
			<h4 class="mb-2 text-uppercase text-center text-uppercase fw-bold"><?php the_title(); ?></h4>
		</div>
		<div class="texture-detail-top row justify-content-center">
			<div class="col-lg-6">
				<div class="sticky-top">
					<a class="image d-block text-center" href="<?php echo esc_url(wp_get_attachment_url( get_post_thumbnail_id() )); ?>" target="_blank">
						<?php the_post_thumbnail( 'medium_large' ); ?>
					</a>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="sticky-top">
					<div class="specifications">
					<?php
					if($design_types) {
						?>
						<div class="spec d-flex fw-bold mb-2 p-2 border">
							<div class="me-2">Loại thiết kế:</div>
							<div class="text-primary">
							<?php
							foreach ($design_types as $key => $value) {
								if($key>0) echo ', ';
								echo esc_html($value->name);
							}
							?>
							</div>
						</div>
						<?php
					}

					if($texture_products) {
						?>
						<div class="spec d-flex fw-bold mb-2 p-2 border">
							<div class="me-2">Loại sản phẩm:</div>
							<div class="text-primary">
							<?php
							foreach ($texture_products as $key => $value) {
								if($key>0) echo ', ';
								echo esc_html($value->name);
							}
							?>
							</div>
						</div>
						<?php
					}
					if($materials) {
						?>
						<div class="spec d-flex fw-bold mb-2 p-2 border">
							<div class="me-2">Chất liệu:</div>
							<div class="text-primary">
							<?php
							foreach ($materials as $key => $value) {
								if($key>0) echo ', ';
								echo esc_html($value->name);
							}
							?>
							</div>
						</div>
						<?php
					}
					if($segments) {
						?>
						<div class="spec d-flex fw-bold mb-2 p-2 border">
							<div class="me-2">Phân khúc đầu tư:</div>
							<div class="text-primary">
							<?php
							foreach ($segments as $key => $value) {
								if($key>0) echo ', ';
								echo esc_html($value->name);
							}
							?>
							</div>
						</div>
						<?php
					}

					if($suppliers) {
						?>
						<div class="spec d-flex fw-bold mb-2 p-2 border">
							<div class="me-2">Nhà cung cấp:</div>
							<div class="text-primary">
							<?php
							foreach ($suppliers as $key => $value) {
								if($key>0) echo ', ';
								echo '<a href="'.esc_url(get_term_link( $value, $value->taxonomy )).'">'.esc_html($value->description.' ('.$value->name.')').'</a>';
							}
							?>
							</div>
						</div>
						<?php
					}
					?>
					</div>
				</div>
			</div>
		</div>
		<?php if($texture->get('images') || get_the_content()) { ?>
		<div class="texture-detail-bottom">
		<?php
		if($texture->get('images')) {
			?>
			<div class="single-texture-images">
				<h5 class="text-center text-uppercase mb-3">Các hình ảnh thực tế</h5>
				<div class="single-texture-slider owl-carousel owl-theme pswp-gallery">
				<?php
				foreach ($texture->get('images') as $key => $value) {
					//$src = wp_get_attachment_url( $value['attachment_id'] );
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
		<?php if(get_the_content()) { ?>
			<div class="texture-content border-top pt-3 mt-3">
				<h5 class="text-center text-uppercase mb-3">Mô tả thêm</h5>
				<?php the_content(); ?>	
			</div>
		<?php } ?>
		</div>
		<?php } ?>
	</section>
	<?php

}

get_footer();
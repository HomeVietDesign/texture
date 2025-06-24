<?php
get_header();

$queried = get_queried_object();

?>
<section class="mb-5">
	<div class="page-header mb-4 border-bottom">
		<h4 class="mb-2 text-uppercase text-center text-uppercase fw-bold">
		<?php
		echo esc_html($queried->description.' ('.$queried->name.')');
		?>
		</h4>
		<div class="d-flex flex-wrap justify-content-center align-items-center">
		<?php
		$website = fw_get_db_term_option($queried->term_id, $queried->taxonomy, 'website', '');
		if($website) {
			?>
			<a href="<?=esc_url($website)?>" target="_blank" class="btn btn-sm btn-primary m-2">Tìm hiểu thêm: <?=esc_html($website)?></a>
			<?php
		}

		$profile_file = fw_get_db_term_option($queried->term_id, $queried->taxonomy, 'profile_file', '');
		if($profile_file) {
			$profile_file_url = wp_get_attachment_url( $profile_file['attachment_id'] );
			?>
			<a href="<?=esc_url($profile_file_url)?>" target="_blank" class="btn btn-sm btn-primary m-2">Hồ sơ năng lực</a>
			<?php
		}

		?>
		</div>
	</div>

	<?php
	$content = fw_get_db_term_option($queried->term_id, $queried->taxonomy, 'content', '');
	if($content) {
	?>
	<div class="supplier-content text-center mb-4">
		<?php echo wp_get_the_content($content); ?>	
	</div>
	<?php
	}
	?>

	<?php if(have_posts()) { ?>
		<div class="row g-3 grid-textures justify-content-center">
		<?php
		while (have_posts()) {
			the_post();
			get_template_part( 'parts/texture-loop' );
		}
		?>
		</div>
		<?php
		$paginate_links = paginate_links([
			'end_size'           => 3,
			'mid_size'           => 2,
			'prev_text'          => '<span class="dashicons dashicons-arrow-left"></span>',
			'next_text'          => '<span class="dashicons dashicons-arrow-right"></span>',
		]);

		if($paginate_links) {
			?>
			<div class="paginate-links d-flex justify-content-center align-items-stretch my-3">
				<?php echo $paginate_links; ?>
			</div>
			<?php
		}
		?>
	<?php } else { ?>
		<div class="alert alert-info" role="alert">Chưa có</div>
	<?php } ?>
</section>
<?php

get_footer();
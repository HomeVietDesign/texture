<?php
/**
 * 
 */
get_header();
$queried = get_queried_object();
$queried_link = get_term_link($queried);

$segments = get_terms([
	'taxonomy' => 'segment',
	'hide_empty' => false,
	//'fields' => 'ids'
]);

$seg = isset($_GET['seg']) ? absint($_GET['seg']) : '';
$mat = isset($_GET['mat']) ? absint($_GET['mat']) : '';
$pro = isset($_GET['pro']) ? absint($_GET['pro']) : '';

$current_url = fw_current_url();
?>
<section class="mb-6">
	<div class="page-header position-sticky row g-2 justify-content-between align-items-center mb-4 border-bottom z-3">
		<div class="col-auto">
			<h6 class="mb-2 text-uppercase d-flex">
				<a class="me-1" href="<?=esc_url($queried_link)?>"><?php the_archive_title(); ?></a>
				<?php
				if($seg) {
					?>
					<span class="me-1">/</span><a class="me-1" href="<?php echo esc_url(add_query_arg(['seg'=>$seg], $queried_link)); ?>"><?=esc_html(get_term_field( 'name', $seg, 'segment' ))?></a>
					<?php
				}
				if($mat) {
					?>
					<span class="me-1">/</span><a class="me-1" href="<?php echo esc_url(add_query_arg(['mat'=>$mat], $queried_link)); ?>"><?=esc_html(get_term_field( 'name', $mat, 'material' ))?></a>
					<?php
				}
				if($pro) {
					?>
					<span class="me-1">/</span><a class="me-1" href="<?php echo esc_url(add_query_arg(['pro'=>$pro], $queried_link)); ?>"><?=esc_html(get_term_field( 'name', $pro, 'texture_product' ))?></a>
					<?php
				}
				?>
			</h6>
		</div>
		<div class="col-auto">
			<?php
			if($segments) {
				$all_url = remove_query_arg('seg', $current_url);
				?>
				<a class="btn btn-sm mb-2 <?php echo ($seg=='')?'btn-primary':'btn-secondary'; ?>" href="<?=esc_url($all_url)?>">Phân khúc: </a>
				<?php
				foreach ($segments as $key => $value) {
					$url = add_query_arg(['seg'=>$value->term_id], $current_url);
					$class = ($seg==$value->term_id)?'btn-primary':'btn-secondary';
					?>
					<a class="btn btn-sm <?=$class?> mb-2" href="<?=esc_url($url)?>"><?php echo esc_html($value->name); ?></a>
					<?php
				}
			}
			?>
		</div>
	</div>
	<?php if(have_posts()) { //global $wp_query; debug($wp_query->request); ?>
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
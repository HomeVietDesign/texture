<?php
$queried = get_queried_object();
$current_url = fw_current_url();
?>
<nav class="navbar navbar-vertical navbar-vertical-right navbar-expand-lg">
<div class="collapse navbar-collapse">
<!-- scrollbar removed-->
	<div class="navbar-vertical-content">
		<ul class="navbar-nav flex-column">
		<?php
		if(is_singular( 'texture' )) {
			$excludes = [];

			global $post;

			$design_types = get_the_terms( $post, 'design_type' );
			$segments = get_the_terms( $post, 'segment' );
			$materials = get_the_terms( $post, 'material' );
			$texture_products = get_the_terms( $post, 'texture_product' );
			$suppliers = get_the_terms( $post, 'supplier' );

			$tax_query = [];
			$args = [
				'post_type' => 'texture',
				'posts_per_page' => 5,
				'post_status' => 'publish',
				'post__not_in' => [$post->ID],
				'orderby' => 'date',
				'order' => 'DESC'
			];
			if($design_types) {
				$tax_query['type'] = [
					'taxonomy' => 'design_type',
					'field' => 'term_id',
					'terms' => array_map('map_term_id', $design_types)
				];
			}
			if($segments) {
				$tax_query['type'] = [
					'taxonomy' => 'segment',
					'field' => 'term_id',
					'terms' => array_map('map_term_id', $segments)
				];
			}
			if($materials) {
				$tax_query['type'] = [
					'taxonomy' => 'material',
					'field' => 'term_id',
					'terms' => array_map('map_term_id', $materials)
				];
			}
			if($texture_products) {
				$tax_query['type'] = [
					'taxonomy' => 'texture_product',
					'field' => 'term_id',
					'terms' => array_map('map_term_id', $texture_products)
				];
			}
			if($suppliers) {
				$tax_query['type'] = [
					'taxonomy' => 'supplier',
					'field' => 'term_id',
					'terms' => array_map('map_term_id', $suppliers)
				];
			}

			if(!empty($tax_query)) $args['tax_query'] = $tax_query;

			$query = new \WP_Query($args);
			if($query->have_posts()) {
			?>
			<li class="nav-item">
				<p class="navbar-vertical-label">Map tương tự</p>
				<hr class="navbar-vertical-line">
				<?php
				if($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();
						$excludes[] = get_the_ID();
						get_template_part( 'parts/texture-side-loop' );
					}
					wp_reset_postdata();
				}
				?>
				<!-- <hr class="navbar-vertical-line"> -->
			</li>
			<?php
			}
		}
		?>
		</ul>
	</div>
</div>
</nav>
<?php
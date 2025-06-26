<?php
$queried = get_queried_object();
$current_url = fw_current_url();
?>
<nav class="navbar navbar-vertical navbar-expand-lg">
<div class="collapse navbar-collapse" id="navbarVerticalCollapse">
<!-- scrollbar removed-->
	<div class="navbar-vertical-content">
		<ul class="navbar-nav flex-column">
		<?php
		
		$queried = get_queried_object();
		$mat = isset($_GET['mat']) ? absint($_GET['mat']) : '';
		$parent = '';
		if($mat) {
			$mat_obj = get_term_by( 'term_id', $mat, 'material' );
			$parent = $mat_obj->parent;
		}

		$materials = get_terms([
			'taxonomy' => 'material',
			'hide_empty' => true,
			//'fields' => 'ids',
			'parent' => 0,
			'orderby' => 'name',
			'order' => 'ASC'
		]);

		//debug($materials);

		if($materials) {
		?>
		<li class="nav-item">
			<div class="navbar-vertical-label d-flex justify-content-between align-items-center">
				<span>Chất liệu</span>
				<?php
				$all_url = remove_query_arg('mat', $current_url);
				?>
				<a class="filter-remove d-block me-2 px-2 pt-1 bg-secondary-subtle border border-dark-subtle rounded-1" data-tax="material" href="<?=esc_url($all_url)?>" title="Tất cả">Tất cả</a>
			</div>
			<hr class="navbar-vertical-line">
			<div class="nav-item-wrapper nav-search-term px-4 mb-2">
				<input type="text" id="search-material" class="form-control form-control-sm" placeholder="Tìm...">
			</div>
			<div id="material-list-search" class="hidden">

			</div>
			<div id="material-list">
			<?php
			foreach ($materials as $key => $value) {
				$children = get_term_children( $value->term_id, 'material' );
				$url = add_query_arg(['mat'=>$value->term_id], $current_url);
				$class = ($mat==$value->term_id)?'active':'';

				?>
				<div class="nav-item-wrapper">
					<?php if(empty($children)) { ?>
					<a class="nav-link dropdown-indicator label-1 <?=$class?> text-uppercase" href="<?=esc_url($url)?>" role="button" data-bs-toggle="" aria-expanded="false">
						<div class="d-flex align-items-center">
							<span class="nav-link-text-wrapper">
								<span class="nav-link-icon"></span>
								<span class="nav-link-text"><?=esc_html($value->name)?></span>
							</span>
						</div>
					</a>
					<?php } else { ?>
					<span class="nav-link dropdown-indicator label-1 <?=$class?> text-uppercase">
						<div class="d-flex align-items-center">
							<a class="dropdown-indicator-icon-wrapper<?php echo ($mat==$value->term_id || $parent==$value->term_id) ? '':' collapsed'; ?>" href="#material-dropdown-<?=$value->term_id?>" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="material-dropdown-<?=$value->term_id?>"></a>
							<a class="nav-link-text" href="<?=esc_url($url)?>"><?=esc_html($value->name)?></a>
						</div>
					</span>
					<div class="parent-wrapper label-1">
						<ul class="nav parent collapse<?php echo ($mat==$value->term_id || $parent==$value->term_id) ? ' show':''; ?>" id="material-dropdown-<?=$value->term_id?>">
							<?php
							foreach($children as $child) {
								$url = add_query_arg(['mat'=>$child], $current_url);
								$class = ($mat==$child)?'active':'';
								?>
								<li class="nav-item">
									<a class="nav-link <?=$class?>" href="<?=esc_url($url)?>">
										<div class="d-flex align-items-center">
											<span class="nav-link-text"><?=esc_html(get_term_field( 'name', $child, 'material' ))?></span>
										</div>
									</a>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
					<?php } ?>
				</div>
				<?php
			}
			?>
			</div>
		</li>
		<?php
		}

		$queried = get_queried_object();
		$pro = isset($_GET['pro']) ? absint($_GET['pro']) : '';

		$products = get_terms([
			'taxonomy' => 'texture_product',
			'hide_empty' => true,
			//'fields' => 'ids'
		]);
		if($products) {
		?>

		<li class="nav-item">
			<hr class="navbar-vertical-line">
			<div class="navbar-vertical-label d-flex justify-content-between align-items-center">
				<span>Sản phẩm</span>
				<?php
				$all_url = remove_query_arg('pro', $current_url);
				?>
				<a class="filter-remove d-block me-2 px-2 pt-1 bg-secondary-subtle border border-dark-subtle rounded-1" data-tax="texture_product" href="<?=esc_url($all_url)?>" title="Tất cả">Tất cả</a>
			</div>
			<hr class="navbar-vertical-line">
			<?php
			foreach ($products as $key => $value) {
			$url = add_query_arg(['pro'=>$value->term_id], $current_url);
			$class = ($pro==$value->term_id)?'active':'';
			?>
			<div class="nav-item-wrapper">
				<a class="nav-link dropdown-indicator label-1 <?=$class?>" href="<?=esc_url($url)?>"<?php
				if($value->description) {
					?>
					data-bs-toggle="tooltip" data-bs-title="<?=esc_attr($value->description)?>"
					<?php
				}
				?>>
					<div class="d-flex align-items-center">
						<span class="nav-link-text-wrapper">
							<span class="nav-link-icon"></span>
							<span class="nav-link-text"><?=esc_html($value->name)?></span>
						</span>
					</div>
				</a>
			</div>
			<?php
			// code...
			}
			?>
		</li>
		<?php
		}

		$queried = get_queried_object();
		$sup = isset($_GET['sup']) ? absint($_GET['sup']) : '';

		$suppliers = get_terms([
			'taxonomy' => 'supplier',
			'hide_empty' => true,
			//'fields' => 'ids'
		]);
		if($suppliers) {
		?>
		<li class="nav-item">
			<hr class="navbar-vertical-line">
			<div class="navbar-vertical-label d-flex justify-content-between align-items-center">
				<span>Nhà cung cấp</span>
				<?php
				$all_url = remove_query_arg('sup', $current_url);
				?>
				<a class="filter-remove d-block me-2 px-2 pt-1 bg-secondary-subtle border border-dark-subtle rounded-1" data-tax="supplier" href="<?=esc_url($all_url)?>" title="Tất cả">Tất cả</a>
			</div>
			<hr class="navbar-vertical-line">
			<?php	
			foreach ($suppliers as $key => $value) {
			$url = add_query_arg(['sup'=>$value->term_id], $current_url);
			$class = ($sup==$value->term_id)?'active':'';
			?>
			<div class="nav-item-wrapper">
				<a class="nav-link dropdown-indicator label-1 <?=$class?>" href="<?=esc_url($url)?>"<?php
				if($value->name) {
					?>
					data-bs-toggle="tooltip" data-bs-title="<?=esc_attr($value->name)?>"
					<?php
				}
				?>>
					<div class="d-flex align-items-center">
						<span class="nav-link-text-wrapper">
							<span class="nav-link-icon"></span>
							<span class="nav-link-text"><?=esc_html($value->description)?></span>
						</span>
					</div>
				</a>
			</div>
			<?php
			// code...
			}
			?>
		</li>
		<?php
		}
		?>
		</ul>
	</div>
</div>
</nav>
<?php
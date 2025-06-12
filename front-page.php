<?php
get_header();
$user = wp_get_current_user();
?>
<div class="front-page py-3">
<?php
$query = new \WP_Query([
  'post_type' => 'texture',
  'posts_per_page' => -1,
  'post_status' => 'publish',
  'meta_key' => 'rating_'.$user->ID,
  'meta_value' => 0,
  'meta_compare' => '>',
  'orderby' => 'meta_value_num',
  'order' => 'DESC'
]);

//debug($query->request);
if($query->have_posts()) {

?>
<section class="mb-5">
  <div class="page-header mb-4 border-bottom">
    <h5 class="mb-2 text-uppercase text-center">Dùng thường xuyên</h5>
  </div>
  <div class="row g-3 grid-textures justify-content-center">
  <?php
    while ($query->have_posts()) {
      $query->the_post();
      get_template_part( 'parts/texture-loop' );
    }

    wp_reset_postdata();
  ?>
  </div>
</section>
<?php
}

$query = new \WP_Query([
  'post_type' => 'texture',
  'posts_per_page' => 100,
  'post_status' => 'publish',
  'meta_key' => 'average_rating',
  'meta_value' => 0,
  'meta_compare' => '>',
  'orderby' => 'meta_value_num',
  'order' => 'DESC'
]);

if($query->have_posts()) {
?>
<section class="mb-5">
  <div class="page-header mb-4 border-bottom">
    <h5 class="mb-2 text-uppercase text-center">Đánh giá cao</h5>
  </div>
  <div class="row g-3 grid-textures justify-content-center">
  <?php
    while ($query->have_posts()) {
      $query->the_post();
      get_template_part( 'parts/texture-loop' );
    }

    wp_reset_postdata();
  ?>
  </div>
</section>
<?php
}

$args = [
  'post_type' => 'texture',
  'posts_per_page' => 120,
  'post_status' => 'publish',
  'orderby' => 'date',
  'offset' => 5,
  'order' => 'DESC',
  'tax_query' => []
];

$query = new \WP_Query($args);
//debug($query->request);
if($query->have_posts()) {
  ?>
  <section class="mb-5">
    <div class="page-header mb-4 border-bottom">
      <h5 class="mb-2 text-uppercase text-center">Map đăng gần đây</h5>
    </div>
    <div class="row g-3 grid-textures justify-content-center">
    <?php
      while ($query->have_posts()) {
        $query->the_post();
        get_template_part( 'parts/texture-loop' );
      }
    ?>
    </div>
  </section>
  <?php
  wp_reset_postdata();
}
?>
</div>
<?php
get_footer();
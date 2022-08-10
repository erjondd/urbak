<?php

get_header();

/*

Template Name: Smartphones Products Template

*/

?>


<section class="reparation-smartphones-category">
    <div class="container">
        <div class="r-s-c-content">
            <?php
  $args = array( 'post_type' => 'product' ,'posts_per_page' => 100);
  $loop = new WP_Query( $args );
  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
  while ( $loop->have_posts() ) : 
      $loop->the_post();
        echo "<div class='smartphones-product-repeartion'>";
      echo "<div class='product-image'>";
      echo the_post_thumbnail();
      echo  "</div>";
      echo the_title();
      echo "</div>";
    
  endwhile;

            ?>

         
        </div>
    </div>
</section>



<?php
get_footer();
?>
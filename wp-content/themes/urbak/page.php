<?php 

get_header();
?>
<section class="main-half">
    <?= the_title() ?>
</section>

<?php while (have_posts()) : the_post(); ?>

  <?php the_content(); ?>

<?php endwhile; ?>

<?php

get_footer();

?>
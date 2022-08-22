<?php

get_header();
?>
<section class="main-half">
    <?= the_title() ?>
</section>
<div class="container">

    <?php while (have_posts()) : the_post(); ?>

        <?php the_content(); ?>

    <?php endwhile; ?>

</div>
<?php

get_footer();

?>
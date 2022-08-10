<?php

get_header(); ?>
<section class="single-product">
    <div class="container">
        
   
</div>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
<?php

?>


    </div>
</section>



<?php
get_footer();

?>
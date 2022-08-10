<?php

get_header();

/*

Template Name: Vendre Template

*/

?>
<section class="vendre">
    <div class="container">
        <div class="lights-and-tech">
            <div class="apple-watch"><img src="<?php echo get_bloginfo('template_url') ?>/images/apple-watch.svg" /></div>
            <div class="iphone"><img src="<?php echo get_bloginfo('template_url') ?>/images/iphone.svg" /></div>
        </div>
        <div class="vendre-center-text">Vendre</div>
    </div>
    <div class="scroll-down" id="scroll-down"><img src="<?php echo get_bloginfo('template_url') ?>/images/scroll-down.png" /></div>
</section>
<section class="vendre-section">
    <div class="container">
        <div class="vendre-content">
            <div class="vendre-content-top">
                <div class="vendre-page">
                    Vendre
                </div>
                <div class="vendre-information">
                    Veuillez indiquer les informations necessaire a la vente de votre produit
                </div>
            </div>
            <div class="vendre-all-form">
                <div class="vendre-content-leftside" id="vendre-content">
                    <!-- <div class="vendre-content-form-output">
                    <span class="vendre-title"> Marque</span>
                    <span class="vendre-value">Apple</span>
                </div> -->


                    <!-- <div class="vendre-content-form-output">
                    <span class="vendre-title"> Modèle</span>
                    <span class="vendre-value">Iphone12</span>
                </div>
                <div class="vendre-content-form-output">
                    <span class="vendre-title"> Capacité </span>
                    <span class="vendre-value">256 Go</span>
                </div> -->
                </div>
                <div class="vendre-content-rightside">
                    <?php

                    wpforms_display(226, false, false);

                    ?>
                </div>
            </div>

        </div>
    </div>
</section>
<?php
get_footer();
?>
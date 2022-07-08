<?php
?>
<footer class="footer" >

    <div class="container">
        <div class="footer-all-content">
            <div class="footer-righside">
                <div class="rs-top">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/footer-logo.svg" />
                </div>
                <div class="rs-mid">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/adding-logo.svg" />
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/poke-logo.svg" />
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/balois-logo.svg" />
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/loyal-logo.svg" />
                </div>
            </div>
            <div class="footer-leftside">
                <div class="ls-firstcol">
                    <div class="ls-firstcol-1">Plan du site</div>
                    <div class="ls-firstcol-2"> <?php
                                                echo wp_nav_menu(array(
                                                    'menu'   => 'Footer',
                                                ));
                                                ?></div>

                </div>
                <div class="ls-secondcol">
                    <div class="ls-secondcol-1">Informations</div>
                    <div class="ls-secondcol-2"> Collaboration</br>
                        Sponsorisation</br>
                        Service client</div>
                </div>
                <div class="ls-thirdcol">
                    <div class="ls-thirdcol-1">Contact</div>
                    <div class="ls-thirdcol-2">+41 22 314 56 06</br>
                        info@urbak.ch</br>
                        Rue des Deux-Ponts 29, </br>
                        1205 Genève, Suisse</div>
                </div>
                <div class="ls-fourthcol">
                    <div class="ls-fourthcol-1">Réseaux</div>
                    <div class="ls-fourthcol-2">
                        <span><img src="<?php echo get_bloginfo('template_url') ?>/images/insta.svg" /></span>
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/fb.svg" />
                    </div>
                </div>
              
                    <div class="rs-mid-mobile">
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/adding-logo.svg" />
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/poke-logo.svg" />
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/balois-logo.svg" />
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/loyal-logo.svg" />
                    </div>
             

              
            </div>
        </div>
        <div class="footer-bot">
            <div class="f-b-l">
                Urbak.ch © 2022. All Rights Reserved.
            </div>
            <div class="f-b-r">
                <a>Mentions légales</a>
                <a>Conditions générales de vente</a>
                <a>Politique de confidentialité</a>
            </div>
        </div>
    </div>
    </div>
</footer>
<?php
wp_footer();
?>

</html>
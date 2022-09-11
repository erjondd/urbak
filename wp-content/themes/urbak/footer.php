<?php
?>
<footer class="footer">

    <div class="container">
        <div class="footer-all-content">
            <div class="footer-righside">
                <div class="rs-top">
                    <a href="<?= get_home_url() ?>">
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/logo-footer.png" />
                    </a>
                </div>
                <div class="rs-mid">
                <a href="<?= get_home_url() ?>">
                <img src="<?php echo get_bloginfo('template_url') ?>/images/adding-logo.svg" />
                    </a>
                
                    <a href="<?= get_home_url() ?>">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/poke-logo.svg" />
                    </a>
                  
                    <a href="<?= get_home_url() ?>">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/balois-logo.svg" />
                    </a>
                 
                    <a href="<?= get_home_url() ?>">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/loyal-logo.svg" />
                    </a>
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
                    <div class="ls-secondcol-2"><a href="#"> Collaboration</a></br>
                    <a href="#">Sponsorisation</a></br>
                    <a href="#">Service client</a></div>
                </div>
                <div class="ls-thirdcol">
                    <div class="ls-thirdcol-1">Contact</div>
                    <div class="ls-thirdcol-2"><a href="tel:+41 22 314 56 06">+41 22 314 56 06</a></br>
                        <a href="mailto:info@urbak.ch">info@urbak.ch</a></br>
                        Rue des Deux-Ponts 29, </br>
                        1205 Genève, Suisse
                    </div>
                </div>
                <div class="ls-fourthcol">
                    <div class="ls-fourthcol-1">Réseaux</div>
                    <div class="ls-fourthcol-2">
                        <span><a href="https://www.instagram.com/"><img src="<?php echo get_bloginfo('template_url') ?>/images/insta.svg" /></a></span>
                        <a href="https://www.fb.com/"><img src="<?php echo get_bloginfo('template_url') ?>/images/fb.svg" /></a>
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
                <a href="#">Mentions légales</a>
                <a href="#">Conditions générales de vente</a>
                <a href="#">Politique de confidentialité</a>
            </div>
        </div>
    </div>
    </div>
</footer>
<?php
wp_footer();
?>

</html>
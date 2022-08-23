<?php

get_header();

/*
Template Name: Reperation Template
*/

?>
<section class="reperation-main">
    <div class="container">
        <div class="reperation-tittle">
            Reperation
        </div>
    </div>
    <div class="scroll-down-repetation" id="scroll-down-repetation"><img src="<?php echo get_bloginfo('template_url') ?>/images/scroll-down.png" /></div>
</section>
<section class="reperation-phone" id="repetation-phone">
    <div class="container">
        <div class="reperation-phone-content">
            <div class="reperation-phone-infos">
                <div class="reperation-phone-subtittle">Reperation</div>
                <div class="reperation-phone-tittle">Nos services de reparation</div>
                <div class="reperation-phone-info">Nous réparons tout type de problème !
                    Que ce soit un IPhone, un Samsung, un Huawei ou une autre marque de smartphone, nous sommes là pour vous !
                    Nous le réparons en clin d’œil !

                </div>
            </div>
            <div class="reperation-phone-pieces">
                <div class="reperation-phone-pieces-list">
                    <ul>
                        <li id="circle-1 " class="active"><span>1</span>Haut Parleur</li>
                        <li id="circle-2"><span>2</span>Connecteur de charge</li>
                        <li id="circle-3"><span>3</span>Bouton HOME</li>
                        <li id="circle-4"><span>4</span>Boutons volume</li>
                        <li id="circle-5"><span>5</span>Appareil photo avant</li>
                        <li id="circle-6"><span>6</span>Bouton On Off</li>
                        <li id="circle-7"><span>7</span>Ecran</li>
                        <li id="circle-8"><span>8</span>Batterie</li>
                        <li id="circle-9"><span>9</span>Ne s allume plus</li>
                        <li id="circle-10"><span>10</span>Appareil photo arriere</li>
                        <li id="circle-11"><span>11</span>Micro soudures</li>
                    </ul>
                </div>
                <div class="reperation-phone-pieces-picture">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/reperation-phoneimg.png" />
                    <span class="circle-1 active">1</span>
                    <span class="circle-2">2</span>
                    <span class="circle-3">3</span>
                    <span class="circle-4">4</span>
                    <span class="circle-5">5</span>
                    <span class="circle-6">6</span>
                    <span class="circle-7">7</span>
                    <span class="circle-8">8</span>
                    <span class="circle-9">9</span>
                    <span class="circle-10">10</span>
                    <span class="circle-11">11</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="reperation-type">
    <div class="container">

        <?= do_shortcode("[rp]") ?>
        <!-- <div class="reperation-type-content">
            <div class="reperation-type-subtittle">Reperation</div>
            <div class="reperation-type-tittle">Reparer mon appareil</div>
            <div class="reperation-types">
                <div class="reperation-smartphones">
                    <a href="<?php echo get_home_url(); ?>/reperation-smartphone-categories">
                        <div class="reperation-smartphones-recentagle">
                            <img src="<?php echo get_bloginfo('template_url') ?>/images/reperation-smartphones.png" />
                        </div>
                        <div class="reperation-smatphones-link">Smartphones
                            <span>
                                <img src="<?php echo get_bloginfo('template_url') ?>/images/reperation-arrow.svg" />
                            </span>
                        </div>
                    </a>
                </div>
                <div class="reperation-laptops">
                    <a href="<?php echo get_home_url(); ?>/reperation-laptops-categories">
                        <div class="reperation-laptops-recentagle">
                            <img src="<?php echo get_bloginfo('template_url') ?>/images/laptop.png" />
                        </div>
                        <div class="reperation-laptops-link">Ordinateurs<span><img src="<?php echo get_bloginfo('template_url') ?>/images/reperation-arrow.png" /></span></div>
                    </a>
                </div>
                <div class="reperation-tablets">
                    <a href="<?php echo get_home_url(); ?>/reperation-tablets-categories">
                        <div class="reperation-tablets-recentagle">
                            <img src="<?php echo get_bloginfo('template_url') ?>/images/tablet.png" />
                        </div>
                        <div class="reperation-tablets-link">Tablettes<span><img src="<?php echo get_bloginfo('template_url') ?>/images/reperation-arrow.png" /></span></div>
                    </a>
                </div>
            </div>






        </div> -->

    </div>
</section>

<?php
get_footer();
?>
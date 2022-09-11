<?php

get_header();

/*
Template Name: Homepage Template
*/



?>
<section class="main">
    <div class="container">
        <div class="lights-and-tech">

            <div class="apple-watch"><img src="<?php echo get_bloginfo('template_url') ?>/images/apple-watch.svg" /></div>
            <div class="iphone"><img src="<?php echo get_bloginfo('template_url') ?>/images/iphone.svg" /></div>
        </div>
        <div class="acceuil-center-text">We got your back.</div>
        <div class="acceuil-text">
            <div class="big-text">
                <div class="text"><a href="<?php echo get_option('home') ?>/reparation">Prendre un Rendez-vous</a></div>
                <div class="right-arrow"><img src="<?php echo get_bloginfo('template_url') ?>/images/right-arrow.svg" /></div>
            </div>
            <div class="small-text">Nos experts reparent vos </br>appareils sur place ou a</br> domicile.</div>
        </div>
    </div>
    <div class="scroll-down" id="scroll-down-home"><img src="<?php echo get_bloginfo('template_url') ?>/images/scroll-down.png" /></div>
</section>
<section class="home-slider" id="home-slider">

    <div class="swiper-container swiper-container-home">
        <div class="swiper-wrapper">
            <div class="swiper-slide yellow module">
                <div class="container">
                    <div class="swiper-top">
                        <div class="swiper-section">Home</div>
                        <div class="swiper-page">
                            <div class="swiper-pagename">Nos services</div>
                            <div class="swiper-pagenum">01</div>
                        </div>
                    </div>
                    <div class="swiper-big-text">Reparation</div>
                    <div class="swiper-slider-pic">
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/slider-pic-1.jpg" />
                    </div>
                </div>
            </div>
            <div class="swiper-slide blue module">
                <div class="container">
                    <div class="swiper-top">
                        <div class="swiper-section">Home</div>
                        <div class="swiper-page">
                            <div class="swiper-pagename">Nos services</div>
                            <div class="swiper-pagenum">02</div>
                        </div>
                    </div>

                    <div class="swiper-big-text">Acheter</div>
                    <div class="swiper-slider-pic">
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/slider-pic-2.jpg" />
                    </div>
                </div>
            </div>
            <div class="swiper-slide orange module">
                <div class="container">
                    <div class="swiper-top">
                        <div class="swiper-section">Home</div>
                        <div class="swiper-page">
                            <div class="swiper-pagename">Nos services</div>
                            <div class="swiper-pagenum">03</div>
                        </div>
                    </div>
                    <div class="swiper-big-text">Vendre</div>
                    <div class="swiper-slider-pic">
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/slider-pic-3.jpg" />
                    </div>
                </div>
            </div>
            <div class="swiper-slide pink module">
                <div class="container">
                    <div class="swiper-top">
                        <div class="swiper-section">Home</div>
                        <div class="swiper-page">
                            <div class="swiper-pagename">Nos services</div>
                            <div class="swiper-pagenum">04</div>
                        </div>
                    </div>
                    <div class="swiper-big-text">Assurance</div>
                    <div class="swiper-slider-pic">
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/slide-pic-4.jpg" />
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-scrollbar"></div>
    </div>
</section>
<section class="about-us">
    <div class="container">
        <div class="about-us-content">
            <div class="about-us-content-top">
                <div class="about-us-pagename">
                    Home
                </div>
                <div class="about-us-bold">
                    <div class="about-us-bold-name">About us</div>
                    <div class="about-us-bold-number">02</div>
                </div>
            </div>
            <div class="about-us-content-bottom">
                URBAK est une société issue de la collaboration d’étudiants debug_print_backtrace l’Université de Genève. L’équipe URBAK offre des services de réparation et de vente de smartphones aux prix les plus attractifs dans toute la Suisse romande.</br></br>

                En outre, URBAK propose également la vente de smartphones reconditionnés ainsi que le rachat de vos smartphones usagés.</br></br>

                Se voulant être de nature philanthrope, l’équipe URBAK met en place une redistribution des bénéfices de la vente des smartphones reconditionnés à une ONG offrant des repas au plus démunis.
            </div>
        </div>
        <div class="about-us-image">
            <img src="<?php echo get_bloginfo('template_url') ?>/images/about-us-image.svg" />
        </div>
    </div>
</section>
<section class="nos-points-forts">
    <div class="container">
        <div class="nos-points-forts-content">
            <div class="nos-points-forts-top">
                <div class="nos-points-forts-pagename">
                    Home
                </div>
                <div class="nos-points-forts-bold">
                    <div class="nos-points-forts-bold-name">Nos points forts</div>
                    <div class="nos-points-forts-number">03</div>
                </div>
            </div>
            <div class="n-p-f-bottom-slide">
                <ul>
                    <li class="first-one with-opacity">
                        Satisfaction client <span class="showed-arrow"><img src="<?php echo get_bloginfo('template_url') ?>/images/arrow-hover.svg" /></span>
                    </li>
                    <li class="second-one">
                        Economie de temps<span><img src="<?php echo get_bloginfo('template_url') ?>/images/arrow-hover.svg" /></span>
                    </li>
                    <li class="third-one">
                        Eco-Friendly<span><img src="<?php echo get_bloginfo('template_url') ?>/images/arrow-hover.svg" /></span>
                    </li>
                </ul>
            </div>
            <div class="nos-points-forts-bottom">
                <div class="n-p-f-bottom-text first-one-text active-text">
                    Votre satisfaction est le principe directeur de notre société. C’est pour cette raison que tous nos produits ainsi que nos services sont garantis (Voir conditions). Il est fondamental pour nous, de vous proposer les produits d’une excellente qualité.
                </div>
                <div class="n-p-f-bottom-text second-one-text">
                    URBAK vous garantit les prix les plus attractifs en Suisse romande !
                    Vous avez trouvé un tarif plus bas ailleurs ?
                    Nous nous alignons dessus et vous offrons 10% de réduction. (Voir conditions)
                </div>
                <div class="n-p-f-bottom-text third-one-text">
                    URBAK s’efforce de contribuer au bien-être environnemental. L’équipe URBAK a en effet pour objectif de réduire les déchets électroniques en offrant la possibilité d’acheter des smartphones reconditionnés. De plus, tous nos spécialistes se déplacent en EcoBike 100% écologique.
                    Tous les écrans, les batteries et autres composants usagés et issus des réparations sont entièrement recyclés.
                </div>
            </div>
        </div>
        <div class="nos-points-forts-image">
            <img src="<?php echo get_bloginfo('template_url') ?>/images/nos-points-forts-image.svg" />
        </div>
    </div>
</section>
<div class="absolute-rectangle">
    <img src="<?php echo get_bloginfo('template_url') ?>/images/rectangle.svg" />
</div>
<section class="s-reconditiones">
    <div class="container">
        <div class="s-reconditiones-content">
            <div class="s-reconditiones-top">
                <div class="s-r-top-pagename">Home</div>
                <div class="s-r-top-section">
                    <div class="s-r-top-section-name">Smartphones reconditiones</div>
                    <div class="s-r-top-section-number">04</div>
                </div>
            </div>
            <div class="s-reconditiones-mid">
                URBAK propose également la vente de smartphones reconditionnés GARANTIE 6 mois aux meilleurs PRIX.</br></br>

                De plus, l’équipe URBAK met en place la redistribution d’une partie des bénéfices de la vente des smartphones reconditionnés à une ONG afin d’offrir des repas au plus démunis.

            </div>
            <div class="s-reconditiones-bot">
                <a href="<?php echo get_option('home') ?>/acheter">Acheter un smartphone</a>
            </div>
        </div>
    </div>
</section>
<section class="micro-s">
    <div class="container">
        <div class="micro-s-content">
            <div class="micro-s-top">
                <div class="micro-s-pagename">Home</div>
                <div class="micro-s-section">
                    <div class="micro-s-section-name">Micro-soudure </div>
                    <div class="micro-s-section-number">05</div>
                </div>
            </div>
            <div class="micro-s-mid">
                Vous avez fait tomber votre téléphone dans l’eau ?</br></br>
                Votre téléphone ne s’allume plus et il n’y a pas de défaut apparent ?
            </div>
            <div class="micro-s-bot">
                <a href="<?php echo get_option('home') ?>/contact">Contactez-nous</a>
            </div>
        </div>
        <div class="micro-s-image">
            <img src="<?php echo get_bloginfo('template_url') ?>/images/micro-s.svg" />
        </div>
    </div>
</section>
<section class="ecobike">
    <div class="container">
        <div class="ecobike-image">
            <img src="<?php echo get_bloginfo('template_url') ?>/images/ecobike.svg" />
        </div>
        <div class="ecobike-content">
            <div class="ecobike-top">
                <div class="ecobike-pagename">Home</div>
                <div class="ecobike-section">
                    <div class="ecobike-section-name">EcoBike </div>
                    <div class="ecobike-section-number">06</div>
                </div>
            </div>
            <div class="ecobike-mid">Un tout nouveau concept, n’existant nulle part ailleurs, prend naissance à Genève : L’EcoBike. À savoir un service de réparation de Smartphones à domicile en déplacement entièrement écologique.</br></br></br>

                Ce dernier permettra de faciliter la vie de la communauté de la cité de Calvin. En effet, plus besoin de vous déplacer, l’équipe URBAK vient à vous !</div>
            <div class="ecobike-bot">
                <a>Prendre rendez-vous</a>
            </div>
        </div>
    </div>
</section>
<section class="partner">
    <div class="container">
        <div class="partner-top">
            <div class="partner-top-pagename">Home</div>

            <div class="partner-section">
                <div class="partner-section-name">Devenez un partenaire</div>
                <div class="partner-section-number">07</div>
            </div>
        </div>
        <div class="partner-content">
            <div class="partner-form">
                <?php echo apply_shortcodes('[contact-form-7 id="532" title="Homepage Contact Form"]'); ?>

                

            </div>
        </div>
    </div>
</section>
<section class="assurance">
    <div class="container">
        <div class="assurance-content">
            <div class="assurance-top">
                <div class="assurance-top-pagename">Home</div>

                <div class="assurance-section">
                    <div class="assurance-section-name">Assurance</div>
                    <div class="assurance-section-number">08</div>
                </div>
            </div>
            <div class="assurance-mid">
                <div class="a-m-text">
                    Assurez-vous</br> avec la baloise.
                </div>
                <div class="a-m-image">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/assurance-mid.svg" />
                </div>
            </div>
            <div class="assurance-bot">
                <a href="<?php echo get_option('home') ?>/acheter">Acheter un smartphone</a>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>
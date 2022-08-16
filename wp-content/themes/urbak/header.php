<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="robots" content="index,follow" />

    <title>
        <?php
        global $page, $paged;
        wp_title('|', true, 'right');
        // Add the blog name.
        bloginfo('name');
        //Add the blog description for the home/fron page.
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page())) {
            echo " | $site_description";
        }
        ?>
    </title>
    <?php wp_head(); ?>


</head>

<body id="body" <?php body_class(); ?>>
    <header class="header <?= $header_color_class ?>">

        <div class="container">
            <div class="header-wrapper">
                <div class="header-logo">
                    <a href="<?= get_home_url() ?>">
                        <img src="<?php echo get_bloginfo('template_url') ?>/images/logo.svg" />
                    </a>
                </div>
                <div class="header-menu">
                    <?php
                    echo wp_nav_menu(array(
                        'menu'   => 'Primary',
                    ));
                    ?>
                </div>
                <div class="header-icons">
                    <div class="search-icon">
                        <img class="search-menu-trigger-open" src="<?php echo get_bloginfo('template_url') ?>/images/search-icon1.svg" />
                        <img class="search-menu-trigger-close" src="<?php echo get_bloginfo('template_url') ?>/images/close-icon.svg" />
                    </div>
                    <div class="cart-icon"><a href="<?php echo get_home_url() ?>/cart"><img src="<?php echo get_bloginfo('template_url') ?>/images/cart-icon.svg" /></a></div>
                </div>
            </div>
            <div class="header-extra-menu">
                <div class="extra-menu">Menu</div>
            </div>
            <div class="extra-search">
                <form role="search" method="get" id="searchform" class="extra-search-content" action="http://localhost/urbak/">
                    <input type="text" value="" placeholder="Search..." name="s" id="s">
                    <button type="submit" class="search-input-icon"><img src="<?php echo get_bloginfo('template_url') ?>/images/search-icon1.svg" /></button>
                </form>
            </div>
        </div>
    </header>
    <div class="search-background">
        <div class="search-background-container">
        </div>
    </div>
    <div id="fsmenu" class="fs-menu">
        <div class="fsmenu-list-wrapper">
            <div class="fsmenu-list">
                <?php
                echo wp_nav_menu(array(
                    'menu'   => 'Primary',
                ));
                ?>
            </div>
            <div class="fsmenu-close" id="fsmenuclose"><span>Fermer</span></div>
        </div>
        <div class="fsmenu-infos">
            <div class="fsmenu-languages">Fr / En</div>
            <div class="fsmenu-logo"><img src="<?php echo get_bloginfo('template_url') ?>/images/fsmenu-logo.svg" /></div>
            <div class="fsmenu-telephone">+41 22 314 56 06</div>
            <div class="fsmenu-email">info@urbak.ch</div>
            <div class="fsmenu-address">Rue des Deux-Ponts 29,</br>1205 Genève, Suisse</div>
            <div class="fsmenu-social">
                <div class="fsmenu-insta"><img src="<?php echo get_bloginfo('template_url') ?>/images/fsmenu-insta.svg" /></div>
                <div class="fsmenu-fb"><img src="<?php echo get_bloginfo('template_url') ?>/images/fsmenu-fb.svg" /></div>
            </div>
        </div>

        <div class="footer-onmenu" id="onmenu">
            <div class="onmenu-first-col">
                <div class="onmenu-logo">
                    <img src="<?php echo get_bloginfo('template_url') ?>/images/onmenu-logo.svg" />
                </div>
                <div class="onmenu-infos">
                    <div class="onmenu-info">+41 22 314 56 06</div>
                    <div class="onmenu-info">info@urbak.ch</div>
                    <div class="onmenu-info">Rue des Deux-Ponts 29, </br>
                        1205 Genève, Suisse</div>
                </div>
            </div>
            <div class="onmenu-second-col">
                <div class="onmenu-languages">Fr/En</div>
                <div class="onmenu-socials">
                    <div class="onemenu-insta"><img src="<?php echo get_bloginfo('template_url') ?>/images/insta.svg" /></div>
                    <div class="onmenu-fb"><img src="<?php echo get_bloginfo('template_url') ?>/images/fb.svg" /></div>
                </div>
            </div>
        </div>

    </div>
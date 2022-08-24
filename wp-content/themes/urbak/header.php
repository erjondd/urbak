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
                    <div class="cart-icon" id="cart-trigger">
                        <img class="cart-icon-img" src="<?php echo get_bloginfo('template_url') ?>/images/cart-icon.svg" />
                        <?php if (WC()->cart->get_cart_contents_count() > 0) { ?>
                            <span class="cart-icon-img-count"><?= WC()->cart->get_cart_contents_count(); ?></span>
                        <?php } ?>
                        <div class="cart-dropdown <?= WC()->cart->get_cart_contents_count() === 0 ? 'cart-dropdown-no-items' : '' ?>">

                            <?php if (!WC()->cart->is_empty()) : ?>

                                <ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
                                    <?php
                                    do_action('woocommerce_before_mini_cart_contents');

                                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                        $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                        $category_id_last = $_product->category_ids[count($_product->category_ids) - 2];
                                        $category_term = get_term_by('id', $category_id_last, 'product_cat', 'ARRAY_A');
                                        $category_name = $category_term["name"];
                                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                            $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                                            $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                            $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                    ?>
                                            <li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">

                                                <?php if (empty($product_permalink)) : ?>
                                                    <?php echo $thumbnail;
                                                    echo "<div class='mini-cart-product-label'>";
                                                    echo "<div class='mini-cart-row'>";
                                                    echo "<span class='mini-cart-product-cat'>" . $category_name . "</span>";
                                                    echo "<span class='mini-cart-product-name'>" . wp_kses_post($product_name) . "</span>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                    echo "</div>";
                                                    echo "<div class='mini-cart-row'>";
                                                    echo "<div class='mini-cart-qty'>x" . $cart_item['quantity'] . " </div>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    ?>
                                                <?php else : ?>
                                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                                        <?php echo $thumbnail;
                                                        echo "<div class='mini-cart-product-label'>";
                                                        echo "<div class='mini-cart-row'>";
                                                        echo "<span class='mini-cart-product-cat'>" . $category_name . "</span>";
                                                        echo "<span class='mini-cart-product-name'>" . wp_kses_post($product_name) . "</span>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                        echo "</div>";
                                                        echo "<div class='mini-cart-row'>";
                                                        echo "<div class='mini-cart-qty'>x" . $cart_item['quantity'] . " </div>";
                                                        echo "</div>";
                                                        echo "</div>";
                                                        ?>
                                                    </a>
                                                <?php endif; ?>
                                                <?php
                                                //  echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                echo "<span class='mini-cart-price'>" . wc_price($cart_item["line_subtotal"]) . "</span>";

                                                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                    'woocommerce_cart_item_remove_link',
                                                    sprintf(
                                                        '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                                                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                        esc_attr__('Remove this item', 'woocommerce'),
                                                        esc_attr($product_id),
                                                        esc_attr($cart_item_key),
                                                        esc_attr($_product->get_sku())
                                                    ),
                                                    $cart_item_key
                                                );
                                                ?>
                                            </li>
                                    <?php
                                        }
                                    }

                                    do_action('woocommerce_mini_cart_contents');
                                    ?>
                                </ul>

                                <p class="woocommerce-mini-cart__total total">
                                    <?php
                                    /**
                                     * Hook: woocommerce_widget_shopping_cart_total.
                                     *
                                     * @hooked woocommerce_widget_shopping_cart_subtotal - 10
                                     */
                                    do_action('woocommerce_widget_shopping_cart_total');
                                    ?>
                                </p>

                                <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

                                <p class="woocommerce-mini-cart__buttons buttons"><?php do_action('woocommerce_widget_shopping_cart_buttons'); ?></p>

                                <?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

                            <?php else : ?>

                                <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

                            <?php endif; ?>

                        </div>
                    </div>
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
            <div class="fsmenu-languages"><?php echo do_shortcode('[gtranslate]'); ?></div>
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
<?php

get_header();

/*
Template Name: Contact Template
*/

?>

<section class="contact">
    <div class="container">
        <div class="contact-all-text">
            <div class="contact-big-text">Contact</div>
            <div class="contact-infos">
                <div class="telephone">+41 22 314 56 06</div>
                <div class="email">info@urbak.ch</div>
                <div class="address">Rue des Deux-Ponts 29, </br>
                    1205 Genève, Suisse</div>
                <div class="socials">
                    <div class="instagram"><img src="<?php echo get_bloginfo('template_url') ?>/images/insta-contact.svg" /></div>
                    <div class="facebook"><img src="<?php echo get_bloginfo('template_url') ?>/images/fb-contact.svg" /></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="contact-form">
    <div class="container">
        <div class="contact-form-all-content">
            <div class="contact-form-content">
                <div class="contact-form-wrapper">
                    <div class="contact-wrapper-title">Contactez nous</div>
                <div class="form-input-row ">
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Nom*" name="nom">
                    </div>
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Prénom*" name="prenom">
                    </div>
                </div>
                <div class="form-input-row ">
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="E-mail*" name="e-mail">
                    </div>
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Téléphone" name="telephone">
                    </div>
                </div>
                <div class="form-input-row ">
                <textarea name="message" placeholder="Message" rows="10" cols="30"></textarea>
                </div>
                </div>
                <div class="partner-foot">
                    Envoyer<span> <img src="<?php echo get_bloginfo('template_url') ?>/images/partner-arrow.svg" /></span>
                </div>
            </div>
            <div class="contact-form-picture"><img src="<?php echo get_bloginfo('template_url') ?>/images/contact-pic.jpg" /></div>
        </div>
    </div>
</section>

<?php
get_footer();
?>
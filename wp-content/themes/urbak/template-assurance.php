<?php

get_header();

/*

    Template Name: Assurance Template

    */

?>
<section class="assurance-page">
    <div class="container">

        <div class="assurance-center-text">Assurance</div>

    </div>
    <div class="scroll-down-assurance" id="scroll-down-assurance"><img src="<?php echo get_bloginfo('template_url') ?>/images/scroll-down.png" /></div>
</section>
<section class="assurance-form" id="assurance-form">
    <div class="container">
        <div class="assurance-form-top">
            <div class="assurance-top-pagename">Home</div>

            <div class="assurance-section">
                <div class="assurance-section-name">Assurez vous avec <span><img src="<?php echo get_bloginfo('template_url') ?>/images/assurance-mid.svg" /></span></div>

            </div>
        </div>
        <div class="assurance-form-rows" >
            <div class="assurance-form-info">
                <div class="form-input-row ">
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Nom" name="Nom">
                    </div>
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Prénom" name="Prénom">
                    </div>
                </div>
                <div class="form-input-row ">
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="E-mail" name="E-mail">
                    </div>
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Téléphone" name="Téléphone">
                    </div>
                </div>
                <div class="form-input-row ">
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Date de naissance" name="Date de naissance">
                    </div>
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Nationalité" name="Nationalité">
                    </div>
                </div>
            </div>
            <div class="assurance-form-info">
                <div class="form-input-row">
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Pays" name="country">
                    </div>
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="Ville" name="city">
                    </div>
                </div>
                <div class="form-input-row ">
                    <div class="form-input form-input-full">
                        <input type="text" placeholder="Adresse" name="address">
                    </div>
                </div>
                <div class="form-input-row">
                    <div class="form-input form-input-half">
                        <input type="text" placeholder="N° de rue" name="street-number">
                    </div>
                    <div class="form-input form-input-half ">
                        <select name="code-postal" placeholder="Code postal" class="full-width">
                            <option value="123">Code Postal</option>
                            
                        </select>
                    </div>
                </div>
            </div>
            <div class="assurance-form-info">
                <div class="form-input-row ">
                <div class="form-input">
                    <label>Appareil</label>
                        <select name="code-postal" placeholder="Marque">
                            <option value="Marque">Marque</option>
                           
                        </select>
                        <select name="code-postal" placeholder="Modèle"  class="special-select">
                            <option value="Modèle">Modèle</option>
                            
                        </select>
                    </div>
                   
                </div>
                <div class="form-input-row">
                <div class="form-input form-input-half">
                            <label>
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                Tarif 1 personne : 5.50 CHF.-/mois (TTC)
                            </label>
                        </div>
                        <div class="form-input form-input-half">
                            <label>
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                Tarif 2 personnes : 10.50 CHF.-/mois (TTC) 
                            </label>
                        </div>
                </div>
            </div>
        </div>
        <div class="assurance-page-bottom">
        Envoyer<span> <img src="<?php echo get_bloginfo('template_url') ?>/images/partner-arrow.svg" /></span>
        </div>
    </div>
</section>

<?php
get_footer();
?>
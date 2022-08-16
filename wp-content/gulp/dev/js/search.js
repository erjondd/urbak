jQuery(document).ready(function() {

    jQuery('.search-menu-trigger-open').on('click', function(){
        console.log(jQuery('body'))
        jQuery('body').addClass('search-menu-open');
    });

    jQuery('.search-menu-trigger-close').on('click', function(){
        jQuery('body').removeClass('search-menu-open');
    });

});
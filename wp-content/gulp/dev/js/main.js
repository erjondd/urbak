jQuery(document).ready(function () {
  console.log("ready");

  // Menu list on hover add color
  jQuery(document).on({
    mouseenter: function () {
       console.log(this);
      var _this = jQuery(this);
      console.log(_this);
        //stuff to do on mouse enterurba
    },
    mouseleave: function () {
        //stuff to do on mouse leave
    }
}, ".fsmenu-list ul li"); //pass the element as an argument to .on

  // jQuery(".fsmenu-list ul li").hover(
  //   () => {
  //     console.log(this)
  //     var _this = jQuery(this);
  //     console.log(_this)
  //   },
  //   () => {}
  // );


  
  jQuery(".extra-menu").on("click", function () {
    const _elm = document.getElementById("fsmenu");
 
    console.log(_elm);
    if (_elm !== null)
    _elm.classList.add("is-open");
  });
  jQuery("#fsmenuclose").on("click", function () {
    const _elm = document.getElementById("fsmenu");

    if (_elm !== null)
    _elm.classList.remove("is-open");
  });

  jQuery(".extra-menu").on("click", function () {
    const _elm = document.getElementById("onmenu");
 
 
    if (_elm !== null)
    _elm.classList.add("is-open");
  });

  var htmlHeight = jQuery("html").innerHeight();
  var bodyHeight = jQuery("body").innerHeight();

  if (htmlHeight > bodyHeight) {
    jQuery(".footer").addClass("footer-absolute");
  }

  jQuery(window).on("resize", function () {
    var win = jQuery(this);
    if (win.width() <= 768) {
      jQuery(".footer").removeClass("footer-absolute");
      jQuery(".footer").addClass("footer-relative");
    }
  });

  //Homepage Slider
  var homepageSwiper = new Swiper(".swiper-container-home", {
    slidesPerView: 1,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    scrollbar: {
      el: ".swiper-scrollbar",
      hide: false,
    },
  });

  homepageSwiper.on("slideChange", function () {
    var index = homepageSwiper.activeIndex;
    console.log(index);
    console.log(jQuery(".swiper-container-home .swiper-slide").eq(index));
  });
  // //About Us Slider
  // var aboutSwiper = new Swiper('.swiper-container-about', {
  //     slidesPerView: 1,
  //     navigation: {
  //         nextEl: '.swiper-button-next',
  //         prevEl: '.swiper-button-prev',
  //     },
  //     on: {
  //         slideChangeTransitionEnd: function (i, elm) {
  //             var allModules = jQuery('.module');
  //             // console.log(i, elm);
  //             allModules.each(function (i, elm) {
  //                 if (jQuery(elm).visible(true)) {
  //                     jQuery(elm).addClass('module-ready');
  //                 }
  //             });
  //         }
  //     }

  // });

  // //Team Slider
  // var teamSwiper = new Swiper('.swiper-container-team', {
  //     init: true,
  //     effect: 'fade',
  //     grabCursor: true,
  //     navigation: {
  //         nextEl: '.swiper-button-next',
  //         prevEl: '.swiper-button-prev',
  //     },
  // });

  // //About Us Project Counter

  // var spanCounter = jQuery('.section-projects');
  // jQuery(document).on('scroll', function () {
  //     if (jQuery(spanCounter).visible(true) && !spanCounter.hasClass('start')) {
  //         spanCounter.addClass('start');

  //         jQuery('.count').each(function () {
  //             jQuery(this).prop('Counter', 0).animate({
  //                 Counter: jQuery(this).text()
  //             }, {
  //                 duration: 5000,
  //                 easing: 'swing',
  //                 step: function (now) {
  //                     jQuery(this).text(Math.ceil(now));
  //                 }
  //             });
  //         });
  //     }

  // });

  //Module Add On First Window
  var allModules = jQuery(".module");
  allModules.each(function (i, elm) {
    if (jQuery(elm).visible(true)) {
      jQuery(elm).addClass("module-ready");
    }
  });

  //Module Add Scroll
  var allModules = jQuery(".module");
  jQuery(document).on("scroll", function () {
    var scroll = jQuery(document).scrollTop();
    allModules.each(function (i, elm) {
      if (jQuery(elm).visible(true)) {
        jQuery(elm).addClass("module-ready");
      }
    });

    // console.log(scroll);
  });
});

//To add a background color on header on Scroll
jQuery(window).on("scroll", function () {
  if (jQuery(window).scrollTop() > 80) {
    jQuery(".header").addClass("dark-background-on-scroll");
  } else {
    jQuery(".header").removeClass("dark-background-on-scroll");
  }
});

jQuery(".scroll-down").click(function () {
  jQuery("html, body").animate(
    {
      scrollTop: jQuery("#home-slider").offset().top,
    },
    2000
  );
});




jQuery(document).on({
  mouseenter: function () {

      //stuff to do on mouse enter
  },
  mouseleave: function () {
      //stuff to do on mouse leave
  }
});



jQuery(document).ready(function(){
  jQuery(".color-FDCE2C a").mouseenter(function(){
    jQuery(".color-FDCE2C a").css("text-decoration", "underline");
    jQuery(".fsmenu-list-wrapper").css("background-color","#FDCE2C");
    jQuery(".color-FDCE2C a").css("color", "white");

  });
  jQuery(".color-FDCE2C a").mouseleave(function(){
    jQuery(".color-FDCE2C a").css("text-decoration", "none");
    jQuery(".fsmenu-list-wrapper").css("background-color","black");
  });
});

jQuery(document).ready(function(){
  jQuery(".color-2562EF  a").mouseenter(function(){
    jQuery(".color-2562EF  a").css("text-decoration", "underline");
    jQuery(".fsmenu-list-wrapper").css("background-color","#2562EF ");
    jQuery(".color-2562EF a").css("color", "white");

  });
  jQuery(".color-2562EF  a").mouseleave(function(){
    jQuery(".color-2562EF  a").css("text-decoration", "none");
    jQuery(".fsmenu-list-wrapper").css("background-color","black");
  });
});

jQuery(document).ready(function(){
  jQuery(".color-FF6100 a").mouseenter(function(){
    jQuery(".color-FF6100 a").css("text-decoration", "underline");
    jQuery(".fsmenu-list-wrapper").css("background-color","#FF6100");
    jQuery(".color-FF6100 a").css("color", "white");

  });
  jQuery(".color-FF6100 a").mouseleave(function(){
    jQuery(".color-FF6100 a").css("text-decoration", "none");
    jQuery(".fsmenu-list-wrapper").css("background-color","black");
  });
});

jQuery(document).ready(function(){
  jQuery(".color-D13F7E a").mouseenter(function(){
    jQuery(".color-D13F7E a").css("text-decoration", "underline");
    jQuery(".fsmenu-list-wrapper").css("background-color","#D13F7E");
    jQuery(".color-D13F7E a").css("color", "white");

  });
  jQuery(".color-D13F7E a").mouseleave(function(){
    jQuery(".color-D13F7E a").css("text-decoration", "none");
    jQuery(".fsmenu-list-wrapper").css("background-color","black");
  });
});

jQuery(document).ready(function(){
  jQuery(".color-000000 a").mouseenter(function(){
    jQuery(".color-000000 a").css("text-decoration", "underline");
    jQuery(".fsmenu-list-wrapper").css("background-color","#000000 ");
    jQuery(".color-000000 a").css("color", "white");

  });
  jQuery(".color-000000 a").mouseleave(function(){
    jQuery(".color-000000 a").css("text-decoration", "none");
    jQuery(".fsmenu-list-wrapper").css("background-color","black");
  });
});
jQuery(document).ready(function(){
  jQuery(".contact-menulink a").mouseenter(function(){
    jQuery(".contact-menulink a").css("text-decoration", "underline");
    jQuery(".fsmenu-list-wrapper").css("background-color","#000000 ");
    jQuery(".contact-menulink a").css("color", "white");

  });
  jQuery(".contact-menulink a").mouseleave(function(){
    jQuery(".contact-menulink a").css("text-decoration", "none");
    jQuery(".fsmenu-list-wrapper").css("background-color","black");
  });
});
jQuery(document).ready(function(){
  jQuery(".acceuil-menulink a").mouseenter(function(){
    jQuery(".acceuil-menulink a").css("text-decoration", "underline");
    jQuery(".fsmenu-list-wrapper").css("background-color","#000000 ");
    jQuery(".acceuil-menulink a").css("color", "white");

  });
  jQuery(".acceuil-menulink a").mouseleave(function(){
    jQuery(".acceuil-menulink a").css("text-decoration", "none");
    jQuery(".fsmenu-list-wrapper").css("background-color","black");
  });
});



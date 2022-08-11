jQuery(document).ready(function () {
  jQuery(".extra-menu").on("click", function () {
    const _fsmenu = document.getElementById("fsmenu");
    const _body = document.getElementById("body");
    const _onMenu = document.getElementById("onmenu");

    _body.classList.add("menu-is-open");
    if (_onMenu !== null) _onMenu.classList.add("is-open");
    if (_fsmenu !== null) _fsmenu.classList.add("is-open");
  });

  jQuery("#fsmenuclose").on("click", function () {
    const _elm = document.getElementById("fsmenu");
    const _body = document.getElementById("body");
    _body.classList.remove("menu-is-open");
    if (_elm !== null) _elm.classList.remove("is-open");
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

  if ($("body").hasClass("page-id-185")) {
    $("header").addClass("black-menu");
  }
  if ($("body").hasClass("page-id-201")) {
    $("header").addClass("black-menu");
  }
  if ($("body").hasClass("page-id-210")) {
    $("header").addClass("black-menu");
  }
  if ($("body").hasClass("page-id-220")) {
    $("header").addClass("black-menu");
  }
  if ($("body").hasClass("single-product")) {
    $("header").addClass("black-fonts");
  }
  if ($("body").hasClass("page-id-201")) {
    $("header").addClass("black-menu");
  }
  if ($("body").hasClass("page-id-210")) {
    $("header").addClass("black-menu");
  }
  if ($("body").hasClass("page-id-185")) {
    $("header").addClass("black-menu");
  }
  



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
  });

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
  });
});

jQuery(".scroll-down").click(function () {
  jQuery("html, body").animate(
    {
      scrollTop: jQuery("#home-slider").offset().top,
    },
    1000
  );
});

jQuery("#scroll-down-assurance").click(function () {
  jQuery("html, body").animate(
    {
      scrollTop: jQuery("#assurance-form").offset().top,
    },
    1000
  );
});

jQuery("#scroll-down-repetation").click(function () {
  jQuery("html, body").animate(
    {
      scrollTop: jQuery("#repetation-phone").offset().top,
    },
    1000
  );
});

jQuery(document).ready(function () {
  jQuery(".color-FDCE2C a").mouseenter(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "#FDCE2C");
    jQuery(".fsmenu-list ul").addClass("is-hover");
    jQuery(".color-FDCE2C a").css("color", "white");
  });
  jQuery(".color-FDCE2C a").mouseleave(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "black");
    jQuery(".fsmenu-list ul").addClass("is-hover");
  });
});

jQuery(document).ready(function () {
  jQuery(".color-2562EF  a").mouseenter(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "#2562EF ");
    jQuery(".color-2562EF a").css("color", "white");
  });
  jQuery(".color-2562EF  a").mouseleave(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "black");
  });
});

jQuery(document).ready(function () {
  jQuery(".color-FF6100 a").mouseenter(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "#FF6100");
    jQuery(".color-FF6100 a").css("color", "white");
  });
  jQuery(".color-FF6100 a").mouseleave(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "black");
  });
});

jQuery(document).ready(function () {
  jQuery(".color-D13F7E a").mouseenter(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "#D13F7E");
    jQuery(".color-D13F7E a").css("color", "white");
  });
  jQuery(".color-D13F7E a").mouseleave(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "black");
  });
});

jQuery(document).ready(function () {
  jQuery(".color-000000 a").mouseenter(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "#000000 ");
    jQuery(".color-000000 a").css("color", "white");
  });
  jQuery(".color-000000 a").mouseleave(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "black");
  });
});
jQuery(document).ready(function () {
  jQuery(".contact-menulink a").mouseenter(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "#000000 ");
    jQuery(".contact-menulink a").css("color", "white");
  });
  jQuery(".contact-menulink a").mouseleave(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "black");
  });
});
jQuery(document).ready(function () {
  jQuery(".acceuil-menulink a").mouseenter(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "#000000 ");
    jQuery(".acceuil-menulink a").css("color", "white");
  });
  jQuery(".acceuil-menulink a").mouseleave(function () {
    jQuery(".fsmenu-list-wrapper").css("background-color", "black");
  });
});

jQuery(document).ready(function () {
  jQuery(".reperation-phone-pieces-list ul li").click(function () {
    const id = this.id;
    const _class = "." + id;
    jQuery(".reperation-phone-pieces-list ul li").removeClass("active");
    jQuery(".reperation-phone-pieces-picture span").removeClass("active");
    jQuery(_class).addClass("active");
    jQuery(this).addClass("active");
  });
});

function genColumn(type, title) {
  var wrapper = document.getElementById("vendre-content");
  var elementString = jQuery.parseHTML(
    `<div class="vendre-content-form-output">` +
      `<span class="vendre-title">${type}</span>` +
      `<span class="vendre-value">${title}</span>` +
      `</div>`
  );
  jQuery(wrapper).append(elementString);
}

// vendre form javascript
jQuery("#wpforms-form-226 button.wpforms-page-next").click(function () {
  const _this = jQuery(this);

  const dataPage = _this.attr("data-page");

  switch (dataPage) {
    case "1":
      var marque = document.getElementById("wpforms-226-field_3").value;
      var modele = document.getElementById("wpforms-226-field_4").value;
      var modeleOptions = document.querySelectorAll(
        "#wpforms-226-field_4 option"
      )[modele].innerHTML;
      console.log(modeleOptions[modele].innerHTML);
      var capacity = document.getElementById("wpforms-226-field_7").value;
      genColumn("Marque", marque);
      genColumn("Modele", modeleOptions);
      genColumn("Capacity", capacity);
      break;
    case "2":
      var votre = document.querySelectorAll(
        "#wpforms-226-field_12 input:checked"
      );
      if(typeof votre !== "undefined"){
        var votreSelected = votre[0].labels[0].innerHTML;
        genColumn("Votre", votreSelected);
      }
      break;
    case "3":
      var ekran = document.querySelectorAll(
        "#wpforms-226-field_14 input:checked"
      );
    if(typeof ekran !== "undefined"){
      var ekran = ekran[0].labels[0].innerHTML;
      genColumn("Ekran", ekran);
    }
  

      break;
    case "4":
      var coque = document.querySelectorAll(
        "#wpforms-226-field_17 input:checked"
      );
    if(typeof coque !== "undefined"){
      var coque = coque[0].labels[0].innerHTML;
      genColumn("Coque", coque);
    }
      break;
    case "5":
      var fonctionnel = document.querySelectorAll(
        "#wpforms-226-field_19 input:checked"
      );
    if(typeof fonctionnel !== "undefined"){
      var fonctionnel = fonctionnel[0].labels[0].innerHTML;
      genColumn("Fonctionnel", fonctionnel);
    }
      break;
    case "6":
      break;

    default:
      break;
  }
});

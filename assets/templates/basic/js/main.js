(function ($) {
  "user strict";

  // preloader
  $(".preloader").delay(800).animate({
    "opacity": "0"
  }, 800, function () {
      $(".preloader").css("display", "none");
  });

// wow
if ($('.wow').length) {
  var wow = new WOW({
    boxClass: 'wow',
    // animated element css class (default is wow)
    animateClass: 'animated',
    // animation css class (default is animated)
    offset: 0,
    // distance to the element when triggering the animation (default is 0)
    mobile: false,
    // trigger animations on mobile devices (default is true)
    live: true // act on asynchronously loaded content (default is true)
  });
  wow.init();
}

//Create Background Image
(function background() {
  let img = $('.bg_img');
  img.css('background-image', function () {
    var bg = ('url(' + $(this).data('background') + ')');
    return bg;
  });
})();


// header-fixed
var fixed_top = $(".header-section");
$(window).on("scroll", function(){
    if( $(window).scrollTop() > 100){  
        fixed_top.addClass("animated fadeInDown header-fixed");
    }
    else{
        fixed_top.removeClass("animated fadeInDown header-fixed");
    }
});

// navbar-click
$(".navbar li a").on("click", function () {
  var element = $(this).parent("li");
  if (element.hasClass("show")) {
    element.removeClass("show");
    element.find("li").removeClass("show");
  }
  else {
    element.addClass("show");
    element.siblings("li").removeClass("show");
    element.siblings("li").find("li").removeClass("show");
  }
});

// main wrapper calculator
var bodySelector = document.querySelector('body');
var header = document.querySelector('.header-section');
var footer = document.querySelector('.footer-area');

(function(){
  if(bodySelector.contains(header) && bodySelector.contains(footer)){
    var headerHeight = document.querySelector('.header-section').clientHeight;
    var footerHeight = document.querySelector('.footer-area').clientHeight;
    

    // if header isn't fixed to top
    var totalHeight = parseInt( headerHeight, 10 ) + parseInt( footerHeight, 10 ) + 'px'; 
    var minHeight = '100vh';
    document.querySelector('.page-wrapper').style.minHeight = `calc(${minHeight} - ${totalHeight})`;
  }
})();

// scroll-to-top
var ScrollTop = $(".scrollToTop");
$(window).on('scroll', function () {
  if ($(this).scrollTop() < 100) {
      ScrollTop.removeClass("active");
  } else {
      ScrollTop.addClass("active");
  }
});

// slider
var swiper = new Swiper('.order-slider', {
  slidesPerView: 5,
  spaceBetween: 10,
  loop: true,
  navigation: {
    nextEl: '.slider-next',
    prevEl: '.slider-prev',
  },
  breakpoints: {
    1199: {
      slidesPerView: 4,
    },
    991: {
      slidesPerView: 3,
    },
    767: {
      slidesPerView: 2,
    },
    575: {
      slidesPerView: 2,
    },
    400: {
      slidesPerView: 1,
    },
  }
});

var swiper = new Swiper('.food-cart-slider', {
  slidesPerView: 1,
  spaceBetween: 10,
  loop: true,
  navigation: {
    nextEl: '.slider-next',
    prevEl: '.slider-prev',
  },
  breakpoints: {
    1199: {
      slidesPerView: 1,
    },
    991: {
      slidesPerView: 1,
    },
    767: {
      slidesPerView: 1,
    },
    575: {
      slidesPerView: 1,
    },
    400: {
      slidesPerView: 1,
    },
  }
});

var swiper = new Swiper('.chef-slider', {
  slidesPerView: 5,
  spaceBetween: 30,
  loop: true,
  navigation: {
    nextEl: '.slider-next',
    prevEl: '.slider-prev',
  },
  autoplay: {
    speed: 1000,
    delay: 3000,
  },
  speed: 1000,
  breakpoints: {
    1199: {
      slidesPerView: 4,
    },
    991: {
      slidesPerView: 3,
    },
    767: {
      slidesPerView: 2,
    },
    575: {
      slidesPerView: 2,
    },
    400: {
      slidesPerView: 1,
    },
  }
});

// footer-toggle
$('.footer-toggle').on('click', function (e) {
  var element = $(this).parent('.footer-wrapper');
  if (element.hasClass('open')) {
    element.removeClass('open');
    element.find('.footer-bottom-area').removeClass('open');
    element.find('.footer-bottom-area').slideUp(300, "swing");
  } else {
    element.addClass('open');
    element.children('.footer-bottom-area').slideDown(300, "swing");
    element.siblings('.footer-wrapper').children('.footer-bottom-area').slideUp(300, "swing");
    element.siblings('.footer-wrapper').removeClass('open');
    element.siblings('.footer-wrapper').find('.footer-toggle').removeClass('open');
    element.siblings('.footer-wrapper').find('.footer-bottom-area').slideUp(300, "swing");
  }
});

$('.side-sidebar-close-btn').on('click', function (e) {
  $('.cart-sidebar-area').removeClass('active');
});


/* ---------------------------------------------
  ## Draw Count Down
--------------------------------------------- */
var $offerCountdown5 = $(".draw-countdown");
if ($offerCountdown5.length) {
  $offerCountdown5.each(function () {
    var jc_year = parseInt($(this).attr("data-year"));
    if (!jc_year) jc_year = 1;
    var jc_month = parseInt($(this).attr("data-month"));
    if (!jc_month) jc_month = 1;
    var jc_day = parseInt($(this).attr("data-day"));
    if (!jc_day) jc_day = 1;
    var jc_hour = parseInt($(this).attr("data-hour"));
    if (!jc_hour) jc_hour = 1;


    $.syotimerLang.neng = {
      second: ['sec', 'sec'],
      minute: ['min', 'min'],
      hour: ['hrs', 'hrs'],
      day: ['days', 'days'],
    };

    $offerCountdown5.syotimer({
      lang: 'neng',
      year: jc_year,
      month: jc_month,
      day: jc_day,
      hour: jc_hour,
      minute: 59
    });
  });
}
  

})(jQuery);
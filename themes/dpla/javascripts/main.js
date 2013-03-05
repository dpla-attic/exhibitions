(function($) {


	$('.homeslide').flexslider({
	    animation: "slide",
	    pauseOnHover: true,
      prevText: '<span class="icon-arrow-left" aria-hidden="true"></span>',
      nextText: '<span class="icon-arrow-right" aria-hidden="true"></span>' 
	});

	$('.moreInfo').mouseover(function () {
      $(this).addClass('hover');
      $('.flex-direction-nav a').addClass('hover');
	    $('.flex-active-slide .slideOverlay').addClass('on');
	    $('.flex-active-slide .slideText').addClass('on');
	});

	$('.forgotPassword').click(function() {
		$('.forgotSlide').slideDown();
		$('#cboxLoadedContent, #cboxWrapper, #colorbox, #cboxContent').animate({height: '430px'});
		return false;
	});
	
	$('#cboxOverlay, #cboxClose').click(function() {
		$('.forgotSlide').slideUp();
		$('#cboxLoadedContent, #cboxWrapper, #colorbox, #cboxContent').animate({height: '320px'});
		return false;
	});
	
  $('.moreInfo').toggle(function() {
      $(this).addClass('hover');
      $('.flex-direction-nav a').addClass('hover');
      $('.flex-active-slide .slideOverlay').addClass('on');
      $('.flex-active-slide .slideText').addClass('on');
    }, 
    function() {
      $(this).removeClass('hover');
      $('.flex-direction-nav a').removeClass('hover');
      $('.flex-active-slide .slideOverlay').removeClass('on');
      $('.flex-active-slide .slideText').removeClass('on');
    }
  );

	$('.homeslide').mouseleave(function () {
	    $('.slideOverlay').removeClass('on');
	    $('.slideText').removeClass('on');
      $('.moreInfo').removeClass('hover');
      $('.flex-direction-nav a').removeClass('hover');
	 });

	$('.flex-direction-nav a').click(function () {
	    $('.slideOverlay').removeClass('on');
	    $('.slideText').removeClass('on');
      $('.moreInfo').removeClass('hover');
      $('.flex-direction-nav a').removeClass('hover');
	});

  $('.pop-open').click(function() {
    $('.breadCrumbs li').removeClass('current');
    $(this).next('a').clone().appendTo('.breadCrumbs ul').wrap('<li class="current"></li>');
  });

  $('.collections .pop-columns a').click(function() {
    $('.breadCrumbs li').removeClass('current');
    $(this).clone().appendTo('.breadCrumbs ul').wrap('<li class="current"></li>');
  });


  $('.search-btn').click(function() {
    $('.searchViews, .search-btn').addClass('off');
	$('.searchRowRight form input[type="submit"]').hide();
    $('.searchRowRight form').show().animate({width: '98%'}, 500, function() {
	  $('.searchRowRight form input[type="submit"]').fadeIn(200);
	});
	$('.searchRowRight form input[type="text"]').focus();
    return false;
  });

  $('.searchRowRight form input[type="text"]').blur(function() {
    $('.search-btn, .searchViews').removeClass('off');
	$('.searchRowRight form').attr('style', '');
  });

  $('.head').toggle(function() {
      $(this).next().slideDown();
      $(this).addClass('close');
    }, 
    function() {
      $(this).next().slideUp();
      $(this).removeClass('close');
    }
  );

  ///// REFINE SIDEBAR TOGGLE
  $('#toggle').toggle(function() {

      $('aside').addClass('moveOut');
      $('.slidePopOut').addClass('moveIn');
      $('.map article, .timeContainer').addClass('widthL');
      $('#toggle').html('Refine <span aria-hidden="true" class="icon-arrow-thin-right"></span>');

      $('aside').removeClass('moveIn');
      $('.slidePopOut').removeClass('moveOut');
      $('.map article, .timeContainer').removeClass('widthS');

    }, 
    function() {

      $('aside').addClass('moveIn');
      $('.slidePopOut').addClass('moveOut');
      $('.map article, .timeContainer').addClass('widthS');
      $('#toggle').html('<span aria-hidden="true" class="icon-arrow-thin-left"></span> Refine');

      $('aside').removeClass('moveOut');
      $('.slidePopOut').removeClass('moveIn');
      $('.map article, .timeContainer').removeClass('widthL');

    }
  );

  $('#toggle.Marticle').click( function() {
      $('aside').addClass('moveIn');
      $('.map article, .timeContainer, .slidePopOut').addClass('moveOut');
      $('#toggle.Marticle').hide();
      $('#toggle.Maside').show();

      $('aside').removeClass('moveOut');
      $('.map article, .timeContainer, .slidePopOut').removeClass('moveIn');
  });

  $('#toggle.Maside').click( function() {
      $('aside').addClass('moveOut');
      $('.map article, .timeContainer, .slidePopOut').addClass('moveIn');
      $('#toggle.Marticle').show();
      $('#toggle.Maside').hide();

      $('aside').removeClass('moveIn');
      $('.map article, .timeContainer, .slidePopOut').removeClass('moveOut');
  });

  $('.mapContainer').click(function() {
    $('.mobile-hover').hide();
    $('.map iframe').animate({ height: '300px' }, 'slow');
  });
 
  $('.open').toggle(
    function(){
      $(this).next('.slidingDiv').slideUp();
      $(this).addClass('close');
    },
    function(){
      $(this).next('.slidingDiv').slideDown();
      $(this).removeClass('close');
    }
  );

  $('.pop-columns li').toggle(
    function(){
      $(this).children('ul').slideDown();
    },
    function(){
      $(this).children('ul').slideUp();
    }
  );



  $('.menu-btn').toggle(
    function(){
      $('.topNav, .MainNav').slideDown();
      $(this).html('<span aria-hidden="true" class="icon-arrow-thin-up"></span>');
    },
    function(){
      $('.topNav, .MainNav').slideUp();
      $(this).html('<span aria-hidden="true" class="icon-arrow-thin-down"></span>');
    }
  );

  $('#inline_content .tabs a').click(function() {
    return false;
  });

  /// REFRESH once the width is 680
  var ww = $(window).width();
  var limit = 680;

  function refresh() {
    ww = $(window).width();
    var w =  ww<limit ? (location.reload(true)) :  ( ww>limit ? (location.reload(true)) : ww=limit );
  }

  var tOut;

  $(window).resize(function() {
    var resW = $(window).width();
    clearTimeout(tOut);
    if ( (ww>limit && resW<limit) || (ww<limit && resW>limit) ) {        
        tOut = setTimeout(refresh);
    }
  });


  //LIGHT BOX
  $(".inline").colorbox({inline:true, width:"100%", maxWidth: "1000px", transition: "none"});

  $(".login").colorbox({inline:true, width:"100%", maxWidth: "600px", transition: "none"});

  $(".signUp").colorbox({inline:true, width:"100%", maxWidth: "600px", transition: "none"});

  $('.pop-open').click(function() {
    $('.pop-columns.country').fadeOut(function() {
      $('.pop-columns.states').fadeIn();
    });
  });


/////TIMELINE SETUP

  var slideDistance;
  var endPoint;
  var windowWidth;
  var selectedYear;
  
  // Set defaults
  function initTimeline() {
	  windowWidth = window.innerWidth || document.documentElement.clientWidth;
	  if ($('.decadesView').length) {
		if (windowWidth > 980) {
			//for thin bars
			slideDistance = 8.341;
			endPoint = 91.659;
			initScrub();
		} else {
			//for fat bars
			slideDistance = 0.667;
			endPoint = 99.333;
			initScrub();
		}
	  } else if ($('.yearsView').length) {
		  endPoint = 100;
		  initScrub();
	  }
  }
  initTimeline();
  
  
/////TIMELINE MODULE: SCRUBBER
  function initScrub() {
	if ($('.decadesView').length) {
	  $('.scrubber').slider({
		value: endPoint*1000,
		min: 0,
		max: endPoint*1000,
		slide: function( event, ui ) {
		  var move = ui.value/1000;
		  $('.DecadesDates, .graph').css({ right: move + '%' });
		  if (ui.value == endPoint*1000) {
		  	$('.Decades .next').hide();
		  } else if (ui.value == 0) {
		  	$('.Decades .prev').hide();
		  } else {
			  $('.Decades .prev, .Decades .next').show();
		  };
		},
		change: function( event, ui ) {
          var move = ui.value/1000;
		  if (ui.value == endPoint*1000) {
		  	$('.Decades .next').hide();
		  } else if (ui.value == 0) {
		  	$('.Decades .prev').hide();
		  } else {
			  $('.Decades .prev, .Decades .next').show();
		  };
        }
	  });
	} else if ($('.yearsView').length) {
	  $('.scrubber').slider({
		value: (selectedYear*100000)/1020,
		min: 0,
		max: endPoint*1000
	  });
	  console.log($('.scrubber').slider('value'));
	}
  }
  initScrub();

  $('.scrubber a').append('<span class="arrow"></span>');
  

/////TIMELINE VIEWS
  $('.DecadesTab').click(function() {
    $('.timeContainer').removeClass('yearsView').addClass('decadesView');
	$('.DecadesDates, .graph').attr('style', '');
    $('.timelineContainer').hide();
    $('.Decades').show();
	initTimeline();
    return false;
  });
  $('.yearTab').click(function() {
    return false;
  });
  
  $('.graph li').click(function() {
    $('.timeContainer').removeClass('decadesView').addClass('yearsView');
    $('.Decades').hide();
    $('.timelineContainer').show();
	selectedYear = $(this).index();
	console.log(selectedYear);
	initTimeline();
    return false;
  });


/////TIMELINE MODULE: DECADES

  var moving = false;
  $('.Decades .prev').click(function() {
	if (moving == false) {
	  moving = true;
	  var moveDistance = Math.abs($('article.timeline').offset().left - $('.graph').offset().left);
	  if (moveDistance < $('.graph').width() * (slideDistance/100)) {
		$('.DecadesDates, .graph').animate({ right: '0' }, function() { moving = false; });
		$('.scrubber').slider('value', 0);
	  } else {
		$('.DecadesDates, .graph').animate({ right: '-=' +slideDistance+ '%' }, function() { moving = false; });
		$('.scrubber').slider('value', $('.scrubber').slider('value') - (slideDistance*1000));
	  }
	}
  });

  $('.Decades .next').click(function() {
	if (moving == false) {
	  moving = true;
	  var moveDistance = Math.abs($('.graph').offset().left + $('.graph').outerWidth() - ($('article.timeline').outerWidth() + $('article.timeline').offset().left));
	  if (moveDistance < $('.graph').width() * (slideDistance/100)) {
		$('.DecadesDates, .graph').animate({ right: endPoint + '%' }, function() { moving = false; });
		$('.scrubber').slider('value', endPoint*1000);
	  } else {
		$('.DecadesDates, .graph').animate({ right: '+=' +slideDistance+ '%' }, function() { moving = false; });
		$('.scrubber').slider('value', $('.scrubber').slider('value') + (slideDistance*1000));
	  }
	}
  });
  
  
/////TIMELINE MODULE: YEARS

  $('.timeline-row .next').click(function() {
    if($(this).parent().next().hasClass('timeline-row')) {
      $('.prev, .next').hide();
      $('.timelineContainer').animate({ right: '+=100%' }, 500, function() { 
        $('.prev, .next').show();
      });
    }
  });

  $('.timeline-row .prev').click(function() {
    if($(this).parent().prev().hasClass('timeline-row')) {
      $('.prev, .next').hide();
      $('.timelineContainer').animate({ right: '-=100%' }, 500, function() { 
        $('.prev, .next').show(); 
      });
    }
  });
  
  
// Fix subpixel rounding on timeline for proper alignment of years and bars. Delete when full browser support exists.
  if ($('.timeline').length) {
	var barsCount = 0;
	$('.bars li').each(function() {
	  $(this).css({'margin-right': '-100%','margin-left': (0.906343*barsCount) + '%'});
	  barsCount = barsCount + 1;
	});
	var graphCount = 0;
	$('.graph li').each(function() {
	  $(this).css({'margin-right': '-100%','margin-left': (0.098038*graphCount) + '%'});
	  graphCount = graphCount + 1;
	});
	var decadesCount = 0;
	$('.DecadesDates li').each(function() {
	  $(this).css({'margin-right': '-100%','margin-left': (0.980392*decadesCount - 0.47) + '%'});
	  decadesCount = decadesCount + 1;
	});
  }



////////////IE8 TO FIX PLACE HOLDER

  if (!Modernizr.input.placeholder) {
    $('[placeholder]').focus(function() {
      var input = $(this);
        if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
          }
      }).blur(function() {
        var input = $(this);
          if (input.val() == '' || input.val() == input.attr('placeholder')) {
           input.addClass('placeholder');
           input.val(input.attr('placeholder'));
         }
      }).blur();

      $('[placeholder]').parents('form').submit(function() {
        $(this).find('[placeholder]').each(function() {
          var input = $(this);
          if (input.val() == input.attr('placeholder')) {
            input.val('');
        }
      })
    });
  }

  // Initially set opacity on thumbs and add
  // additional styling for hover effect on thumbs
  var onMouseOutOpacity = 0.67;
  $('ul.thumbs li').opacityrollover({
    mouseOutOpacity:   onMouseOutOpacity,
    mouseOverOpacity:  1.0,
    fadeSpeed:         'fast',
    exemptionSelector: '.selected'
  });

  // Initialize Advanced Galleriffic Gallery
//  var gallery = $('#thumbs').galleriffic({
//    delay:                     2500,
//    numThumbs:                 15,
//    preloadAhead:              10,
//    enableTopPager:            true,
//    enableBottomPager:         true,
//    maxPagesToShow:            7,
//    imageContainerSel:         '#slideshow',
//    controlsContainerSel:      '#controls',
//    captionContainerSel:       '#caption',
//    loadingContainerSel:       '#loading',
//    renderSSControls:          true,
//    renderNavControls:         true,
//    enableHistory:             true,
//    autoStart:                 false,
//    syncTransitions:           true,
//    defaultTransitionDuration: 900,
//    onSlideChange:             function(prevIndex, nextIndex) {
//      // 'this' refers to the gallery, which is an extension of $('#thumbs')
//      this.find('ul.thumbs').children()
//        .eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
//        .eq(nextIndex).fadeTo('fast', 1.0);
//    },
//    onPageTransitionOut:       function(callback) {
//      this.fadeTo('fast', 0.0, callback);
//    },
//    onPageTransitionIn:        function() {
//      this.fadeTo('fast', 1.0);
//    }
//  });

    jQuery('ul.thumbs a.thumb').on('click', function(e) {
        var link = $(this);
        var thumb_src = link.find('img').attr('src');
        var thumb_title = link.data('title');

        $('ul.thumbs li').removeClass('active');
        link.parents('li').addClass('active');

        $('#slideshow img').attr('src', thumb_src.replace('square_thumbnails', 'fullsize'));
        $('#slideshow a.download-file').attr('href', link.attr('href'));

        $('#slideshow .caption a').html(thumb_title);
        $('#slideshow .caption a').attr('href', link.attr('href'));

        e.preventDefault();
    });


})(jQuery);




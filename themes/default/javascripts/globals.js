if (!Omeka) {
    var Omeka = {};
}

(function($,sr){
 
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;
 
      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null; 
          };
 
          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);
 
          timeout = setTimeout(delayed, threshold || 100); 
      };
  }
	// smartresize 
	jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
 
})(jQuery,'smartresize');

(function ($) {

    Omeka.showAdvancedForm = function () {
        var advancedForm = $('#advanced-form');
        var searchTextbox = $('#search-form input[type=text]');
        var searchSubmit = $('#search-form input[type=submit]');
        if (advancedForm) {
            searchTextbox.css("width", "60%");
            advancedForm.css("display", "none");
            searchSubmit.addClass("with-advanced").after('<a href="#" id="advanced-search" class="button">Advanced Search</a>');
            advancedForm.click(function (event) {
                event.stopPropagation();
            });
            $("#advanced-search").click(function (event) {
                event.preventDefault();
                event.stopPropagation();
                advancedForm.fadeToggle();
                $(document).click(function (event) {
                    if (event.target.id == 'query') {
                        return;
                    }
                    advancedForm.fadeOut();
                    $(this).unbind(event);
                });
            });
        } else {
            $('#search-form input[type=submit]').addClass("blue button");
        }
    };
    
    Omeka.moveNavOnResize = function() {
        var primaryNavUl;
        var moveNav = function () {
            if ($(window).width() < 768) {
                primaryNavUl = $('#primary-nav ul.navigation').first().detach();
                $(primaryNavUl).insertBefore('#wrap');
                $(primaryNavUl).addClass('mobile');
            } else {
                primaryNavUl = $('.menu-button + ul.navigation').detach();
                $(primaryNavUl).prependTo('#primary-nav');
                $(primaryNavUl).removeClass('mobile');
            }
        }
        moveNav();
        
        $(window).smartresize(function() {
            moveNav();
        });
    };
    
    Omeka.mobileMenu = function() {
        $('.navigation li a').each( function() {
            if ($(this).next().length > 0) {
                $(this).parent().addClass('parent');
            }
        });
        
        $('.menu-button').click( function(e) {
            e.preventDefault();
            $('.mobile').toggle();
        });
    };
    
})(jQuery);

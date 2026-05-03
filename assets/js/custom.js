var desktop = 1024;
/*!
 * JavaScript Cookie v2.0.2
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl
 * Released under the MIT license
 */
! function (a) {
  if ("function" == typeof define && define.amd) define(a);
  else if ("object" == typeof exports) module.exports = a();
  else {
      var b = window.Cookies,
          c = window.Cookies = a(window.jQuery);
      c.noConflict = function () {
          return window.Cookies = b, c
      }
  }
}(function () {
  function a() {
      for (var a = 0, b = {}; a < arguments.length; a++) {
          var c = arguments[a];
          for (var d in c) b[d] = c[d]
      }
      return b
  }

  function b(c) {
      function d(b, e, f) {
          var g;
          if (arguments.length > 1) {
              if (f = a({
                      path: "/"
                  }, d.defaults, f), "number" == typeof f.expires) {
                  var h = new Date;
                  h.setMilliseconds(h.getMilliseconds() + 864e5 * f.expires), f.expires = h
              }
              try {
                  g = JSON.stringify(e), /^[\{\[]/.test(g) && (e = g)
              } catch (a) {}
              return e = encodeURIComponent(String(e)), e = e.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent), b = encodeURIComponent(String(b)), b = b.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent), b = b.replace(/[\(\)]/g, escape), document.cookie = [b, "=", e, f.expires && "; expires=" + f.expires.toUTCString(), f.path && "; path=" + f.path, f.domain && "; domain=" + f.domain, f.secure ? "; secure" : ""].join("")
          }
          b || (g = {});
          for (var i = document.cookie ? document.cookie.split("; ") : [], j = /(%[0-9A-Z]{2})+/g, k = 0; k < i.length; k++) {
              var l = i[k].split("="),
                  m = l[0].replace(j, decodeURIComponent),
                  n = l.slice(1).join("=");
              if ('"' === n.charAt(0) && (n = n.slice(1, -1)), n = c && c(n, m) || n.replace(j, decodeURIComponent), this.json) try {
                  n = JSON.parse(n)
              } catch (a) {}
              if (b === m) {
                  g = n;
                  break
              }
              b || (g[m] = n)
          }
          return g
      }
      return d.get = d.set = d, d.getJSON = function () {
          return d.apply({
              json: !0
          }, [].slice.call(arguments))
      }, d.defaults = {}, d.remove = function (b, c) {
          d(b, "", a(c, {
              expires: -1
          }))
      }, d.withConverter = b, d
  }
  return b()
});
$(function () {
  paginationJump();
  btnBurger();
  btnSearch();
  tabsHeader();
  tabs();
  copyInput();
  memberProfileDrop();
  changeTheme();
  cookieHd();
  uploadDrop();
  initSearch();
  commentsOpen();
  videoInfoOpen();
  dropPosition();
  if ($(window).width() < desktop) {
    dropdown();
  }
  initKVSSuggestModel();
  suggestForm();
  downloadHistory();
  ititAgePass();
  initLang();
  // if ($(window).width() < 901) {
  //   getDropdownPosition();
  // }
  $(".dropdown .tags-list").addClass("second");
});

$('body').on('click', '.js-recaptcha', function(ev) {
  var form = $(this).closest('form'); 
  var msg = form.serialize();

  $.ajax({
    type: 'POST',
    url: '/verify.php',
    data: msg,
    success:function(data){
      var fail = data.match(/false/g);
      if(fail) {
        $('.recaptcha-error').show();
      }
      else {
        form.submit();
        $(".success").css("display", "block");
        $('.recaptcha-error').hide();
      }
    }
  });
  return false;
});



function suggestForm(){
  $('.js-open-suggest').on('click', function() {
    var $this = $(this);
    var form = $this.attr('data-open-suggest');
    if ($this.hasClass('open')) { 
      $this.removeClass('open');
      $('.' + form).slideUp();
    } else {
      $('.' + form).slideDown();
      $this.addClass('open');
    }
    return false;
  });
}

function initKVSSuggestModel() {

  $('.js-suggest-model input').on('input', function() {
    $(this).parent().parent().find($('.form-error')).fadeOut();
  });
  $('.js-suggest-model').on('submit', function() {
    var $this = $(this);
    var $failure = $this.find('.form-error');
    var $failure_ajax = $this.find('.form-error-ajax');
    var $form_close = $this.find('.form');
    var $success = $this.find('.block-success');
    var $input = $this.find('input');

    var get_id = $this.attr('data-id');
    var flag_id = $this.attr('data-flag');

    var flag_mes = $input.val();

    var text_failure = $this.attr('data-text-failure');
    var text_success = $this.attr('data-text-success');

    var get_target = $this.attr('data-target');

    if(!flag_mes) {
      $failure.fadeIn();
    } else {
      var get_url = "?action=flag&mode=async&" + get_target + "_id=" + get_id + "&flag_id=" + flag_id + "&flag_message="+ flag_mes;
      $.ajax({
        url: get_url,
        dataType: "text",
        success: function(msg) {
          var found_word = 'error_1';
          if (msg.indexOf(found_word) != -1) {
            $failure_ajax.show();
          } else {
            $success.show();
            $form_close.hide();
          }
        }
      });
    }

    return false;
  });
}

function commentsOpen() {
  if ($(window).width() < 601) {
    if ($(".block-comments").hasClass('open')) {
      $(".block-comments").removeClass('open');
      $('.block-comments .mobile-hide').hide();
    }
  }

  $(".block-comments .toggle-comments").on("click", function () {
    var $this = $(this);
    var $parent = $this.parents('.block-comments');
    var $mobile_hide = $('.block-comments .mobile-hide');
    var $video_info = $('.video-info');
    var $parentPos = $this.position();

    // var $trailer_height = $(".player-holder").offsetHeight();

    if ($parent.hasClass('open')) {
      $parent.removeClass('open');
      $mobile_hide.slideUp();
      $video_info.slideDown();
    } else {
      $parent.addClass('open');
      $mobile_hide.slideDown();
      $video_info.slideUp();
      $('html, body').animate({
        scrollTop: $('.player-holder').offset().top
      }, 500)
    }
  })

  if ($(window).width() > 639) {
    $('.comments-heading').on('click', function () {
      var $this = $(this);
      var $parent = $this.parents('.block-comments');
      var $mobile_hide = $('.block-comments .mobile-hide');

      if ($parent.hasClass('open')) {
        $parent.removeClass('open');
        $mobile_hide.slideUp();
      } else {
        $parent.addClass('open');
        $mobile_hide.slideDown();
      }
    })
  }
}

function videoInfoOpen() {
  $(".toggle-info").on("click", function () {
    console.log(213321);
    var $info_wrap = $('.tab-box .info-wrap');
    var $parent = $info_wrap.parents('.tab-content')
    if ($parent.hasClass("open")) {
      $parent.removeClass("open");
      $info_wrap.slideUp();
    } else {
      $parent.addClass("open");
      $info_wrap.slideDown();
    }
  })
}

function initSearch() {
  var options = {
    url: function(q) {
      return "/search_results.php?q=" + q;
    },
    getValue: "text",
    placeholder: "Search",
    list: {
      maxNumberOfElements: 13,
      match: {
        enabled: true
      }
    },
    template: {
      type: "links",
      fields: {
        link: "website-link"
      }
    },
    categories: [
      {
        listLocation: "search",
        maxNumberOfElements: 5,
        header: "Search suggestions:"
      },
      {
        listLocation: "category",
        maxNumberOfElements: 5,
        header: "Categories:"
      },
      {
        listLocation: "model",
        maxNumberOfElements: 5,
        header: "Models:"
      },
      {
        listLocation: "cs",
        maxNumberOfElements: 5,
        header: "Sites:"
      }
    ],
    requestDelay: 50
  };

  $(".search").easyAutocomplete(options);
}

function changeTheme() {
  var $btn = $(".toggle-dark");
  var $body = $("body");

  // Initial icon check
  if ($body.hasClass("dark")) {
    $btn.html("<svg class='svg-icon'><use xlink:href='#icon-sun'/></svg>");
  } else {
    $btn.html("<svg class='svg-icon'><use xlink:href='#icon-moon'/></svg>");
  }

  $btn.on("click", function () {
    var $this = $(this);
    var parent = $("body");
    var date = new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000);

    if (parent.hasClass("dark")) {
      parent.removeClass("dark").addClass("white");
      $this.html("<svg class='svg-icon'><use xlink:href='#icon-moon'/></svg>");
      document.cookie = "kt_rt_theme=white; path=/; expires=" + date.toUTCString();
    } else {
      parent.addClass("dark").removeClass("white");
      $this.html("<svg class='svg-icon'><use xlink:href='#icon-sun'/></svg>");
      document.cookie = "kt_rt_theme=dark; path=/; expires=" + date.toUTCString();
    }
  });
}

function memberProfileDrop() {
  $(".btn-user").on("click", function () {
    var $this = $(this);

    if ($this.hasClass("active")) {
      $this.removeClass("active").next().slideUp();
    } else {
      $this.addClass("active").next().slideDown();
    }
  });

  $(".wrapper").on("click", function (event) {
    if (!$(event.target).closest(".btn-user, .user-drop, .sidebar").length) {
      $(".btn-user").removeClass("active").next().slideUp();
    }
  });
}

function tabsHeader() {
  $('.col-tab').on("click", function () {
    var $this = $(this);
    var tabId = $this.attr('data-tab');

    $('.col-tab').removeClass('active');
    $('.tabs-content').removeClass('active');

    $this.addClass('active');
    $("#" + tabId).addClass('active');

  });
}

function tabs() {
  var $tab = $('.tab-box.active');

  $tab.slideDown();

  $(".js-tab").on("click", function () {
    var $this = $(this),
      $parent = $this.parent(),
      linkId = $this.attr('href');

    $('.tab-box').removeClass('active').slideUp();

    $(".js-drop").next().slideUp();

    if ($('.tab-box').hasClass('active')) {
      $(linkId).slideUp();
    } else {
      $(linkId).slideDown();
      $(".tab-buttons .item").removeClass('active');
      $parent.addClass('active');
    }
    return false;

  });

  $(".js-drop").on("click", function () {
    var $this = $(this),
      $parent = $this.parent(),
      $sibling = $(".js-drop").parent(),
      $parentPos = $parent.position();

    if ($parent.hasClass('active')) {
      $parent.removeClass("active");
      $(".js-drop").next().slideUp();
    } else {
      $sibling.removeClass("active");
      $(".js-drop").next().slideUp();
      $parent.addClass("active");
      $this.next().slideDown();
    }

    if ($(window).width() < 901) {
      $this.next().css({
        top: $parentPos.top + Math.round($this.outerHeight()),
        left: $parentPos.left,
        width: $this.outerWidth(),
      })
    }
  });

  $(".wrapper").on("click", function (event) {
    if (!$(event.target).closest(".js-drop").length) {
      $(".js-drop").parent().removeClass('active');
      $(".js-drop").next().slideUp();
    }
  });
}

function dropPosition() {
  $('.info-buttons .wrap-buttons').on('scroll', function () {
    var $parent = $('.js-drop').closest('.item.active'),
      $parentPos = $parent.position(),
      $btn_child = $parent.children(0);

    if ($parent.get(0)) {
      if ($(window).width() < 901) {
        console.log($btn_child.next());
        $btn_child.next().css({
          top: $parentPos.top + Math.round($btn_child.outerHeight()),
          left: $parentPos.left,
        })
      }

    }
  })
}

function btnBurger() {
  $(".burger").on("click", function () {
    var $this = $(this);
    var $parent = $this.parents(".wrapper");

    if ($parent.hasClass("open")) {
      $parent.removeClass("open");
    } else {
      $parent.addClass("open");
      $(".header-top").removeClass("active");

      if ($(window).width() <= 599) {
        $(".col-search").slideUp();
      }

      $(".btnSearch span").html("<svg class='svg-icon'><use xlink:href='#icon-search'/></svg>");
    }

    if ($(window).width() <= desktop) {
      $(".wrapper").on("click", function (event) {
        if (!$(event.target).closest(".burger, .sidebar").length) {
          if ($(".wrapper").hasClass("open")) {
            $(".wrapper").removeClass("open");
          }
        }
      });
    }

  });
}

function btnSearch() {
  $(".btnSearch").on("click", function () {
    var $this = $(this);
    var $parent = $this.parents(".header-top");

    if ($parent.hasClass("active")) {
      $parent.removeClass("active");
      $(".col-search").slideUp();
      $(".btnSearch span").html("<svg class='svg-icon'><use xlink:href='#icon-search'/></svg>");
    } else {
      $parent.addClass("active");
      $(".col-search").slideDown();
      $(".btnSearch span").html("<svg class='svg-icon cross'><use xlink:href='#icon-cross'/></svg>");
      $(".wrapper").removeClass("open");
    }
  });
}

function dropdown() {
  $(document).on("click", ".btn-dropdown", function () {
    var $this = $(this);

    if ($this.hasClass("current")) {
      $this.removeClass("current").next().slideUp();
    } else {
      $(".btn-dropdown").removeClass("current").next().slideUp();
      $this.addClass("current").next().slideDown();
    }
  });

  $(".nav .btn-dropdown").on("click", function () {
    $(".wrapper").removeClass("open");
  });

  $(".wrapper").on("click", function (e) {
    if (!$(e.target).closest(".btn-dropdown, .dropdown").length) {
      $(".btn-dropdown").removeClass("current").next().slideUp();
    }
  });
}

function copyInput() {
  $(".tab-box .input").on("click", function () {
    $(this).select();
  });
}

function uploadDrop() {
  $("button.upload").on("click", function () {
    var $this = $(this);

    if ($this.hasClass('active')) {
      $this.removeClass("active").next().slideUp();
    } else {
      $this.addClass("active").next().slideDown();
    }
  });

  $(".wrapper").on("click", function (e) {
    if (!$(e.target).closest(".header-top .drop").length) {
      $("button.upload").removeClass("active").next().slideUp();
    }
  });
}

function cookieHd() {
  if ($.cookie('hd') == "1") {
    $("body").addClass("hd");
    $(".list-hd .btn-hd").addClass("active") && $(".list-hd .all").removeClass("active");
    $(".headline .title .is-hd").show();
  } else {
    $(".list-hd .all").addClass("active") && $(".list-hd .btn-hd").removeClass("active");
    $(".headline .title .is-hd").hide();
  }

  $(".list-hd .btn-hd").click(function () {
    if ($.cookie('hd') != "undefined" || $.cookie('hd') != "no") {
      $.cookie('hd', '1', {
        expires: 365,
        path: '/'
      });
      $("body").addClass("hd") && $(".list-hd .all").removeClass("active") && $(".list-hd .btn-hd").addClass("active");
      $(".headline .title .is-hd").show();
    }
    location.href = location.href;
  });

  $(".list-hd .all").click(function () {
    $('body').removeClass('hd');
    if ($.cookie('hd') == "1") {
      $.cookie("hd", null, {
        path: '/'
      });
      $(".list-hd .btn-hd").removeClass("active") && $(".list-hd .all").addClass("active");
      $(".headline .title .is-hd").hide();
    }
    location.href = location.href;
  });
}

function readCookieDelit(name) {
  var name_cook = name + "=";
  var spl = document.cookie.split(';');
  for(var i=0; i<spl.length; i++) {
    var c = spl[i];
    while (c.charAt(0)==' ') c = c.substring(1, c.length);
    if(c.indexOf(name_cook) == 0) return c.substring(name_cook.length, c.length);
  }
  return null;
}

function downloadHistory() {
  var value_cookie_download = readCookieDelit('kt_rt_download');
  var $limit = +$("#tab6").attr('data-limit');
  var $href = +$("#tab6").attr('data-limit-url');
  if (value_cookie_download >= $limit) { 
    console.log(value_cookie_download)
    console.log($limit)
    $(".js-download-history").attr('href', $href);
  }

  $(".js-download-history").on("click", function () {
    var obDate = new Date();
    var currDate = [];
    var Year = currDate.push(obDate.getFullYear());
    var Month = currDate.push(obDate.getMonth() + 1);
    var date = currDate.push(obDate.getDate());
    var ending = currDate.join('-') + ' 23:59:00';
    var diff = Math.round((new Date(ending).getTime() / 1000) - (obDate.getTime() / 1000)) * 1000;
    if (value_cookie_download >= $limit) { 
      $(".js-limit-url").click();
      return false;
    }

    if (value_cookie_download == null) { 
      var $download = 1;  
    } else {
      var $download = +value_cookie_download + 1;
    }
    var date = new Date(new Date().getTime() + diff);
    document.cookie = "kt_rt_download=" + $download + "; path=/; expires=" + date.toUTCString();

    var $this = $(this);
    var $id = $this.attr('data-id');
    var $video_id = $this.attr('data-video_id');
    var data = { 
      'action': 'download_history',
      'video_id': $video_id,
      'format': 'json',
      'mode': 'async'
    };
    var get_url = "?block_id="+$id;
    $.ajax({
      url: get_url,
      type: 'POST',
      data: data,
      success: function(msg) {
        console.log(msg);
      }
    });
  });
}

function removeArrowsOnModal() {
  $(".list-profile .button.btn").on("click", function () {
    $("body").addClass("removeArrow");
  });
}

if (document.location.href.indexOf('members') != -1) {
  removeArrowsOnModal();
}

$(document).ready(function () {
  if ($("body").hasClass("video-page")) {
      var e = $.cookie("fake_player_adv");
      null != e && "3" != e && "undefined" != e || ($(".html-player").addClass("open-tool"), $(".fake-player").addClass("open-tool"), $(".fake-player-wrap").addClass("show-fake"), $(".fake-player-wrap").on("click", function () {
          $(this).hasClass("show-fake") && ($(".pop-fade").addClass("open-tool"), $(".fake-player").removeClass("open-tool"), $(".pop-adv").addClass("open-tool"))
      })), 1 == e ? Cookies.set("fake_player_adv", 2, {
          expires: 1
      }) : 2 == e && Cookies.set("fake_player_adv", 3, {
          expires: 1
      }), $(".pop-adv .cross, .pop-adv .btn").on("click", function () {
          $(".pop-adv").hasClass("open-tool") && ($(".pop-fade").removeClass("open-tool"), $(".pop-adv").removeClass("open-tool"), $(".fake-player-wrap").removeClass("show-fake"), $(".html-player").addClass("open-tool")), Cookies.set("fake_player_adv", 1, {
              expires: 1
          })
      })
  }
})

function ititAgePass() {
  $('.js-age-pass').on('click', function() {
    $('body').removeClass('age_false');
    var date = new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000);
    document.cookie = "kt_rt_age_pass=true; path=/; expires=" + date.toUTCString();
    return false;
  });
}

function paginationJump() {
	$('body').on('keyup', '.js_jumpTo', function (e) {
		var $this = $(this);
		var parametersDef = $this.closest('.jump_to').find('a').attr('data-parameters-def') + $this.val();
		var dataParameters = $this.closest('.jump_to').find('a');
		if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 46 || e.keyCode == 8) {
			dataParameters.attr('data-parameters', parametersDef);
		}
	})
}

function initLang() {
  $('.js-lang').on('click', function() {
    var $this = $(this);
    var $lang = $this.attr('data-lang');
    var date = new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000);
    document.cookie = "kt_lang=" + $lang + "; path=/; expires=" + date.toUTCString();
    location.reload();
    return false;
  });
}
(function() {
    function initHoverPreview() {
        document.querySelectorAll('.js-hover-preview').forEach(function(link) {
            if (link._previewInit) return;
            link._previewInit = true;
            var img = link.querySelector('.js-preview-img');
            if (!img) return;
            var raw = link.getAttribute('data-preview-images');
            if (!raw) return;
            var urls = [];
            try { urls = JSON.parse(raw); } catch (e) { return; }
            if (urls.length < 2) return;
            var posterUrl = img.src;
            var idx = 0;
            var t = null;
            link.addEventListener('mouseenter', function() {
                idx = 0;
                if (urls[0]) img.src = urls[0];
                t = setInterval(function() {
                    idx = (idx + 1) % urls.length;
                    if (urls[idx]) img.src = urls[idx];
                }, 500);
            });
            link.addEventListener('mouseleave', function() {
                if (t) clearInterval(t);
                t = null;
                img.src = posterUrl;
            });
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHoverPreview);
    } else {
        initHoverPreview();
    }
})();

/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/frontend/login.js":
/***/ (function(module, exports) {

(function ($) {
    $(document).ready(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('.app').addClass('loginbackground');
    });
})(jQuery);

/***/ }),

/***/ "./resources/assets/js/plugin/icheck.min.js":
/***/ (function(module, exports) {

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/*! iCheck v1.0.1 by Damir Sultanov, http://git.io/arlzeA, MIT Licensed */
(function (h) {
  function F(a, b, d) {
    var c = a[0],
        e = /er/.test(d) ? m : /bl/.test(d) ? s : l,
        f = d == H ? { checked: c[l], disabled: c[s], indeterminate: "true" == a.attr(m) || "false" == a.attr(w) } : c[e];if (/^(ch|di|in)/.test(d) && !f) D(a, e);else if (/^(un|en|de)/.test(d) && f) t(a, e);else if (d == H) for (e in f) {
      f[e] ? D(a, e, !0) : t(a, e, !0);
    } else if (!b || "toggle" == d) {
      if (!b) a[p]("ifClicked");f ? c[n] !== u && t(a, e) : D(a, e);
    }
  }function D(a, b, d) {
    var c = a[0],
        e = a.parent(),
        f = b == l,
        A = b == m,
        B = b == s,
        K = A ? w : f ? E : "enabled",
        p = k(a, K + x(c[n])),
        N = k(a, b + x(c[n]));if (!0 !== c[b]) {
      if (!d && b == l && c[n] == u && c.name) {
        var C = a.closest("form"),
            r = 'input[name="' + c.name + '"]',
            r = C.length ? C.find(r) : h(r);r.each(function () {
          this !== c && h(this).data(q) && t(h(this), b);
        });
      }A ? (c[b] = !0, c[l] && t(a, l, "force")) : (d || (c[b] = !0), f && c[m] && t(a, m, !1));L(a, f, b, d);
    }c[s] && k(a, y, !0) && e.find("." + I).css(y, "default");e[v](N || k(a, b) || "");B ? e.attr("aria-disabled", "true") : e.attr("aria-checked", A ? "mixed" : "true");e[z](p || k(a, K) || "");
  }function t(a, b, d) {
    var c = a[0],
        e = a.parent(),
        f = b == l,
        h = b == m,
        q = b == s,
        p = h ? w : f ? E : "enabled",
        t = k(a, p + x(c[n])),
        u = k(a, b + x(c[n]));if (!1 !== c[b]) {
      if (h || !d || "force" == d) c[b] = !1;L(a, f, p, d);
    }!c[s] && k(a, y, !0) && e.find("." + I).css(y, "pointer");e[z](u || k(a, b) || "");q ? e.attr("aria-disabled", "false") : e.attr("aria-checked", "false");e[v](t || k(a, p) || "");
  }function M(a, b) {
    if (a.data(q)) {
      a.parent().html(a.attr("style", a.data(q).s || ""));if (b) a[p](b);a.off(".i").unwrap();h(G + '[for="' + a[0].id + '"]').add(a.closest(G)).off(".i");
    }
  }function k(a, b, d) {
    if (a.data(q)) return a.data(q).o[b + (d ? "" : "Class")];
  }function x(a) {
    return a.charAt(0).toUpperCase() + a.slice(1);
  }function L(a, b, d, c) {
    if (!c) {
      if (b) a[p]("ifToggled");a[p]("ifChanged")[p]("if" + x(d));
    }
  }var q = "iCheck",
      I = q + "-helper",
      u = "radio",
      l = "checked",
      E = "un" + l,
      s = "disabled",
      w = "determinate",
      m = "in" + w,
      H = "update",
      n = "type",
      v = "addClass",
      z = "removeClass",
      p = "trigger",
      G = "label",
      y = "cursor",
      J = /ipad|iphone|ipod|android|blackberry|windows phone|opera mini|silk/i.test(navigator.userAgent);h.fn[q] = function (a, b) {
    var d = 'input[type="checkbox"], input[type="' + u + '"]',
        c = h(),
        e = function e(a) {
      a.each(function () {
        var a = h(this);c = a.is(d) ? c.add(a) : c.add(a.find(d));
      });
    };if (/^(check|uncheck|toggle|indeterminate|determinate|disable|enable|update|destroy)$/i.test(a)) return a = a.toLowerCase(), e(this), c.each(function () {
      var c = h(this);"destroy" == a ? M(c, "ifDestroyed") : F(c, !0, a);h.isFunction(b) && b();
    });if ("object" != (typeof a === "undefined" ? "undefined" : _typeof(a)) && a) return this;var f = h.extend({ checkedClass: l, disabledClass: s, indeterminateClass: m, labelHover: !0, aria: !1 }, a),
        k = f.handle,
        B = f.hoverClass || "hover",
        x = f.focusClass || "focus",
        w = f.activeClass || "active",
        y = !!f.labelHover,
        C = f.labelHoverClass || "hover",
        r = ("" + f.increaseArea).replace("%", "") | 0;if ("checkbox" == k || k == u) d = 'input[type="' + k + '"]';-50 > r && (r = -50);e(this);return c.each(function () {
      var a = h(this);M(a);var c = this,
          b = c.id,
          e = -r + "%",
          d = 100 + 2 * r + "%",
          d = { position: "absolute", top: e, left: e, display: "block", width: d, height: d, margin: 0, padding: 0, background: "#fff", border: 0, opacity: 0 },
          e = J ? { position: "absolute", visibility: "hidden" } : r ? d : { position: "absolute", opacity: 0 },
          k = "checkbox" == c[n] ? f.checkboxClass || "icheckbox" : f.radioClass || "i" + u,
          m = h(G + '[for="' + b + '"]').add(a.closest(G)),
          A = !!f.aria,
          E = q + "-" + Math.random().toString(36).replace("0.", ""),
          g = '<div class="' + k + '" ' + (A ? 'role="' + c[n] + '" ' : "");m.length && A && m.each(function () {
        g += 'aria-labelledby="';this.id ? g += this.id : (this.id = E, g += E);g += '"';
      });g = a.wrap(g + "/>")[p]("ifCreated").parent().append(f.insert);d = h('<ins class="' + I + '"/>').css(d).appendTo(g);a.data(q, { o: f, s: a.attr("style") }).css(e);f.inheritClass && g[v](c.className || "");f.inheritID && b && g.attr("id", q + "-" + b);"static" == g.css("position") && g.css("position", "relative");F(a, !0, H);
      if (m.length) m.on("click.i mouseover.i mouseout.i touchbegin.i touchend.i", function (b) {
        var d = b[n],
            e = h(this);if (!c[s]) {
          if ("click" == d) {
            if (h(b.target).is("a")) return;F(a, !1, !0);
          } else y && (/ut|nd/.test(d) ? (g[z](B), e[z](C)) : (g[v](B), e[v](C)));if (J) b.stopPropagation();else return !1;
        }
      });a.on("click.i focus.i blur.i keyup.i keydown.i keypress.i", function (b) {
        var d = b[n];b = b.keyCode;if ("click" == d) return !1;if ("keydown" == d && 32 == b) return c[n] == u && c[l] || (c[l] ? t(a, l) : D(a, l)), !1;if ("keyup" == d && c[n] == u) !c[l] && D(a, l);else if (/us|ur/.test(d)) g["blur" == d ? z : v](x);
      });d.on("click mousedown mouseup mouseover mouseout touchbegin.i touchend.i", function (b) {
        var d = b[n],
            e = /wn|up/.test(d) ? w : B;if (!c[s]) {
          if ("click" == d) F(a, !1, !0);else {
            if (/wn|er|in/.test(d)) g[v](e);else g[z](e + " " + w);if (m.length && y && e == B) m[/ut|nd/.test(d) ? z : v](C);
          }if (J) b.stopPropagation();else return !1;
        }
      });
    });
  };
})(window.jQuery || window.Zepto);

/***/ }),

/***/ "./resources/assets/js/plugins.js":
/***/ (function(module, exports) {

/**
 * Allows you to add data-method="METHOD to links to automatically inject a form
 * with the method on click
 *
 * Example: <a href="{{route('customers.destroy', $customer->id)}}"
 * data-method="delete" name="delete_item">Delete</a>
 *
 * Injects a form with that's fired on click of the link with a DELETE request.
 * Good because you don't have to dirty your HTML with delete forms everywhere.
 */
function addDeleteForms() {
    $('[data-method]').append(function () {
        if (!$(this).find('form').length > 0) return "\n" + "<form action='" + $(this).attr('href') + "' method='POST' name='delete_item' style='display:none'>\n" + "<input type='hidden' name='_method' value='" + $(this).attr('data-method') + "'>\n" + "<input type='hidden' name='_token' value='" + $('meta[name="csrf-token"]').attr('content') + "'>\n" + "</form>\n";else return "";
    }).removeAttr('href').attr('style', 'cursor:pointer;').attr('onclick', '$(this).find("form").submit();');
}

/**
 * Place any jQuery/helper plugins in here.
 */
$(function () {
    var $loading = $('.loader');

    $(document).ajaxStart(function () {
        $loading.show();
    }).ajaxError(function (event, jqxhr, settings, thrownError) {
        $loading.hide();
        location.reload();
    }).ajaxStop(function () {
        $loading.hide();
    }).on('draw.dt', function () {
        addDeleteForms();
    });

    /**
     * Add the data-method="delete" forms to all delete links
     */
    addDeleteForms();

    /**
     * Place the CSRF token as a header on all pages for access in AJAX requests
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Bind all bootstrap tooltips & popovers
     */
    $("[data-toggle='tooltip']").tooltip();
    $("[data-toggle='popover']").popover();

    /**
     * Generic confirm form delete using Sweet Alert
     */
    $('body').on('submit', 'form[name=delete_item]', function (e) {
        e.preventDefault();

        var form = this,
            link = $('a[data-method="delete"]'),
            cancel = link.attr('data-trans-button-cancel') ? link.attr('data-trans-button-cancel') : "Cancel",
            confirm = link.attr('data-trans-button-confirm') ? link.attr('data-trans-button-confirm') : "Yes, delete",
            title = link.attr('data-trans-title') ? link.attr('data-trans-title') : "Warning",
            text = link.attr('data-trans-text') ? link.attr('data-trans-text') : "Are you sure you want to delete this item?";

        swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            cancelButtonText: cancel,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirm,
            closeOnConfirm: true
        }, function (confirmed) {
            if (confirmed) form.submit();
        });
    }).on('click', 'a[name=confirm_item]', function (e) {
        /**
         * Generic 'are you sure' confirm box
         */
        e.preventDefault();

        var link = $(this),
            title = link.attr('data-trans-title') ? link.attr('data-trans-title') : "Are you sure you want to do this?",
            cancel = link.attr('data-trans-button-cancel') ? link.attr('data-trans-button-cancel') : "Cancel",
            confirm = link.attr('data-trans-button-confirm') ? link.attr('data-trans-button-confirm') : "Continue";

        swal({
            title: title,
            type: "info",
            showCancelButton: true,
            cancelButtonText: cancel,
            confirmButtonColor: "#3C8DBC",
            confirmButtonText: confirm,
            closeOnConfirm: true
        }, function (confirmed) {
            if (confirmed) window.location = link.attr('href');
        });
    }).on('click', function (e) {
        /**
         * This closes popovers when clicked away from
         */
        $('[data-toggle="popover"]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
});

/***/ }),

/***/ 2:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__("./resources/assets/js/plugin/icheck.min.js");
__webpack_require__("./resources/assets/js/plugins.js");
module.exports = __webpack_require__("./resources/assets/js/frontend/login.js");


/***/ })

/******/ });
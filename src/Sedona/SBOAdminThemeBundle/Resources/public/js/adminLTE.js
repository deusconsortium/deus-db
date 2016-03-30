/*!
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This file should be included in all pages
 !**/

$(function() {
    "use strict";

    //Enable sidebar toggle
    $("[data-toggle='offcanvas']").click(function(e) {
        e.preventDefault();

        //If window is small enough, enable sidebar push menu
        if ($(window).width() <= 992) {
            $('.row-offcanvas').toggleClass('active');
            $('.left-side').removeClass("collapse-left");
            $(".right-side").removeClass("strech");
            $('.row-offcanvas').toggleClass("relative");
        } else {
            //Else, enable content streching
            $('.left-side').toggleClass("collapse-left");
            $(".right-side").toggleClass("strech");
        }
    });

    //Add hover support for touch devices
    $('.btn').bind('touchstart', function() {
        $(this).addClass('hover');
    }).bind('touchend', function() {
        $(this).removeClass('hover');
    });

    //Activate tooltips
    $("[data-toggle='tooltip']").tooltip();

    /*
     * Add collapse and remove events to boxes
     */
    $("[data-widget='collapse']").click(function() {
        //Find the box parent
        var box = $(this).parents(".box").first();
        //Find the body and the footer
        var bf = box.find(".box-body, .box-footer");
        if (!box.hasClass("collapsed-box")) {
            box.addClass("collapsed-box");
            bf.slideUp();
        } else {
            box.removeClass("collapsed-box");
            bf.slideDown();
        }
    });

    /*
     * ADD SLIMSCROLL TO THE TOP NAV DROPDOWNS
     * ---------------------------------------
     */
    $(".navbar .menu").slimscroll({
        height: "200px",
        alwaysVisible: false,
        size: "3px"
    }).css("width","100%");

    /*
     * INITIALIZE BUTTON TOGGLE
     * ------------------------
     */
    $('.btn-group[data-toggle="btn-toggle"]').each(function() {
        var group = $(this);
        $(this).find(".btn").click(function(e) {
            group.find(".btn.active").removeClass("active");
            $(this).addClass("active");
            e.preventDefault();
        });

    });

    $("[data-widget='remove']").click(function() {
        //Find the box parent
        var box = $(this).parents(".box").first();
        box.slideUp();
    });

    /* Sidebar tree view */
    $(".sidebar .treeview").tree();

    /*
     * Make sure that the sidebar is streched full height
     * ---------------------------------------------
     * We are gonna assign a min-height value every time the
     * wrapper gets resized and upon page load. We will use
     * Ben Alman's method for detecting the resize event.
     **/
    //alert($(window).height());
    function _fix() {
        //Get window height and the wrapper height
        var height = $(window).height() - $("body > .header").height();
        $(".wrapper").css("min-height", height + "px");
        var content = $(".wrapper").height();
        //If the wrapper height is greater than the window
        if (content > height)
        //then set sidebar height to the wrapper
            $(".left-side, html, body").css("min-height", content + "px");
        else {
            //Otherwise, set the sidebar to the height of the window
            $(".left-side, html, body").css("min-height", height + "px");
        }
    }
    //Fire upon load
    _fix();
    //Fire when wrapper is resized
    $(".wrapper").resize(function() {
        _fix();
    });

    /*
     * We are gonna initialize all checkbox and radio inputs to
     * iCheck plugin in.
     * You can find the documentation at http://fronteed.com/iCheck/
     */
    $("input[type='checkbox'], input[type='radio']").iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal'
    });

});
function change_layout() {
    $("body").toggleClass("fixed");
}
/*END DEMO*/

/*
 * BOX REFRESH BUTTON
 * ------------------
 * This is a custom plugin to use with the compenet BOX. It allows you to add
 * a refresh button to the box. It converts the box's state to a loading state.
 *
 * USAGE:
 *  $("#box-widget").boxRefresh( options );
 * */
(function($) {
    "use strict";

    $.fn.boxRefresh = function(options) {

        // Render options
        var settings = $.extend({
            //Refressh button selector
            trigger: ".refresh-btn",
            //File source to be loaded (e.g: ajax/src.php)
            source: "",
            //Callbacks
            onLoadStart: function(box) {
            }, //Right after the button has been clicked
            onLoadDone: function(box) {
            } //When the source has been loaded

        }, options);

        //The overlay
        var overlay = $('<div class="overlay"></div><div class="loading-img"></div>');

        return this.each(function() {
            //if a source is specified
            if (settings.source === "") {
                if (console) {
                    console.log("Please specify a source first - boxRefresh()");
                }
                return;
            }
            //the box
            var box = $(this);
            //the button
            var rBtn = box.find(settings.trigger).first();

            //On trigger click
            rBtn.click(function(e) {
                e.preventDefault();
                //Add loading overlay
                start(box);

                //Perform ajax call
                box.find(".box-body").load(settings.source, function() {
                    done(box);
                });


            });

        });

        function start(box) {
            //Add overlay and loading img
            box.append(overlay);

            settings.onLoadStart.call(box);
        }

        function done(box) {
            //Remove overlay and loading img
            box.find(overlay).remove();

            settings.onLoadDone.call(box);
        }

    };

})(jQuery);

/*
 * SIDEBAR MENU
 * ------------
 * This is a custom plugin for the sidebar menu. It provides a tree view.
 *
 * Usage:
 * $(".sidebar).tree();
 *
 * Note: This plugin does not accept any options. Instead, it only requires a class
 *       added to the element that contains a sub-menu.
 *
 * When used with the sidebar, for example, it would look something like this:
 * <ul class='sidebar-menu'>
 *      <li class="treeview active">
 *          <a href="#>Menu</a>
 *          <ul class='treeview-menu'>
 *              <li class='active'><a href=#>Level 1</a></li>
 *          </ul>
 *      </li>
 * </ul>
 *
 * Add .active class to <li> elements if you want the menu to be open automatically
 * on page load. See above for an example.
 */
(function($) {
    "use strict";

    $.fn.tree = function() {

        return this.each(function() {
            var btn = $(this).children("a").first();
            var menu = $(this).children(".treeview-menu").first();
            var isActive = $(this).hasClass('active');

            //initialize already active menus
            if (isActive) {
                menu.show();
                btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
            }
            //Slide open or close the menu on link click
            btn.click(function(e) {
                e.preventDefault();
                if (isActive) {
                    //Slide up to close menu
                    menu.slideUp();
                    isActive = false;
                    btn.children(".fa-angle-down").first().removeClass("fa-angle-down").addClass("fa-angle-left");
                    btn.parent("li").removeClass("active");
                } else {
                    //Slide down to open menu
                    menu.slideDown();
                    isActive = true;
                    btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
                    btn.parent("li").addClass("active");
                }
            });

            /* Add margins to submenu elements to give it a tree look */
            menu.find("li > a").each(function() {
                var pad = parseInt($(this).css("margin-left")) + 10;

                $(this).css({"margin-left": pad + "px"});
            });

        });

    };


}(jQuery));

/*
 * TODO LIST CUSTOM PLUGIN
 * -----------------------
 * This plugin depends on iCheck plugin for checkbox and radio inputs
 */
(function($) {
    "use strict";

    $.fn.todolist = function(options) {
        // Render options
        var settings = $.extend({
            //When the user checks the input
            onCheck: function(ele) {
            },
            //When the user unchecks the input
            onUncheck: function(ele) {
            }
        }, options);

        return this.each(function() {
            $('input', this).on('ifChecked', function(event) {
                var ele = $(this).parents("li").first();
                ele.toggleClass("done");
                settings.onCheck.call(ele);
            });

            $('input', this).on('ifUnchecked', function(event) {
                var ele = $(this).parents("li").first();
                ele.toggleClass("done");
                settings.onUncheck.call(ele);
            });
        });
    };

}(jQuery));

/* CENTER ELEMENTS */
(function($) {
    "use strict";
    jQuery.fn.center = function(parent) {
        if (parent) {
            parent = this.parent();
        } else {
            parent = window;
        }
        this.css({
            "position": "absolute",
            "top": ((($(parent).height() - this.outerHeight()) / 2) + $(parent).scrollTop() + "px"),
            "left": ((($(parent).width() - this.outerWidth()) / 2) + $(parent).scrollLeft() + "px")
        });
        return this;
    }
}(jQuery));

/*
 * jQuery resize event - v1.1 - 3/14/2010
 * http://benalman.com/projects/jquery-resize-plugin/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($, h, c) {
    var a = $([]), e = $.resize = $.extend($.resize, {}), i, k = "setTimeout", j = "resize", d = j + "-special-event", b = "delay", f = "throttleWindow";
    e[b] = 250;
    e[f] = true;
    $.event.special[j] = {setup: function() {
        if (!e[f] && this[k]) {
            return false;
        }
        var l = $(this);
        a = a.add(l);
        $.data(this, d, {w: l.width(), h: l.height()});
        if (a.length === 1) {
            g();
        }
    }, teardown: function() {
        if (!e[f] && this[k]) {
            return false
        }
        var l = $(this);
        a = a.not(l);
        l.removeData(d);
        if (!a.length) {
            clearTimeout(i);
        }
    }, add: function(l) {
        if (!e[f] && this[k]) {
            return false
        }
        var n;
        function m(s, o, p) {
            var q = $(this), r = $.data(this, d);
            r.w = o !== c ? o : q.width();
            r.h = p !== c ? p : q.height();
            n.apply(this, arguments)
        }
        if ($.isFunction(l)) {
            n = l;
            return m
        } else {
            n = l.handler;
            l.handler = m
        }
    }};
    function g() {
        i = h[k](function() {
            a.each(function() {
                var n = $(this), m = n.width(), l = n.height(), o = $.data(this, d);
                if (m !== o.w || l !== o.h) {
                    n.trigger(j, [o.w = m, o.h = l])
                }
            });
            g()
        }, e[b])
    }}
)(jQuery, this);

/*!
 * iCheck v1.0.1, http://git.io/arlzeA
 * =================================
 * Powerful jQuery and Zepto plugin for checkboxes and radio buttons customization
 *
 * (c) 2013 Damir Sultanov, http://fronteed.com
 * MIT Licensed
 */
(function(f) {
    jQuery.fn.extend({slimScroll: function(h) {
        var a = f.extend({width: "auto", height: "250px", size: "7px", color: "#000", position: "right", distance: "1px", start: "top", opacity: 0.4, alwaysVisible: !1, disableFadeOut: !1, railVisible: !1, railColor: "#333", railOpacity: 0.2, railDraggable: !0, railClass: "slimScrollRail", barClass: "slimScrollBar", wrapperClass: "slimScrollDiv", allowPageScroll: !1, wheelStep: 20, touchScrollStep: 200, borderRadius: "0px", railBorderRadius: "0px"}, h);
        this.each(function() {
            function r(d) {
                if (s) {
                    d = d ||
                    window.event;
                    var c = 0;
                    d.wheelDelta && (c = -d.wheelDelta / 120);
                    d.detail && (c = d.detail / 3);
                    f(d.target || d.srcTarget || d.srcElement).closest("." + a.wrapperClass).is(b.parent()) && m(c, !0);
                    d.preventDefault && !k && d.preventDefault();
                    k || (d.returnValue = !1)
                }
            }
            function m(d, f, h) {
                k = !1;
                var e = d, g = b.outerHeight() - c.outerHeight();
                f && (e = parseInt(c.css("top")) + d * parseInt(a.wheelStep) / 100 * c.outerHeight(), e = Math.min(Math.max(e, 0), g), e = 0 < d ? Math.ceil(e) : Math.floor(e), c.css({top: e + "px"}));
                l = parseInt(c.css("top")) / (b.outerHeight() - c.outerHeight());
                e = l * (b[0].scrollHeight - b.outerHeight());
                h && (e = d, d = e / b[0].scrollHeight * b.outerHeight(), d = Math.min(Math.max(d, 0), g), c.css({top: d + "px"}));
                b.scrollTop(e);
                b.trigger("slimscrolling", ~~e);
                v();
                p()
            }
            function C() {
                window.addEventListener ? (this.addEventListener("DOMMouseScroll", r, !1), this.addEventListener("mousewheel", r, !1), this.addEventListener("MozMousePixelScroll", r, !1)) : document.attachEvent("onmousewheel", r)
            }
            function w() {
                u = Math.max(b.outerHeight() / b[0].scrollHeight * b.outerHeight(), D);
                c.css({height: u + "px"});
                var a = u == b.outerHeight() ? "none" : "block";
                c.css({display: a})
            }
            function v() {
                w();
                clearTimeout(A);
                l == ~~l ? (k = a.allowPageScroll, B != l && b.trigger("slimscroll", 0 == ~~l ? "top" : "bottom")) : k = !1;
                B = l;
                u >= b.outerHeight() ? k = !0 : (c.stop(!0, !0).fadeIn("fast"), a.railVisible && g.stop(!0, !0).fadeIn("fast"))
            }
            function p() {
                a.alwaysVisible || (A = setTimeout(function() {
                    a.disableFadeOut && s || (x || y) || (c.fadeOut("slow"), g.fadeOut("slow"))
                }, 1E3))
            }
            var s, x, y, A, z, u, l, B, D = 30, k = !1, b = f(this);
            if (b.parent().hasClass(a.wrapperClass)) {
                var n = b.scrollTop(),
                    c = b.parent().find("." + a.barClass), g = b.parent().find("." + a.railClass);
                w();
                if (f.isPlainObject(h)) {
                    if ("height"in h && "auto" == h.height) {
                        b.parent().css("height", "auto");
                        b.css("height", "auto");
                        var q = b.parent().parent().height();
                        b.parent().css("height", q);
                        b.css("height", q)
                    }
                    if ("scrollTo"in h)
                        n = parseInt(a.scrollTo);
                    else if ("scrollBy"in h)
                        n += parseInt(a.scrollBy);
                    else if ("destroy"in h) {
                        c.remove();
                        g.remove();
                        b.unwrap();
                        return
                    }
                    m(n, !1, !0)
                }
            } else {
                a.height = "auto" == a.height ? b.parent().height() : a.height;
                n = f("<div></div>").addClass(a.wrapperClass).css({position: "relative",
                    overflow: "hidden", width: a.width, height: a.height});
                b.css({overflow: "hidden", width: a.width, height: a.height});
                var g = f("<div></div>").addClass(a.railClass).css({width: a.size, height: "100%", position: "absolute", top: 0, display: a.alwaysVisible && a.railVisible ? "block" : "none", "border-radius": a.railBorderRadius, background: a.railColor, opacity: a.railOpacity, zIndex: 90}), c = f("<div></div>").addClass(a.barClass).css({background: a.color, width: a.size, position: "absolute", top: 0, opacity: a.opacity, display: a.alwaysVisible ?
                    "block" : "none", "border-radius": a.borderRadius, BorderRadius: a.borderRadius, MozBorderRadius: a.borderRadius, WebkitBorderRadius: a.borderRadius, zIndex: 99}), q = "right" == a.position ? {right: a.distance} : {left: a.distance};
                g.css(q);
                c.css(q);
                b.wrap(n);
                b.parent().append(c);
                b.parent().append(g);
                a.railDraggable && c.bind("mousedown", function(a) {
                    var b = f(document);
                    y = !0;
                    t = parseFloat(c.css("top"));
                    pageY = a.pageY;
                    b.bind("mousemove.slimscroll", function(a) {
                        currTop = t + a.pageY - pageY;
                        c.css("top", currTop);
                        m(0, c.position().top, !1)
                    });
                    b.bind("mouseup.slimscroll", function(a) {
                        y = !1;
                        p();
                        b.unbind(".slimscroll")
                    });
                    return!1
                }).bind("selectstart.slimscroll", function(a) {
                    a.stopPropagation();
                    a.preventDefault();
                    return!1
                });
                g.hover(function() {
                    v()
                }, function() {
                    p()
                });
                c.hover(function() {
                    x = !0
                }, function() {
                    x = !1
                });
                b.hover(function() {
                    s = !0;
                    v();
                    p()
                }, function() {
                    s = !1;
                    p()
                });
                b.bind("touchstart", function(a, b) {
                    a.originalEvent.touches.length && (z = a.originalEvent.touches[0].pageY)
                });
                b.bind("touchmove", function(b) {
                    k || b.originalEvent.preventDefault();
                    b.originalEvent.touches.length &&
                    (m((z - b.originalEvent.touches[0].pageY) / a.touchScrollStep, !0), z = b.originalEvent.touches[0].pageY)
                });
                w();
                "bottom" === a.start ? (c.css({top: b.outerHeight() - c.outerHeight()}), m(0, !0)) : "top" !== a.start && (m(f(a.start).position().top, null, !0), a.alwaysVisible || c.hide());
                C()
            }
        });
        return this
    }});
    jQuery.fn.extend({slimscroll: jQuery.fn.slimScroll})
})(jQuery);

/*! iCheck v1.0.1 by Damir Sultanov, http://git.io/arlzeA, MIT Licensed */
(function(h) {
    function F(a, b, d) {
        var c = a[0], e = /er/.test(d) ? m : /bl/.test(d) ? s : l, f = d == H ? {checked: c[l], disabled: c[s], indeterminate: "true" == a.attr(m) || "false" == a.attr(w)} : c[e];
        if (/^(ch|di|in)/.test(d) && !f)
            D(a, e);
        else if (/^(un|en|de)/.test(d) && f)
            t(a, e);
        else if (d == H)
            for (e in f)
                f[e] ? D(a, e, !0) : t(a, e, !0);
        else if (!b || "toggle" == d) {
            if (!b)
                a[p]("ifClicked");
            f ? c[n] !== u && t(a, e) : D(a, e)
        }
    }
    function D(a, b, d) {
        var c = a[0], e = a.parent(), f = b == l, A = b == m, B = b == s, K = A ? w : f ? E : "enabled", p = k(a, K + x(c[n])), N = k(a, b + x(c[n]));
        if (!0 !== c[b]) {
            if (!d &&
                b == l && c[n] == u && c.name) {
                var C = a.closest("form"), r = 'input[name="' + c.name + '"]', r = C.length ? C.find(r) : h(r);
                r.each(function() {
                    this !== c && h(this).data(q) && t(h(this), b)
                })
            }
            A ? (c[b] = !0, c[l] && t(a, l, "force")) : (d || (c[b] = !0), f && c[m] && t(a, m, !1));
            L(a, f, b, d)
        }
        c[s] && k(a, y, !0) && e.find("." + I).css(y, "default");
        e[v](N || k(a, b) || "");
        B ? e.attr("aria-disabled", "true") : e.attr("aria-checked", A ? "mixed" : "true");
        e[z](p || k(a, K) || "")
    }
    function t(a, b, d) {
        var c = a[0], e = a.parent(), f = b == l, h = b == m, q = b == s, p = h ? w : f ? E : "enabled", t = k(a, p + x(c[n])),
            u = k(a, b + x(c[n]));
        if (!1 !== c[b]) {
            if (h || !d || "force" == d)
                c[b] = !1;
            L(a, f, p, d)
        }
        !c[s] && k(a, y, !0) && e.find("." + I).css(y, "pointer");
        e[z](u || k(a, b) || "");
        q ? e.attr("aria-disabled", "false") : e.attr("aria-checked", "false");
        e[v](t || k(a, p) || "")
    }
    function M(a, b) {
        if (a.data(q)) {
            a.parent().html(a.attr("style", a.data(q).s || ""));
            if (b)
                a[p](b);
            a.off(".i").unwrap();
            h(G + '[for="' + a[0].id + '"]').add(a.closest(G)).off(".i")
        }
    }
    function k(a, b, d) {
        if (a.data(q))
            return a.data(q).o[b + (d ? "" : "Class")]
    }
    function x(a) {
        return a.charAt(0).toUpperCase() +
            a.slice(1)
    }
    function L(a, b, d, c) {
        if (!c) {
            if (b)
                a[p]("ifToggled");
            a[p]("ifChanged")[p]("if" + x(d))
        }
    }
    var q = "iCheck", I = q + "-helper", u = "radio", l = "checked", E = "un" + l, s = "disabled", w = "determinate", m = "in" + w, H = "update", n = "type", v = "addClass", z = "removeClass", p = "trigger", G = "label", y = "cursor", J = /ipad|iphone|ipod|android|blackberry|windows phone|opera mini|silk/i.test(navigator.userAgent);
    h.fn[q] = function(a, b) {
        var d = 'input[type="checkbox"], input[type="' + u + '"]', c = h(), e = function(a) {
            a.each(function() {
                var a = h(this);
                c = a.is(d) ?
                    c.add(a) : c.add(a.find(d))
            })
        };
        if (/^(check|uncheck|toggle|indeterminate|determinate|disable|enable|update|destroy)$/i.test(a))
            return a = a.toLowerCase(), e(this), c.each(function() {
                var c = h(this);
                "destroy" == a ? M(c, "ifDestroyed") : F(c, !0, a);
                h.isFunction(b) && b()
            });
        if ("object" != typeof a && a)
            return this;
        var f = h.extend({checkedClass: l, disabledClass: s, indeterminateClass: m, labelHover: !0, aria: !1}, a), k = f.handle, B = f.hoverClass || "hover", x = f.focusClass || "focus", w = f.activeClass || "active", y = !!f.labelHover, C = f.labelHoverClass ||
            "hover", r = ("" + f.increaseArea).replace("%", "") | 0;
        if ("checkbox" == k || k == u)
            d = 'input[type="' + k + '"]';
        -50 > r && (r = -50);
        e(this);
        return c.each(function() {
            var a = h(this);
            M(a);
            var c = this, b = c.id, e = -r + "%", d = 100 + 2 * r + "%", d = {position: "absolute", top: e, left: e, display: "block", width: d, height: d, margin: 0, padding: 0, background: "#fff", border: 0, opacity: 0}, e = J ? {position: "absolute", visibility: "hidden"} : r ? d : {position: "absolute", opacity: 0}, k = "checkbox" == c[n] ? f.checkboxClass || "icheckbox" : f.radioClass || "i" + u, m = h(G + '[for="' + b + '"]').add(a.closest(G)),
                A = !!f.aria, E = q + "-" + Math.random().toString(36).replace("0.", ""), g = '<div class="' + k + '" ' + (A ? 'role="' + c[n] + '" ' : "");
            m.length && A && m.each(function() {
                g += 'aria-labelledby="';
                this.id ? g += this.id : (this.id = E, g += E);
                g += '"'
            });
            g = a.wrap(g + "/>")[p]("ifCreated").parent().append(f.insert);
            d = h('<ins class="' + I + '"/>').css(d).appendTo(g);
            a.data(q, {o: f, s: a.attr("style")}).css(e);
            f.inheritClass && g[v](c.className || "");
            f.inheritID && b && g.attr("id", q + "-" + b);
            "static" == g.css("position") && g.css("position", "relative");
            F(a, !0, H);
            if (m.length)
                m.on("click.i mouseover.i mouseout.i touchbegin.i touchend.i", function(b) {
                    var d = b[n], e = h(this);
                    if (!c[s]) {
                        if ("click" == d) {
                            if (h(b.target).is("a"))
                                return;
                            F(a, !1, !0)
                        } else
                            y && (/ut|nd/.test(d) ? (g[z](B), e[z](C)) : (g[v](B), e[v](C)));
                        if (J)
                            b.stopPropagation();
                        else
                            return!1
                    }
                });
            a.on("click.i focus.i blur.i keyup.i keydown.i keypress.i", function(b) {
                var d = b[n];
                b = b.keyCode;
                if ("click" == d)
                    return!1;
                if ("keydown" == d && 32 == b)
                    return c[n] == u && c[l] || (c[l] ? t(a, l) : D(a, l)), !1;
                if ("keyup" == d && c[n] == u)
                    !c[l] && D(a, l);
                else if (/us|ur/.test(d))
                    g["blur" ==
                    d ? z : v](x)
            });
            d.on("click mousedown mouseup mouseover mouseout touchbegin.i touchend.i", function(b) {
                var d = b[n], e = /wn|up/.test(d) ? w : B;
                if (!c[s]) {
                    if ("click" == d)
                        F(a, !1, !0);
                    else {
                        if (/wn|er|in/.test(d))
                            g[v](e);
                        else
                            g[z](e + " " + w);
                        if (m.length && y && e == B)
                            m[/ut|nd/.test(d) ? z : v](C)
                    }
                    if (J)
                        b.stopPropagation();
                    else
                        return!1
                }
            })
        })
    }
})(window.jQuery || window.Zepto);

(function($){
    "use strict";
    //$('[data-timepicker]').timepicker();
    //var format, picker = $('[data-datepicker]');
    //for(var n = 0;  n < picker.length; ++n) {
    //    format = $(picker[n]).attr('data-format') || 'yy-mm-dd';
    //    $(picker[n]).datepicker({
    //        showInputs: false,
    //        dateFormat: format
    //    });
    //}

    $('[data-timepicker]').datetimepicker({
        pickDate: false,
        pickTime: true,
        useSeconds: true,
        language: myApplication.locale
    });

    $('[data-datepicker]').datetimepicker({
        pickDate: true,
        pickTime: false,
        language: myApplication.locale
    });

    $('[data-datetimepicker]').datetimepicker({
        pickDate: true,
        pickTime: true,
        useSeconds: true,
        language: myApplication.locale
    });



    $(document)
        // -- ionRangeSlider ------------------------------------------------------------------------------------------
        .on('focus click','[data-toggle=ionrangeslider]', function(){
            var $this = $(this);
            if ($this.data('ionRangeSlider') != undefined) {
                return;
            } else if('ionRangeSlider' in $.fn == false) {
                console.error("ionRangeSlider lib is missing, the js & css must be included");
                return;
            }

            // Launch plugin
            $this.ionRangeSlider();
        })
        .on('ready loaded.bs.modal',function(){
            if('ionRangeSlider' in $.fn) {
                $('[data-toggle=ionrangeslider]').ionRangeSlider();
            }
        })
        // -- daterange ----------------------------------------------------------------------------------------------
        .on('focus click','[data-toggle=daterangepicker]', function(){
            var $this = $(this);
            if ($this.data('daterangepicker') != undefined) {
                return;
            } else if(('daterangepicker' in $.fn) == false) {
                console.error("daterangepicker lib is missing, the js must be included");
                return;
            } else if('moment' in window == false) {
                console.error("moment lib is missing, the js must be included");
                return;
            }
            var option = {
                timePicker: true,
                timePickerIncrement: 30,
                timePicker12Hour: false,
                format: $this.attr('date-format') != undefined ? $this.attr('date-format') : 'DD/MM/YYYY hh:mm:ss',
                stayInView: true
            };

            if (myApplication.locale == 'fr') {
                option['locale'] = {
                    applyLabel: 'Valider',
                    cancelLabel: 'Fermer',
                    fromLabel: 'Du',
                    toLabel: 'Au',
                    weekLabel: 'W',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: window.moment()._locale._weekdaysMin.slice(),
                    monthNames: window.moment()._locale._monthsShort.slice(),
                    firstDay: 0
                };
            }

            $this
                .daterangepicker(option)
                .click();
        })
        // -- colorpicker ----------------------------------------------------------------------------------------------
        .on( 'focus click', '[data-colorpicker-init]', function(e){
            var $this = $(this);
            if ($this.data('colorpicker'))
                return;
            e.preventDefault();
            // component click requires us to explicitly show it
            $this.colorpicker($this.data('data-colorpicker-init'));
        })
        .on('ready loaded.bs.modal',function(){
            if('colorpicker' in $.fn) {
                $('[data-colorpicker-init]').each(function (i, el) {
                    var $this = $(this);
                    $this.colorpicker($this.data('data-colorpicker-init'));
                });
            }
        })
        // -------------------------------------------------------------------------------------------------------------
        .on('ready loaded.bs.modal',function(){
            $('[data-ckeditor]').each(function(i, el) {
                var $this = $(this);
                if ('CKEDITOR' in window) {
                    CKEDITOR.replace($this);
                } else {
                    console.error("CKEDITOR lib is missing, the js must be included");
                }
                //$this.colorpicker($this.data('data-ckeditor'));
            });
        })
        .on('ready loaded.bs.modal',function(){
            $('[data-wysihtml5]').each(function(i, el) {
                var $this = $(this);
                if ('wysihtml5' in window) {
                    $this.wysihtml5($this.data('data-wysihtml5'));
                } else {
                    console.error("wysihtml5  lib is missing, the js must be included");
                }
            });
        })
        // ---- tabajax ------------------------------------------------------------------------------------------------
        .on('click', '[data-toggle="tabajax"]', function(e) {
            var $this = $(this),
                loadurl = $this.attr('href'),
                $tabTarg = $($this.data('target')),
                $tabCurrent = $($this.parents('.nav-tabs:first')
                        .find('.active [data-toggle=tab]')
                        .attr('href')
                );

            //
            $tabTarg.height($tabCurrent.height());

            $.get(loadurl, function(data) {
                $tabTarg
                    .height('auto')
                    .html(data);
                $this
                    .attr('data-toggle','tab')
                    .attr('href',$this.data('target'))
                    .removeAttr('data-target')
                ;
                $tabTarg.find('[data-toggle="select2-remote"]').select2remote();
            });

            /* Update hash based on tab */
            window.location = $(this).data("target");
            scrollTo(0,0);

            $this.tab('show');
            return false;
        })
        .on('click', "[data-toggle=pill],[data-toggle=tab]",function (event) {
            /* Update hash based on tab */
            window.location =  $(this).attr("href");
            scrollTo(0,0);
        })
        .on('ready', function(){
            /* Automagically jump on good tab based on anchor */
            var url = document.location.href.split('#');
            if(url[1] != undefined) {
                $('[data-toggle="tab"][href=#'+url[1]+']').tab('show');
                $('[data-toggle="tabajax"][data-target=#'+url[1]+']').click();
            }
        })
    ;

    /* -------------------------------------------------------------------------------------------------------------- */

    /**
     * Supprime un contenu de la page si la réponse de la requette est positive
     * <* href="lien vers fonction de suppersion"   Obligatoire si le tag n'est un 'a'    >
     *          data-target="lien vers ..."         Obligatoire si le tag n'est pas un 'a' >
     *          data-toggle="delete"                Obligatoire >
     *          data-parent="tr"                    Optionnel   > selecteur du parants a Supprime, par défaut cherche le parent tr
     *      />blabla</*>
     */
    $(document).on(
        'click',
        '[data-toggle="delete"]',
        function(e){
            e && e.preventDefault();
            e.stopPropagation();
            var $this =  $(this);
            var url = $this.is('[href]') ? $this.attr('href').trim() : $this.attr('data-target').trim();
            var $cible = $this.is('[data-parent]') ? $this.parents($this.attr('data-parent')).first() : $this.parents('tr:first');
            if ($cible.hasClass('loading') == false && (!$this.is('[data-confirm]') || confirm($this.attr('data-confirm')))) {
                $.ajax({
                    'url': url,
                    'beforeSend': function () {
                        $cible.addClass('loading');
                    },
                    'success': function (data) {
                        if (data.result) {
                            // on test pour voir si on a pas affire a un datatable
                            if ($cible.is('tr') && $.fn.dataTable.isDataTable($cible.parents('table:first'))) {
                                $($cible.parents('table:first')).DataTable()
                                    .row($cible)
                                    .remove()
                                    .draw();
                            } else {
                                var $p = $cible.parent();
                                $cible.remove();
                                $p.change();
                            }
                        } else {
                            alert(data.message);
                        }
                    },
                    'complete': function () {
                        $cible.removeClass("loading");
                    }
                });
            }
            return false;
        });

    /* -------------------------------------------------------------------------------------------------------------- */

    /**
     * Active le plugin Select2 http://ivaynberg.github.io/select2/
     * <select  data-toggle="select2"               Obligatoire >
     *          data-source="lien vers ..."         Optionnel   > url de la fonction pour faire de l'autocompletion, le résultat attendu est  {'results':[],'more':true|false,'message':null|text}
     *          data-target="table"                 Optionnel   > selecteur dans lequelle sera ajouter le contenu html de la propriété de "html" du result séléctionée
     *          data-minimumInputLength=""          Optionnel   >
     *          data-maximumInputLength=""          Optionnel   >
     *
     *          data-initselection=""               Optionnel   > A utiliser avec les formulaire Symfony , definit les valeurs au chargement de la page
     *          required="required"                 Optionnel   > A utiliser dans les relation ManyToOne avec le type de formulaire Symfony "entity_select2"
     *          multiple="*"                        Optionnel   > A utiliser dans les relation OneToMany avec le type de formulaire Symfony "collection_select2"
     *          data-maximumSelectionSize=""        Optionnel   > A utiliser dans les relation OneToMany avec le type de formulaire Symfony "collection_select2"
     *
     *      /></select>                                           et/ou le contenu de la propriété de "html" de la requette ajax effectée a partir de la propriété de "confirme" du result séléctionée
     *
     * Exemple de response pour :
     * data-source               > {'results':[],'more':true|false,'message':null|text}
     * data-source + data-target > {'results':[],'more':true|false,'message':null|text,'html':'text html'}
     * data-source + data-target >{'results':[],'more':true|false,'message':null|text,'confirme': 'url de recu pour charger le contenu'} puis sur confirme {'result':true|false,'message':null|text,'html':'text html'}
     *
     */
    $.fn.select2remote = function () {
        this.each(function (i,el) {
            var $this = $(el),
                param = {
                    'multiple'   : $this.is('[multiple]'),
                    'allowClear' : $this.is('[required]') == false,
                    'minimumInputLength': 1
                },
                appendData = function($target, content) {
                    var $row = arguments.length>1 ? arguments[2] : null;
                    console.log($row);
                    if ($target.is('tbody') && $.fn.dataTable.isDataTable($target.parents('table:first'))) {
                        $($target.parents('table:first')).Datatable().draw();
                    } else if ($target.is('table') && $.fn.dataTable.isDataTable($target))  {
                        $target.DataTable().draw();
                    } else if ($target.is('input'))  {
                        $target.val(content);
                    } else {
                        if ($row != null) {
                            $row.replaceWith(content); // replaceWithPolyfill(data.html);
                        } else {
                            $row = $(content).appendTo($target); // appendPolyfillTo($target)
                        }
                    }
                    $target.change();
                    return $row;
                }
                ;
            for(var $prop in {'minimumInputLength':0,'maximumInputLength':0,'maximumSelectionSize':0}) {
                if ($this.data($prop.toLocaleLowerCase()) != undefined) {
                    param[$prop] = $this.data($prop.toLocaleLowerCase());
                }
            }


            if ($this.data('initselection') != undefined) {
                param['initSelection'] = function (element, callback) {
                    callback($this.data('initselection'));
                };
            }

            if ($this.data('source') != undefined) {
                // si on a du remote, on recupere les données
                param['ajax'] = {
                    url: function(){ return $this.data('source'); },
                    dataType: 'jsonp',
                    quietMillis: 500,
                    data: function (term, page) { // page is the one-based page number tracked by Select2
                        return {
                            q: term, //search term
                            page_limit: 10, // page size
                            page: page, // page number
                            apikey: $this.attr('id') // please do not use so this example keeps working
                        };
                    },
                    results: function (data, page) { return data; }
                };

                var DIACRITICS = {"\u24B6":"A","\uFF21":"A","\u00C0":"A","\u00C1":"A","\u00C2":"A","\u1EA6":"A","\u1EA4":"A","\u1EAA":"A","\u1EA8":"A","\u00C3":"A","\u0100":"A","\u0102":"A","\u1EB0":"A","\u1EAE":"A","\u1EB4":"A","\u1EB2":"A","\u0226":"A","\u01E0":"A","\u00C4":"A","\u01DE":"A","\u1EA2":"A","\u00C5":"A","\u01FA":"A","\u01CD":"A","\u0200":"A","\u0202":"A","\u1EA0":"A","\u1EAC":"A","\u1EB6":"A","\u1E00":"A","\u0104":"A","\u023A":"A","\u2C6F":"A","\uA732":"AA","\u00C6":"AE","\u01FC":"AE","\u01E2":"AE","\uA734":"AO","\uA736":"AU","\uA738":"AV","\uA73A":"AV","\uA73C":"AY","\u24B7":"B","\uFF22":"B","\u1E02":"B","\u1E04":"B","\u1E06":"B","\u0243":"B","\u0182":"B","\u0181":"B","\u24B8":"C","\uFF23":"C","\u0106":"C","\u0108":"C","\u010A":"C","\u010C":"C","\u00C7":"C","\u1E08":"C","\u0187":"C","\u023B":"C","\uA73E":"C","\u24B9":"D","\uFF24":"D","\u1E0A":"D","\u010E":"D","\u1E0C":"D","\u1E10":"D","\u1E12":"D","\u1E0E":"D","\u0110":"D","\u018B":"D","\u018A":"D","\u0189":"D","\uA779":"D","\u01F1":"DZ","\u01C4":"DZ","\u01F2":"Dz","\u01C5":"Dz","\u24BA":"E","\uFF25":"E","\u00C8":"E","\u00C9":"E","\u00CA":"E","\u1EC0":"E","\u1EBE":"E","\u1EC4":"E","\u1EC2":"E","\u1EBC":"E","\u0112":"E","\u1E14":"E","\u1E16":"E","\u0114":"E","\u0116":"E","\u00CB":"E","\u1EBA":"E","\u011A":"E","\u0204":"E","\u0206":"E","\u1EB8":"E","\u1EC6":"E","\u0228":"E","\u1E1C":"E","\u0118":"E","\u1E18":"E","\u1E1A":"E","\u0190":"E","\u018E":"E","\u24BB":"F","\uFF26":"F","\u1E1E":"F","\u0191":"F","\uA77B":"F","\u24BC":"G","\uFF27":"G","\u01F4":"G","\u011C":"G","\u1E20":"G","\u011E":"G","\u0120":"G","\u01E6":"G","\u0122":"G","\u01E4":"G","\u0193":"G","\uA7A0":"G","\uA77D":"G","\uA77E":"G","\u24BD":"H","\uFF28":"H","\u0124":"H","\u1E22":"H","\u1E26":"H","\u021E":"H","\u1E24":"H","\u1E28":"H","\u1E2A":"H","\u0126":"H","\u2C67":"H","\u2C75":"H","\uA78D":"H","\u24BE":"I","\uFF29":"I","\u00CC":"I","\u00CD":"I","\u00CE":"I","\u0128":"I","\u012A":"I","\u012C":"I","\u0130":"I","\u00CF":"I","\u1E2E":"I","\u1EC8":"I","\u01CF":"I","\u0208":"I","\u020A":"I","\u1ECA":"I","\u012E":"I","\u1E2C":"I","\u0197":"I","\u24BF":"J","\uFF2A":"J","\u0134":"J","\u0248":"J","\u24C0":"K","\uFF2B":"K","\u1E30":"K","\u01E8":"K","\u1E32":"K","\u0136":"K","\u1E34":"K","\u0198":"K","\u2C69":"K","\uA740":"K","\uA742":"K","\uA744":"K","\uA7A2":"K","\u24C1":"L","\uFF2C":"L","\u013F":"L","\u0139":"L","\u013D":"L","\u1E36":"L","\u1E38":"L","\u013B":"L","\u1E3C":"L","\u1E3A":"L","\u0141":"L","\u023D":"L","\u2C62":"L","\u2C60":"L","\uA748":"L","\uA746":"L","\uA780":"L","\u01C7":"LJ","\u01C8":"Lj","\u24C2":"M","\uFF2D":"M","\u1E3E":"M","\u1E40":"M","\u1E42":"M","\u2C6E":"M","\u019C":"M","\u24C3":"N","\uFF2E":"N","\u01F8":"N","\u0143":"N","\u00D1":"N","\u1E44":"N","\u0147":"N","\u1E46":"N","\u0145":"N","\u1E4A":"N","\u1E48":"N","\u0220":"N","\u019D":"N","\uA790":"N","\uA7A4":"N","\u01CA":"NJ","\u01CB":"Nj","\u24C4":"O","\uFF2F":"O","\u00D2":"O","\u00D3":"O","\u00D4":"O","\u1ED2":"O","\u1ED0":"O","\u1ED6":"O","\u1ED4":"O","\u00D5":"O","\u1E4C":"O","\u022C":"O","\u1E4E":"O","\u014C":"O","\u1E50":"O","\u1E52":"O","\u014E":"O","\u022E":"O","\u0230":"O","\u00D6":"O","\u022A":"O","\u1ECE":"O","\u0150":"O","\u01D1":"O","\u020C":"O","\u020E":"O","\u01A0":"O","\u1EDC":"O","\u1EDA":"O","\u1EE0":"O","\u1EDE":"O","\u1EE2":"O","\u1ECC":"O","\u1ED8":"O","\u01EA":"O","\u01EC":"O","\u00D8":"O","\u01FE":"O","\u0186":"O","\u019F":"O","\uA74A":"O","\uA74C":"O","\u01A2":"OI","\uA74E":"OO","\u0222":"OU","\u24C5":"P","\uFF30":"P","\u1E54":"P","\u1E56":"P","\u01A4":"P","\u2C63":"P","\uA750":"P","\uA752":"P","\uA754":"P","\u24C6":"Q","\uFF31":"Q","\uA756":"Q","\uA758":"Q","\u024A":"Q","\u24C7":"R","\uFF32":"R","\u0154":"R","\u1E58":"R","\u0158":"R","\u0210":"R","\u0212":"R","\u1E5A":"R","\u1E5C":"R","\u0156":"R","\u1E5E":"R","\u024C":"R","\u2C64":"R","\uA75A":"R","\uA7A6":"R","\uA782":"R","\u24C8":"S","\uFF33":"S","\u1E9E":"S","\u015A":"S","\u1E64":"S","\u015C":"S","\u1E60":"S","\u0160":"S","\u1E66":"S","\u1E62":"S","\u1E68":"S","\u0218":"S","\u015E":"S","\u2C7E":"S","\uA7A8":"S","\uA784":"S","\u24C9":"T","\uFF34":"T","\u1E6A":"T","\u0164":"T","\u1E6C":"T","\u021A":"T","\u0162":"T","\u1E70":"T","\u1E6E":"T","\u0166":"T","\u01AC":"T","\u01AE":"T","\u023E":"T","\uA786":"T","\uA728":"TZ","\u24CA":"U","\uFF35":"U","\u00D9":"U","\u00DA":"U","\u00DB":"U","\u0168":"U","\u1E78":"U","\u016A":"U","\u1E7A":"U","\u016C":"U","\u00DC":"U","\u01DB":"U","\u01D7":"U","\u01D5":"U","\u01D9":"U","\u1EE6":"U","\u016E":"U","\u0170":"U","\u01D3":"U","\u0214":"U","\u0216":"U","\u01AF":"U","\u1EEA":"U","\u1EE8":"U","\u1EEE":"U","\u1EEC":"U","\u1EF0":"U","\u1EE4":"U","\u1E72":"U","\u0172":"U","\u1E76":"U","\u1E74":"U","\u0244":"U","\u24CB":"V","\uFF36":"V","\u1E7C":"V","\u1E7E":"V","\u01B2":"V","\uA75E":"V","\u0245":"V","\uA760":"VY","\u24CC":"W","\uFF37":"W","\u1E80":"W","\u1E82":"W","\u0174":"W","\u1E86":"W","\u1E84":"W","\u1E88":"W","\u2C72":"W","\u24CD":"X","\uFF38":"X","\u1E8A":"X","\u1E8C":"X","\u24CE":"Y","\uFF39":"Y","\u1EF2":"Y","\u00DD":"Y","\u0176":"Y","\u1EF8":"Y","\u0232":"Y","\u1E8E":"Y","\u0178":"Y","\u1EF6":"Y","\u1EF4":"Y","\u01B3":"Y","\u024E":"Y","\u1EFE":"Y","\u24CF":"Z","\uFF3A":"Z","\u0179":"Z","\u1E90":"Z","\u017B":"Z","\u017D":"Z","\u1E92":"Z","\u1E94":"Z","\u01B5":"Z","\u0224":"Z","\u2C7F":"Z","\u2C6B":"Z","\uA762":"Z","\u24D0":"a","\uFF41":"a","\u1E9A":"a","\u00E0":"a","\u00E1":"a","\u00E2":"a","\u1EA7":"a","\u1EA5":"a","\u1EAB":"a","\u1EA9":"a","\u00E3":"a","\u0101":"a","\u0103":"a","\u1EB1":"a","\u1EAF":"a","\u1EB5":"a","\u1EB3":"a","\u0227":"a","\u01E1":"a","\u00E4":"a","\u01DF":"a","\u1EA3":"a","\u00E5":"a","\u01FB":"a","\u01CE":"a","\u0201":"a","\u0203":"a","\u1EA1":"a","\u1EAD":"a","\u1EB7":"a","\u1E01":"a","\u0105":"a","\u2C65":"a","\u0250":"a","\uA733":"aa","\u00E6":"ae","\u01FD":"ae","\u01E3":"ae","\uA735":"ao","\uA737":"au","\uA739":"av","\uA73B":"av","\uA73D":"ay","\u24D1":"b","\uFF42":"b","\u1E03":"b","\u1E05":"b","\u1E07":"b","\u0180":"b","\u0183":"b","\u0253":"b","\u24D2":"c","\uFF43":"c","\u0107":"c","\u0109":"c","\u010B":"c","\u010D":"c","\u00E7":"c","\u1E09":"c","\u0188":"c","\u023C":"c","\uA73F":"c","\u2184":"c","\u24D3":"d","\uFF44":"d","\u1E0B":"d","\u010F":"d","\u1E0D":"d","\u1E11":"d","\u1E13":"d","\u1E0F":"d","\u0111":"d","\u018C":"d","\u0256":"d","\u0257":"d","\uA77A":"d","\u01F3":"dz","\u01C6":"dz","\u24D4":"e","\uFF45":"e","\u00E8":"e","\u00E9":"e","\u00EA":"e","\u1EC1":"e","\u1EBF":"e","\u1EC5":"e","\u1EC3":"e","\u1EBD":"e","\u0113":"e","\u1E15":"e","\u1E17":"e","\u0115":"e","\u0117":"e","\u00EB":"e","\u1EBB":"e","\u011B":"e","\u0205":"e","\u0207":"e","\u1EB9":"e","\u1EC7":"e","\u0229":"e","\u1E1D":"e","\u0119":"e","\u1E19":"e","\u1E1B":"e","\u0247":"e","\u025B":"e","\u01DD":"e","\u24D5":"f","\uFF46":"f","\u1E1F":"f","\u0192":"f","\uA77C":"f","\u24D6":"g","\uFF47":"g","\u01F5":"g","\u011D":"g","\u1E21":"g","\u011F":"g","\u0121":"g","\u01E7":"g","\u0123":"g","\u01E5":"g","\u0260":"g","\uA7A1":"g","\u1D79":"g","\uA77F":"g","\u24D7":"h","\uFF48":"h","\u0125":"h","\u1E23":"h","\u1E27":"h","\u021F":"h","\u1E25":"h","\u1E29":"h","\u1E2B":"h","\u1E96":"h","\u0127":"h","\u2C68":"h","\u2C76":"h","\u0265":"h","\u0195":"hv","\u24D8":"i","\uFF49":"i","\u00EC":"i","\u00ED":"i","\u00EE":"i","\u0129":"i","\u012B":"i","\u012D":"i","\u00EF":"i","\u1E2F":"i","\u1EC9":"i","\u01D0":"i","\u0209":"i","\u020B":"i","\u1ECB":"i","\u012F":"i","\u1E2D":"i","\u0268":"i","\u0131":"i","\u24D9":"j","\uFF4A":"j","\u0135":"j","\u01F0":"j","\u0249":"j","\u24DA":"k","\uFF4B":"k","\u1E31":"k","\u01E9":"k","\u1E33":"k","\u0137":"k","\u1E35":"k","\u0199":"k","\u2C6A":"k","\uA741":"k","\uA743":"k","\uA745":"k","\uA7A3":"k","\u24DB":"l","\uFF4C":"l","\u0140":"l","\u013A":"l","\u013E":"l","\u1E37":"l","\u1E39":"l","\u013C":"l","\u1E3D":"l","\u1E3B":"l","\u017F":"l","\u0142":"l","\u019A":"l","\u026B":"l","\u2C61":"l","\uA749":"l","\uA781":"l","\uA747":"l","\u01C9":"lj","\u24DC":"m","\uFF4D":"m","\u1E3F":"m","\u1E41":"m","\u1E43":"m","\u0271":"m","\u026F":"m","\u24DD":"n","\uFF4E":"n","\u01F9":"n","\u0144":"n","\u00F1":"n","\u1E45":"n","\u0148":"n","\u1E47":"n","\u0146":"n","\u1E4B":"n","\u1E49":"n","\u019E":"n","\u0272":"n","\u0149":"n","\uA791":"n","\uA7A5":"n","\u01CC":"nj","\u24DE":"o","\uFF4F":"o","\u00F2":"o","\u00F3":"o","\u00F4":"o","\u1ED3":"o","\u1ED1":"o","\u1ED7":"o","\u1ED5":"o","\u00F5":"o","\u1E4D":"o","\u022D":"o","\u1E4F":"o","\u014D":"o","\u1E51":"o","\u1E53":"o","\u014F":"o","\u022F":"o","\u0231":"o","\u00F6":"o","\u022B":"o","\u1ECF":"o","\u0151":"o","\u01D2":"o","\u020D":"o","\u020F":"o","\u01A1":"o","\u1EDD":"o","\u1EDB":"o","\u1EE1":"o","\u1EDF":"o","\u1EE3":"o","\u1ECD":"o","\u1ED9":"o","\u01EB":"o","\u01ED":"o","\u00F8":"o","\u01FF":"o","\u0254":"o","\uA74B":"o","\uA74D":"o","\u0275":"o","\u01A3":"oi","\u0223":"ou","\uA74F":"oo","\u24DF":"p","\uFF50":"p","\u1E55":"p","\u1E57":"p","\u01A5":"p","\u1D7D":"p","\uA751":"p","\uA753":"p","\uA755":"p","\u24E0":"q","\uFF51":"q","\u024B":"q","\uA757":"q","\uA759":"q","\u24E1":"r","\uFF52":"r","\u0155":"r","\u1E59":"r","\u0159":"r","\u0211":"r","\u0213":"r","\u1E5B":"r","\u1E5D":"r","\u0157":"r","\u1E5F":"r","\u024D":"r","\u027D":"r","\uA75B":"r","\uA7A7":"r","\uA783":"r","\u24E2":"s","\uFF53":"s","\u00DF":"s","\u015B":"s","\u1E65":"s","\u015D":"s","\u1E61":"s","\u0161":"s","\u1E67":"s","\u1E63":"s","\u1E69":"s","\u0219":"s","\u015F":"s","\u023F":"s","\uA7A9":"s","\uA785":"s","\u1E9B":"s","\u24E3":"t","\uFF54":"t","\u1E6B":"t","\u1E97":"t","\u0165":"t","\u1E6D":"t","\u021B":"t","\u0163":"t","\u1E71":"t","\u1E6F":"t","\u0167":"t","\u01AD":"t","\u0288":"t","\u2C66":"t","\uA787":"t","\uA729":"tz","\u24E4":"u","\uFF55":"u","\u00F9":"u","\u00FA":"u","\u00FB":"u","\u0169":"u","\u1E79":"u","\u016B":"u","\u1E7B":"u","\u016D":"u","\u00FC":"u","\u01DC":"u","\u01D8":"u","\u01D6":"u","\u01DA":"u","\u1EE7":"u","\u016F":"u","\u0171":"u","\u01D4":"u","\u0215":"u","\u0217":"u","\u01B0":"u","\u1EEB":"u","\u1EE9":"u","\u1EEF":"u","\u1EED":"u","\u1EF1":"u","\u1EE5":"u","\u1E73":"u","\u0173":"u","\u1E77":"u","\u1E75":"u","\u0289":"u","\u24E5":"v","\uFF56":"v","\u1E7D":"v","\u1E7F":"v","\u028B":"v","\uA75F":"v","\u028C":"v","\uA761":"vy","\u24E6":"w","\uFF57":"w","\u1E81":"w","\u1E83":"w","\u0175":"w","\u1E87":"w","\u1E85":"w","\u1E98":"w","\u1E89":"w","\u2C73":"w","\u24E7":"x","\uFF58":"x","\u1E8B":"x","\u1E8D":"x","\u24E8":"y","\uFF59":"y","\u1EF3":"y","\u00FD":"y","\u0177":"y","\u1EF9":"y","\u0233":"y","\u1E8F":"y","\u00FF":"y","\u1EF7":"y","\u1E99":"y","\u1EF5":"y","\u01B4":"y","\u024F":"y","\u1EFF":"y","\u24E9":"z","\uFF5A":"z","\u017A":"z","\u1E91":"z","\u017C":"z","\u017E":"z","\u1E93":"z","\u1E95":"z","\u01B6":"z","\u0225":"z","\u0240":"z","\u2C6C":"z","\uA763":"z","\u0386":"\u0391","\u0388":"\u0395","\u0389":"\u0397","\u038A":"\u0399","\u03AA":"\u0399","\u038C":"\u039F","\u038E":"\u03A5","\u03AB":"\u03A5","\u038F":"\u03A9","\u03AC":"\u03B1","\u03AD":"\u03B5","\u03AE":"\u03B7","\u03AF":"\u03B9","\u03CA":"\u03B9","\u0390":"\u03B9","\u03CC":"\u03BF","\u03CD":"\u03C5","\u03CB":"\u03C5","\u03B0":"\u03C5","\u03C9":"\u03C9","\u03C2":"\u03C3"},
                    stripDiacritics = function (str) {
                        // Used 'uni range + named function' from http://jsperf.com/diacritics/18
                        function match(a) {
                            return DIACRITICS[a] || a;
                        }
                        return str.replace(/[^\u0000-\u007E]/g, match);
                    },
                    markMatch = function(text, term, markup, escapeMarkup) {
                        var match=stripDiacritics(text.toUpperCase()).indexOf(stripDiacritics(term.toUpperCase())),
                            tl=term.length;
                        if (match<0) {
                            markup.push(escapeMarkup(text));
                            return;
                        }
                        markup.push(escapeMarkup(text.substring(0, match)));
                        markup.push("<span class='select2-match'>");
                        markup.push(escapeMarkup(text.substring(match, match + tl)));
                        markup.push("</span>");
                        markup.push(escapeMarkup(text.substring(match + tl, text.length)));
                    };

                param['formatResult'] = function (data, container, query, escapeMarkup) {
                    if ('renderValue' in data) {
                        return data.renderValue;
                    } else {
                        var markup=[];
                        markMatch(this.text(data), query.term, markup, escapeMarkup);
                        return markup.join("");
                    }
                };

                param['escapeMarkup:'] = function(data) { return data; };

                if (param.multiple){
                    param['tokenSeparators'] =  [",", " "];
                }
            }

            $this.select2(param);

            // si il y a un target , c'est que l'on doit ajouter quelque chose
            if ($this.data('target') != undefined) {

                $this.on('change',function(e) {
                    if ('added' in e) {
                        var $target = $($this.data('target')),
                            $row = null;

                        // on a du contenu dans la reponse, donc on l'ajoute...
                        if ('html' in e.added) {
                            $row = appendData($target, e.added.html);
                        }
                        // il y a une confirmation d'ajoute, on l'execute...
                        if ('confirme' in e.added) {
                            if ($row!=null) {
                                $row.addClass('loading');
                            }
                            $.ajax({
                                'url': e.added.confirme,
                                'success' : function (data) {
                                    if(data.result) {
                                        $row = appendData($target, data.html, $row);
                                    } else {
                                        alert(data.message);
                                        if ($row!=null) {
                                            $row.remove();
                                        }
                                    }
                                }
                            });
                        }

                        $this.select2('val',null);

                    }
                });
            }
        });
        return this;
    };

    $(document)
        .on('focus click','[data-toggle="select2-remote"]',function(e){
            var $this = $(this);
            if ($this.data('select2'))
                return;
            e.preventDefault();
            // component click requires us to explicitly show it
            $this
                .select2remote()
                .select2("open");
        })
        .on('ready loaded.bs.modal',function(){
            $('[data-toggle="select2-remote"]').select2remote();
        })
    ;

    /* -------------------------------------------------------------------------------------------------------------- */

})(window.jQuery || window.Zepto);

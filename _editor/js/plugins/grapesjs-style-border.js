/** grapesjs-style-border - 1.0.4 */
!function(e, t) {
    'object' == typeof exports && 'object' == typeof module ? module.exports = t() : 'function' == typeof define && define.amd ? define([], t) : 'object' == typeof exports ? exports["grapesjs-style-border"] = t() : e["grapesjs-style-border"] = t()
}('undefined' != typeof globalThis ? globalThis : 'undefined' != typeof window ? window : this, (() => (() => {
    "use strict";
    var e = {
        d    : (t, r) => {
            for (var o in r) e.o(r, o) && !e.o(t, o) && Object.defineProperty(t, o, {enumerable : !0, get : r[o]})
        }, o : (e, t) => Object.prototype.hasOwnProperty.call(e, t), r : e => {
            'undefined' != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value : 'Module'}), Object.defineProperty(e, '__esModule', {value : !0})
        }
    }, t  = {};

    function r(e, t, r) {
        return t in e ? Object.defineProperty(e, t, {
            value        : r,
            enumerable   : !0,
            configurable : !0,
            writable     : !0
        }) : e[t] = r, e
    }

    function o(e, t) {
        var r = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var o = Object.getOwnPropertySymbols(e);
            t && (o = o.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }))), r.push.apply(r, o)
        }
        return r
    }

    function n(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? o(Object(n), !0).forEach((function(t) {
                r(e, t, n[t])
            })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : o(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }))
        }
        return e
    }

    e.r(t), e.d(t, {default : () => p});
    const p = function(e) {
        var t, r                                                                                                   = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {}, o                          = n(n({}, {
                sector             : 'decorations',
                originalBorder     : {sector : 'decorations', name : 'border'},
                extendType         : {},
                extendWidth        : {},
                extendStyle        : {},
                extendColor        : {},
                extendBorder       : {},
                extendBorderTop    : {},
                extendBorderLeft   : {},
                extendBorderBottom : {},
                extendBorderRight  : {},
                at                 : !1
            }), r), p                                                                                              = e.Styles, i                                                                                = [], a = e.getConfig('stylePrefix'), l = ".".concat(a, "sm-property"),
            d                                                                                                      = function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : '';
                i.forEach((function(r) {
                    if (r) {
                        var o = (r.view || {}).$el;
                        o && (o.show(), r.get('property') === "border".concat(e) ? t.view.$el.insertBefore(o.find(l).first()) : o.hide())
                    }
                })), i.forEach((function(t) {
                    if (t) {
                        t.get('property') !== "border".concat(e) ? t.set('requires', {display : 'invisible'}) : t.set('requires', null)
                    }
                }))
            }, s                                                                                                   = n({
                property   : 'border-type',
                type       : 'composite',
                properties : [n({
                    property : 'border-options',
                    type     : 'radio',
                    name     : ' ',
                    default  : 'all',
                    options  : [{value : 'all', title : 'all', className : 'fa fa-arrows'}, {
                        value     : 'top',
                        title     : 'top',
                        className : 'fa fa-long-arrow-up'
                    }, {value : 'right', title : 'right', className : 'fa fa-long-arrow-right'}, {
                        value     : 'bottom',
                        title     : 'bottom',
                        className : 'fa fa-long-arrow-down'
                    }, {value : 'left', title : 'left', className : 'fa fa-long-arrow-left'}],
                    onChange : function(e) {
                        e.property;
                        var t = e.to.value;
                        if (null != t) {
                            var r = t && 'all' !== t ? "-".concat(t) : '';
                            d(r)
                        }
                    }
                }, o.extendType)],
                toStyle    : function(e, t) {
                    t.name;
                    return {}
                }
            }, o.extendType), c                                                                                    = n({
                property : 'border-style',
                type     : 'select',
                default  : 'solid',
                full     : 'true',
                options  : [{value : 'none'}, {value : 'solid'}, {value : 'dotted'}, {value : 'dashed'}, {value : 'double'}, {value : 'groove'}, {value : 'ridge'}, {value : 'inset'}, {value : 'outset'}]
            }, o.extendStyle), f                                                                                   = n({
                property : 'border-width',
                units    : ['px', 'em', 'rem', 'vh', 'vw'],
                unit     : 'px',
                type     : 'slider',
                defaults : 1,
                min      : 0,
                max      : 20
            }, o.extendWidth), u = n({property : 'border-color', type : 'color'}, o.extendColor), y = [f, c, u], b = [n({
                property   : 'border',
                type       : 'composite',
                properties : y
            }, o.extendBorder), n({
                property   : 'border-top',
                type       : 'composite',
                properties : y
            }, o.extendBorderTop), n({
                property   : 'border-right',
                type       : 'composite',
                properties : y
            }, o.extendBorderRight), n({
                property   : 'border-bottom',
                type       : 'composite',
                properties : y
            }, o.extendBorderBottom), n({
                property   : 'border-left',
                type       : 'composite',
                properties : y
            }, o.extendBorderLeft)];
        e.on('load', (function() {
            var e = o.originalBorder, r = o.sector, n = o.at;
            p.removeProperty(e.sector, e.name), t = p.addProperty(r, s, n ? {at : n} : {}), b.forEach((function(e) {
                i.push(p.addProperty(r, e, n ? {at : n} : {}))
            })), d()
        }))
    };
    return t
})()));

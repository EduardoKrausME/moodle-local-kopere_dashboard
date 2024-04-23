/** grapesjs-blocks-table - 1.1.2 */
!function(e, t) {
    'object' == typeof exports && 'object' == typeof module ? module.exports = t() : 'function' == typeof define && define.amd ? define([], t) : 'object' == typeof exports ? exports["grapesjs-blocks-table"] = t() : e["grapesjs-blocks-table"] = t()
}('undefined' != typeof globalThis ? globalThis : 'undefined' != typeof window ? window : this, (() => (() => {
    var e = {
        755 : function(e, t) {
            var n;
            !function(t, n) {
                "use strict";
                1 && "object" == typeof e.exports ? e.exports = t.document ? n(t, !0) : function(e) {
                    if (!e.document) throw new Error("jQuery requires a window with a document");
                    return n(e)
                } : n(t)
            }("undefined" != typeof window ? window : this, (function(r, o) {
                "use strict";
                var i = [], a = Object.getPrototypeOf, s = i.slice, l = i.flat ? function(e) {
                        return i.flat.call(e)
                    } : function(e) {
                        return i.concat.apply([], e)
                    }, u = i.push, c = i.indexOf, p = {}, f = p.toString, d = p.hasOwnProperty, h = d.toString,
                    m = h.call(Object), g = {}, v = function(e) {
                        return "function" == typeof e && "number" != typeof e.nodeType && "function" != typeof e.item
                    }, y = function(e) {
                        return null != e && e === e.window
                    }, b = r.document, x = {type : !0, src : !0, nonce : !0, noModule : !0};

                function w(e, t, n) {
                    var r, o, i = (n = n || b).createElement("script");
                    if (i.text = e, t) for (r in x) (o = t[r] || t.getAttribute && t.getAttribute(r)) && i.setAttribute(r, o);
                    n.head.appendChild(i).parentNode.removeChild(i)
                }

                function C(e) {
                    return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? p[f.call(e)] || "object" : typeof e
                }

                var T = "3.6.0", S = function(e, t) {
                    return new S.fn.init(e, t)
                };

                function E(e) {
                    var t = !!e && "length" in e && e.length, n = C(e);
                    return !v(e) && !y(e) && ("array" === n || 0 === t || "number" == typeof t && t > 0 && t - 1 in e)
                }

                S.fn = S.prototype = {
                    jquery       : T, constructor : S, length : 0, toArray : function() {
                        return s.call(this)
                    }, get       : function(e) {
                        return null == e ? s.call(this) : e < 0 ? this[e + this.length] : this[e]
                    }, pushStack : function(e) {
                        var t = S.merge(this.constructor(), e);
                        return t.prevObject = this, t
                    }, each      : function(e) {
                        return S.each(this, e)
                    }, map       : function(e) {
                        return this.pushStack(S.map(this, (function(t, n) {
                            return e.call(t, n, t)
                        })))
                    }, slice     : function() {
                        return this.pushStack(s.apply(this, arguments))
                    }, first     : function() {
                        return this.eq(0)
                    }, last      : function() {
                        return this.eq(-1)
                    }, even      : function() {
                        return this.pushStack(S.grep(this, (function(e, t) {
                            return (t + 1) % 2
                        })))
                    }, odd       : function() {
                        return this.pushStack(S.grep(this, (function(e, t) {
                            return t % 2
                        })))
                    }, eq        : function(e) {
                        var t = this.length, n = +e + (e < 0 ? t : 0);
                        return this.pushStack(n >= 0 && n < t ? [this[n]] : [])
                    }, end       : function() {
                        return this.prevObject || this.constructor()
                    }, push      : u, sort : i.sort, splice : i.splice
                }, S.extend = S.fn.extend = function() {
                    var e, t, n, r, o, i, a = arguments[0] || {}, s = 1, l = arguments.length, u = !1;
                    for ("boolean" == typeof a && (u = a, a = arguments[s] || {}, s++), "object" == typeof a || v(a) || (a = {}), s === l && (a = this, s--); s < l; s++) if (null != (e = arguments[s])) for (t in e) r = e[t], "__proto__" !== t && a !== r && (u && r && (S.isPlainObject(r) || (o = Array.isArray(r))) ? (n = a[t], i = o && !Array.isArray(n) ? [] : o || S.isPlainObject(n) ? n : {}, o = !1, a[t] = S.extend(u, i, r)) : void 0 !== r && (a[t] = r));
                    return a
                }, S.extend({
                    expando       : "jQuery" + (T + Math.random()).replace(/\D/g, ""),
                    isReady       : !0,
                    error         : function(e) {
                        throw new Error(e)
                    },
                    noop          : function() {
                    },
                    isPlainObject : function(e) {
                        var t, n;
                        return !(!e || "[object Object]" !== f.call(e)) && (!(t = a(e)) || "function" == typeof(n = d.call(t, "constructor") && t.constructor) && h.call(n) === m)
                    },
                    isEmptyObject : function(e) {
                        var t;
                        for (t in e) return !1;
                        return !0
                    },
                    globalEval    : function(e, t, n) {
                        w(e, {nonce : t && t.nonce}, n)
                    },
                    each          : function(e, t) {
                        var n, r = 0;
                        if (E(e)) for (n = e.length; r < n && !1 !== t.call(e[r], r, e[r]); r++) ; else for (r in e) if (!1 === t.call(e[r], r, e[r])) break;
                        return e
                    },
                    makeArray     : function(e, t) {
                        var n = t || [];
                        return null != e && (E(Object(e)) ? S.merge(n, "string" == typeof e ? [e] : e) : u.call(n, e)), n
                    },
                    inArray       : function(e, t, n) {
                        return null == t ? -1 : c.call(t, e, n)
                    },
                    merge         : function(e, t) {
                        for (var n = +t.length, r = 0, o = e.length; r < n; r++) e[o++] = t[r];
                        return e.length = o, e
                    },
                    grep          : function(e, t, n) {
                        for (var r = [], o = 0, i = e.length, a = !n; o < i; o++) !t(e[o], o) !== a && r.push(e[o]);
                        return r
                    },
                    map           : function(e, t, n) {
                        var r, o, i = 0, a = [];
                        if (E(e)) for (r = e.length; i < r; i++) null != (o = t(e[i], i, n)) && a.push(o); else for (i in e) null != (o = t(e[i], i, n)) && a.push(o);
                        return l(a)
                    },
                    guid          : 1,
                    support       : g
                }), "function" == typeof Symbol && (S.fn[Symbol.iterator] = i[Symbol.iterator]), S.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), (function(e, t) {
                    p["[object " + t + "]"] = t.toLowerCase()
                }));
                var k = function(e) {
                    var t, n, r, o, i, a, s, l, u, c, p, f, d, h, m, g, v, y, b, x = "sizzle" + 1 * new Date,
                        w = e.document, C = 0, T = 0, S = le(),
                        E = le(), k = le(), A = le(),
                        j = function(e, t) {
                            return e === t && (p = !0), 0
                        }, N = {}.hasOwnProperty, D = [],
                        H = D.pop, O = D.push, L = D.push,
                        R = D.slice, q = function(e, t) {
                            for (var n = 0, r = e.length; n < r; n++) if (e[n] === t) return n;
                            return -1
                        },
                        P = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|" + "ismap|loop|multiple|open|readonly|required|scoped",
                        M = "[\\x20\\t\\r\\n\\f]",
                        I = "(?:\\\\[\\da-fA-F]{1,6}" + M + "?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+",
                        $ = "\\[" + M + "*(" + I + ")(?:" + M + "*([*^$|!~]?=)" + M + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + I + "))|)" + M + "*\\]",
                        B = ":(" + I + ")(?:\\((" + "('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|" + "((?:\\\\.|[^\\\\()[\\]]|" + $ + ")*)|" + ".*" + ")\\)|)",
                        W = new RegExp(M + "+", "g"),
                        F = new RegExp("^" + M + "+|((?:^|[^\\\\])(?:\\\\.)*)" + M + "+$", "g"),
                        _ = new RegExp("^" + M + "*," + M + "*"),
                        z = new RegExp("^" + M + "*([>+~]|" + M + ")" + M + "*"),
                        U = new RegExp(M + "|>"),
                        X = new RegExp(B),
                        V = new RegExp("^" + I + "$"), G = {
                            ID : new RegExp("^#(" + I + ")"),
                            CLASS : new RegExp("^\\.(" + I + ")"),
                            TAG : new RegExp("^(" + I + "|[*])"),
                            ATTR : new RegExp("^" + $),
                            PSEUDO : new RegExp("^" + B),
                            CHILD : new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + M + "*(even|odd|(([+-]|)(\\d*)n|)" + M + "*(?:([+-]|)" + M + "*(\\d+)|))" + M + "*\\)|)", "i"),
                            bool : new RegExp("^(?:" + P + ")$", "i"),
                            needsContext : new RegExp("^" + M + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + M + "*((?:-\\d)?\\d*)" + M + "*\\)|)(?=[^-]|$)", "i")
                        }, Y = /HTML$/i,
                        Q = /^(?:input|select|textarea|button)$/i,
                        J = /^h\d$/i,
                        K = /^[^{]+\{\s*\[native \w/,
                        Z = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
                        ee = /[+~]/,
                        te = new RegExp("\\\\[\\da-fA-F]{1,6}" + M + "?|\\\\([^\\r\\n\\f])", "g"),
                        ne = function(e, t) {
                            var n = "0x" + e.slice(1) - 65536;
                            return t || (n < 0 ? String.fromCharCode(n + 65536) : String.fromCharCode(n >> 10 | 55296, 1023 & n | 56320))
                        },
                        re = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
                        oe = function(e, t) {
                            return t ? "\0" === e ? "�" : e.slice(0, -1) + "\\" + e.charCodeAt(e.length - 1).toString(16) + " " : "\\" + e
                        }, ie = function() {
                            f()
                        }, ae = xe((function(e) {
                            return !0 === e.disabled && "fieldset" === e.nodeName.toLowerCase()
                        }), {dir : "parentNode", next : "legend"});
                    try {
                        L.apply(D = R.call(w.childNodes), w.childNodes), D[w.childNodes.length].nodeType
                    } catch (e) {
                        L = {
                            apply : D.length ? function(e, t) {
                                O.apply(e, R.call(t))
                            } : function(e, t) {
                                for (var n = e.length, r = 0; e[n++] = t[r++];) ;
                                e.length = n - 1
                            }
                        }
                    }

                    function se(e, t, r, o) {
                        var i, s, u, c, p, h, v, y = t && t.ownerDocument, w = t ? t.nodeType : 9;
                        if (r = r || [], "string" != typeof e || !e || 1 !== w && 9 !== w && 11 !== w) return r;
                        if (!o && (f(t), t = t || d, m)) {
                            if (11 !== w && (p = Z.exec(e))) if (i = p[1]) {
                                if (9 === w) {
                                    if (!(u = t.getElementById(i))) return r;
                                    if (u.id === i) return r.push(u), r
                                } else if (y && (u = y.getElementById(i)) && b(t, u) && u.id === i) return r.push(u), r
                            } else {
                                if (p[2]) return L.apply(r, t.getElementsByTagName(e)), r;
                                if ((i = p[3]) && n.getElementsByClassName && t.getElementsByClassName) return L.apply(r, t.getElementsByClassName(i)), r
                            }
                            if (n.qsa && !A[e + " "] && (!g || !g.test(e)) && (1 !== w || "object" !== t.nodeName.toLowerCase())) {
                                if (v = e, y = t, 1 === w && (U.test(e) || z.test(e))) {
                                    for ((y = ee.test(e) && ve(t.parentNode) || t) === t && n.scope || ((c = t.getAttribute("id")) ? c = c.replace(re, oe) : t.setAttribute("id", c = x)), s = (h = a(e)).length; s--;) h[s] = (c ? "#" + c : ":scope") + " " + be(h[s]);
                                    v = h.join(",")
                                }
                                try {
                                    return L.apply(r, y.querySelectorAll(v)), r
                                } catch (t) {
                                    A(e, !0)
                                } finally {
                                    c === x && t.removeAttribute("id")
                                }
                            }
                        }
                        return l(e.replace(F, "$1"), t, r, o)
                    }

                    function le() {
                        var e = [];
                        return function t(n, o) {
                            return e.push(n + " ") > r.cacheLength && delete t[e.shift()], t[n + " "] = o
                        }
                    }

                    function ue(e) {
                        return e[x] = !0, e
                    }

                    function ce(e) {
                        var t = d.createElement("fieldset");
                        try {
                            return !!e(t)
                        } catch (e) {
                            return !1
                        } finally {
                            t.parentNode && t.parentNode.removeChild(t), t = null
                        }
                    }

                    function pe(e, t) {
                        for (var n = e.split("|"), o = n.length; o--;) r.attrHandle[n[o]] = t
                    }

                    function fe(e, t) {
                        var n = t && e, r = n && 1 === e.nodeType && 1 === t.nodeType && e.sourceIndex - t.sourceIndex;
                        if (r) return r;
                        if (n) for (; n = n.nextSibling;) if (n === t) return -1;
                        return e ? 1 : -1
                    }

                    function de(e) {
                        return function(t) {
                            return "input" === t.nodeName.toLowerCase() && t.type === e
                        }
                    }

                    function he(e) {
                        return function(t) {
                            var n = t.nodeName.toLowerCase();
                            return ("input" === n || "button" === n) && t.type === e
                        }
                    }

                    function me(e) {
                        return function(t) {
                            return "form" in t ? t.parentNode && !1 === t.disabled ? "label" in t ? "label" in t.parentNode ? t.parentNode.disabled === e : t.disabled === e : t.isDisabled === e || t.isDisabled !== !e && ae(t) === e : t.disabled === e : "label" in t && t.disabled === e
                        }
                    }

                    function ge(e) {
                        return ue((function(t) {
                            return t = +t, ue((function(n, r) {
                                for (var o, i = e([], n.length, t), a = i.length; a--;) n[o = i[a]] && (n[o] = !(r[o] = n[o]))
                            }))
                        }))
                    }

                    function ve(e) {
                        return e && void 0 !== e.getElementsByTagName && e
                    }

                    for (t in n = se.support = {}, i = se.isXML = function(e) {
                        var t = e && e.namespaceURI, n = e && (e.ownerDocument || e).documentElement;
                        return !Y.test(t || n && n.nodeName || "HTML")
                    }, f = se.setDocument = function(e) {
                        var t, o, a = e ? e.ownerDocument || e : w;
                        return a != d && 9 === a.nodeType && a.documentElement ? (h = (d = a).documentElement, m = !i(d), w != d && (o = d.defaultView) && o.top !== o && (o.addEventListener ? o.addEventListener("unload", ie, !1) : o.attachEvent && o.attachEvent("onunload", ie)), n.scope = ce((function(e) {
                            return h.appendChild(e).appendChild(d.createElement("div")), void 0 !== e.querySelectorAll && !e.querySelectorAll(":scope fieldset div").length
                        })), n.attributes = ce((function(e) {
                            return e.className = "i", !e.getAttribute("className")
                        })), n.getElementsByTagName = ce((function(e) {
                            return e.appendChild(d.createComment("")), !e.getElementsByTagName("*").length
                        })), n.getElementsByClassName = K.test(d.getElementsByClassName), n.getById = ce((function(e) {
                            return h.appendChild(e).id = x, !d.getElementsByName || !d.getElementsByName(x).length
                        })), n.getById ? (r.filter["ID"] = function(e) {
                            var t = e.replace(te, ne);
                            return function(e) {
                                return e.getAttribute("id") === t
                            }
                        }, r.find["ID"] = function(e, t) {
                            if (void 0 !== t.getElementById && m) {
                                var n = t.getElementById(e);
                                return n ? [n] : []
                            }
                        }) : (r.filter["ID"] = function(e) {
                            var t = e.replace(te, ne);
                            return function(e) {
                                var n = void 0 !== e.getAttributeNode && e.getAttributeNode("id");
                                return n && n.value === t
                            }
                        }, r.find["ID"] = function(e, t) {
                            if (void 0 !== t.getElementById && m) {
                                var n, r, o, i = t.getElementById(e);
                                if (i) {
                                    if ((n = i.getAttributeNode("id")) && n.value === e) return [i];
                                    for (o = t.getElementsByName(e), r = 0; i = o[r++];) if ((n = i.getAttributeNode("id")) && n.value === e) return [i]
                                }
                                return []
                            }
                        }), r.find["TAG"] = n.getElementsByTagName ? function(e, t) {
                            return void 0 !== t.getElementsByTagName ? t.getElementsByTagName(e) : n.qsa ? t.querySelectorAll(e) : void 0
                        } : function(e, t) {
                            var n, r = [], o = 0, i = t.getElementsByTagName(e);
                            if ("*" === e) {
                                for (; n = i[o++];) 1 === n.nodeType && r.push(n);
                                return r
                            }
                            return i
                        }, r.find["CLASS"] = n.getElementsByClassName && function(e, t) {
                            if (void 0 !== t.getElementsByClassName && m) return t.getElementsByClassName(e)
                        }, v = [], g = [], (n.qsa = K.test(d.querySelectorAll)) && (ce((function(e) {
                            var t;
                            h.appendChild(e).innerHTML = "<a id='" + x + "'></a>" + "<select id='" + x + "-\r\\' msallowcapture=''>" + "<option selected=''></option></select>", e.querySelectorAll("[msallowcapture^='']").length && g.push("[*^$]=" + M + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || g.push("\\[" + M + "*(?:value|" + P + ")"), e.querySelectorAll("[id~=" + x + "-]").length || g.push("~="), (t = d.createElement("input")).setAttribute("name", ""), e.appendChild(t), e.querySelectorAll("[name='']").length || g.push("\\[" + M + "*name" + M + "*=" + M + "*(?:''|\"\")"), e.querySelectorAll(":checked").length || g.push(":checked"), e.querySelectorAll("a#" + x + "+*").length || g.push(".#.+[+~]"), e.querySelectorAll("\\\f"), g.push("[\\r\\n\\f]")
                        })), ce((function(e) {
                            e.innerHTML = "<a href='' disabled='disabled'></a>" + "<select disabled='disabled'><option/></select>";
                            var t = d.createElement("input");
                            t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && g.push("name" + M + "*[*^$|!~]?="), 2 !== e.querySelectorAll(":enabled").length && g.push(":enabled", ":disabled"), h.appendChild(e).disabled = !0, 2 !== e.querySelectorAll(":disabled").length && g.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), g.push(",.*:")
                        }))), (n.matchesSelector = K.test(y = h.matches || h.webkitMatchesSelector || h.mozMatchesSelector || h.oMatchesSelector || h.msMatchesSelector)) && ce((function(e) {
                            n.disconnectedMatch = y.call(e, "*"), y.call(e, "[s!='']:x"), v.push("!=", B)
                        })), g = g.length && new RegExp(g.join("|")), v = v.length && new RegExp(v.join("|")), t = K.test(h.compareDocumentPosition), b = t || K.test(h.contains) ? function(e, t) {
                            var n = 9 === e.nodeType ? e.documentElement : e, r = t && t.parentNode;
                            return e === r || !(!r || 1 !== r.nodeType || !(n.contains ? n.contains(r) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(r)))
                        } : function(e, t) {
                            if (t) for (; t = t.parentNode;) if (t === e) return !0;
                            return !1
                        }, j = t ? function(e, t) {
                            if (e === t) return p = !0, 0;
                            var r = !e.compareDocumentPosition - !t.compareDocumentPosition;
                            return r || (1 & (r = (e.ownerDocument || e) == (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1) || !n.sortDetached && t.compareDocumentPosition(e) === r ? e == d || e.ownerDocument == w && b(w, e) ? -1 : t == d || t.ownerDocument == w && b(w, t) ? 1 : c ? q(c, e) - q(c, t) : 0 : 4 & r ? -1 : 1)
                        } : function(e, t) {
                            if (e === t) return p = !0, 0;
                            var n, r = 0, o = e.parentNode, i = t.parentNode, a = [e], s = [t];
                            if (!o || !i) return e == d ? -1 : t == d ? 1 : o ? -1 : i ? 1 : c ? q(c, e) - q(c, t) : 0;
                            if (o === i) return fe(e, t);
                            for (n = e; n = n.parentNode;) a.unshift(n);
                            for (n = t; n = n.parentNode;) s.unshift(n);
                            for (; a[r] === s[r];) r++;
                            return r ? fe(a[r], s[r]) : a[r] == w ? -1 : s[r] == w ? 1 : 0
                        }, d) : d
                    }, se.matches = function(e, t) {
                        return se(e, null, null, t)
                    }, se.matchesSelector = function(e, t) {
                        if (f(e), n.matchesSelector && m && !A[t + " "] && (!v || !v.test(t)) && (!g || !g.test(t))) try {
                            var r = y.call(e, t);
                            if (r || n.disconnectedMatch || e.document && 11 !== e.document.nodeType) return r
                        } catch (e) {
                            A(t, !0)
                        }
                        return se(t, d, null, [e]).length > 0
                    }, se.contains = function(e, t) {
                        return (e.ownerDocument || e) != d && f(e), b(e, t)
                    }, se.attr = function(e, t) {
                        (e.ownerDocument || e) != d && f(e);
                        var o = r.attrHandle[t.toLowerCase()],
                            i = o && N.call(r.attrHandle, t.toLowerCase()) ? o(e, t, !m) : void 0;
                        return void 0 !== i ? i : n.attributes || !m ? e.getAttribute(t) : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
                    }, se.escape = function(e) {
                        return (e + "").replace(re, oe)
                    }, se.error = function(e) {
                        throw new Error("Syntax error, unrecognized expression: " + e)
                    }, se.uniqueSort = function(e) {
                        var t, r = [], o = 0, i = 0;
                        if (p = !n.detectDuplicates, c = !n.sortStable && e.slice(0), e.sort(j), p) {
                            for (; t = e[i++];) t === e[i] && (o = r.push(i));
                            for (; o--;) e.splice(r[o], 1)
                        }
                        return c = null, e
                    }, o = se.getText = function(e) {
                        var t, n = "", r = 0, i = e.nodeType;
                        if (i) {
                            if (1 === i || 9 === i || 11 === i) {
                                if ("string" == typeof e.textContent) return e.textContent;
                                for (e = e.firstChild; e; e = e.nextSibling) n += o(e)
                            } else if (3 === i || 4 === i) return e.nodeValue
                        } else for (; t = e[r++];) n += o(t);
                        return n
                    }, r = se.selectors = {
                        cacheLength  : 50,
                        createPseudo : ue,
                        match        : G,
                        attrHandle   : {},
                        find         : {},
                        relative     : {
                            ">" : {dir : "parentNode", first : !0},
                            " " : {dir : "parentNode"},
                            "+" : {dir : "previousSibling", first : !0},
                            "~" : {dir : "previousSibling"}
                        },
                        preFilter    : {
                            ATTR      : function(e) {
                                return e[1] = e[1].replace(te, ne), e[3] = (e[3] || e[4] || e[5] || "").replace(te, ne), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                            }, CHILD  : function(e) {
                                return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || se.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && se.error(e[0]), e
                            }, PSEUDO : function(e) {
                                var t, n = !e[6] && e[2];
                                return G["CHILD"].test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : n && X.test(n) && (t = a(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                            }
                        },
                        filter       : {
                            TAG       : function(e) {
                                var t = e.replace(te, ne).toLowerCase();
                                return "*" === e ? function() {
                                    return !0
                                } : function(e) {
                                    return e.nodeName && e.nodeName.toLowerCase() === t
                                }
                            }, CLASS  : function(e) {
                                var t = S[e + " "];
                                return t || (t = new RegExp("(^|" + M + ")" + e + "(" + M + "|$)")) && S(e, (function(e) {
                                    return t.test("string" == typeof e.className && e.className || void 0 !== e.getAttribute && e.getAttribute("class") || "")
                                }))
                            }, ATTR   : function(e, t, n) {
                                return function(r) {
                                    var o = se.attr(r, e);
                                    return null == o ? "!=" === t : !t || (o += "", "=" === t ? o === n : "!=" === t ? o !== n : "^=" === t ? n && 0 === o.indexOf(n) : "*=" === t ? n && o.indexOf(n) > -1 : "$=" === t ? n && o.slice(-n.length) === n : "~=" === t ? (" " + o.replace(W, " ") + " ").indexOf(n) > -1 : "|=" === t && (o === n || o.slice(0, n.length + 1) === n + "-"))
                                }
                            }, CHILD  : function(e, t, n, r, o) {
                                var i = "nth" !== e.slice(0, 3), a = "last" !== e.slice(-4), s = "of-type" === t;
                                return 1 === r && 0 === o ? function(e) {
                                    return !!e.parentNode
                                } : function(t, n, l) {
                                    var u, c, p, f, d, h, m = i !== a ? "nextSibling" : "previousSibling",
                                        g = t.parentNode, v = s && t.nodeName.toLowerCase(),
                                        y = !l && !s, b = !1;
                                    if (g) {
                                        if (i) {
                                            for (; m;) {
                                                for (f = t; f = f[m];) if (s ? f.nodeName.toLowerCase() === v : 1 === f.nodeType) return !1;
                                                h = m = "only" === e && !h && "nextSibling"
                                            }
                                            return !0
                                        }
                                        if (h = [a ? g.firstChild : g.lastChild], a && y) {
                                            for (b = (d = (u = (c = (p = (f = g)[x] || (f[x] = {}))[f.uniqueID] || (p[f.uniqueID] = {}))[e] || [])[0] === C && u[1]) && u[2], f = d && g.childNodes[d]; f = ++d && f && f[m] || (b = d = 0) || h.pop();) if (1 === f.nodeType && ++b && f === t) {
                                                c[e] = [C, d, b];
                                                break
                                            }
                                        } else if (y && (b = d = (u = (c = (p = (f = t)[x] || (f[x] = {}))[f.uniqueID] || (p[f.uniqueID] = {}))[e] || [])[0] === C && u[1]), !1 === b) for (; (f = ++d && f && f[m] || (b = d = 0) || h.pop()) && ((s ? f.nodeName.toLowerCase() !== v : 1 !== f.nodeType) || !++b || (y && ((c = (p = f[x] || (f[x] = {}))[f.uniqueID] || (p[f.uniqueID] = {}))[e] = [C, b]), f !== t));) ;
                                        return (b -= o) === r || b % r == 0 && b / r >= 0
                                    }
                                }
                            }, PSEUDO : function(e, t) {
                                var n,
                                    o = r.pseudos[e] || r.setFilters[e.toLowerCase()] || se.error("unsupported pseudo: " + e);
                                return o[x] ? o(t) : o.length > 1 ? (n = [e, e, "", t], r.setFilters.hasOwnProperty(e.toLowerCase()) ? ue((function(e, n) {
                                    for (var r, i = o(e, t), a = i.length; a--;) e[r = q(e, i[a])] = !(n[r] = i[a])
                                })) : function(e) {
                                    return o(e, 0, n)
                                }) : o
                            }
                        },
                        pseudos      : {
                            not           : ue((function(e) {
                                var t = [], n = [], r = s(e.replace(F, "$1"));
                                return r[x] ? ue((function(e, t, n, o) {
                                    for (var i, a = r(e, null, o, []), s = e.length; s--;) (i = a[s]) && (e[s] = !(t[s] = i))
                                })) : function(e, o, i) {
                                    return t[0] = e, r(t, null, i, n), t[0] = null, !n.pop()
                                }
                            })), has      : ue((function(e) {
                                return function(t) {
                                    return se(e, t).length > 0
                                }
                            })), contains : ue((function(e) {
                                return e = e.replace(te, ne), function(t) {
                                    return (t.textContent || o(t)).indexOf(e) > -1
                                }
                            })), lang     : ue((function(e) {
                                return V.test(e || "") || se.error("unsupported lang: " + e), e = e.replace(te, ne).toLowerCase(), function(t) {
                                    var n;
                                    do {
                                        if (n = m ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang")) return (n = n.toLowerCase()) === e || 0 === n.indexOf(e + "-")
                                    } while ((t = t.parentNode) && 1 === t.nodeType);
                                    return !1
                                }
                            })), target   : function(t) {
                                var n = e.location && e.location.hash;
                                return n && n.slice(1) === t.id
                            }, root       : function(e) {
                                return e === h
                            }, focus      : function(e) {
                                return e === d.activeElement && (!d.hasFocus || d.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                            }, enabled    : me(!1), disabled : me(!0), checked : function(e) {
                                var t = e.nodeName.toLowerCase();
                                return "input" === t && !!e.checked || "option" === t && !!e.selected
                            }, selected   : function(e) {
                                return e.parentNode && e.parentNode.selectedIndex, !0 === e.selected
                            }, empty      : function(e) {
                                for (e = e.firstChild; e; e = e.nextSibling) if (e.nodeType < 6) return !1;
                                return !0
                            }, parent     : function(e) {
                                return !r.pseudos["empty"](e)
                            }, header     : function(e) {
                                return J.test(e.nodeName)
                            }, input      : function(e) {
                                return Q.test(e.nodeName)
                            }, button     : function(e) {
                                var t = e.nodeName.toLowerCase();
                                return "input" === t && "button" === e.type || "button" === t
                            }, text       : function(e) {
                                var t;
                                return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                            }, first      : ge((function() {
                                return [0]
                            })), last     : ge((function(e, t) {
                                return [t - 1]
                            })), eq       : ge((function(e, t, n) {
                                return [n < 0 ? n + t : n]
                            })), even     : ge((function(e, t) {
                                for (var n = 0; n < t; n += 2) e.push(n);
                                return e
                            })), odd      : ge((function(e, t) {
                                for (var n = 1; n < t; n += 2) e.push(n);
                                return e
                            })), lt       : ge((function(e, t, n) {
                                for (var r = n < 0 ? n + t : n > t ? t : n; --r >= 0;) e.push(r);
                                return e
                            })), gt       : ge((function(e, t, n) {
                                for (var r = n < 0 ? n + t : n; ++r < t;) e.push(r);
                                return e
                            }))
                        }
                    }, r.pseudos["nth"] = r.pseudos["eq"], {
                        radio    : !0,
                        checkbox : !0,
                        file     : !0,
                        password : !0,
                        image    : !0
                    }) r.pseudos[t] = de(t);
                    for (t in{submit : !0, reset : !0}) r.pseudos[t] = he(t);

                    function ye() {
                    }

                    function be(e) {
                        for (var t = 0, n = e.length, r = ""; t < n; t++) r += e[t].value;
                        return r
                    }

                    function xe(e, t, n) {
                        var r = t.dir, o = t.next, i = o || r, a = n && "parentNode" === i, s = T++;
                        return t.first ? function(t, n, o) {
                            for (; t = t[r];) if (1 === t.nodeType || a) return e(t, n, o);
                            return !1
                        } : function(t, n, l) {
                            var u, c, p, f = [C, s];
                            if (l) {
                                for (; t = t[r];) if ((1 === t.nodeType || a) && e(t, n, l)) return !0
                            } else for (; t = t[r];) if (1 === t.nodeType || a) if (c = (p = t[x] || (t[x] = {}))[t.uniqueID] || (p[t.uniqueID] = {}), o && o === t.nodeName.toLowerCase()) t = t[r] || t; else {
                                if ((u = c[i]) && u[0] === C && u[1] === s) return f[2] = u[2];
                                if (c[i] = f, f[2] = e(t, n, l)) return !0
                            }
                            return !1
                        }
                    }

                    function we(e) {
                        return e.length > 1 ? function(t, n, r) {
                            for (var o = e.length; o--;) if (!e[o](t, n, r)) return !1;
                            return !0
                        } : e[0]
                    }

                    function Ce(e, t, n, r, o) {
                        for (var i, a = [], s = 0, l = e.length, u = null != t; s < l; s++) (i = e[s]) && (n && !n(i, r, o) || (a.push(i), u && t.push(s)));
                        return a
                    }

                    function Te(e, t, n, r, o, i) {
                        return r && !r[x] && (r = Te(r)), o && !o[x] && (o = Te(o, i)), ue((function(i, a, s, l) {
                            var u, c, p, f = [], d = [], h = a.length, m = i || function(e, t, n) {
                                    for (var r = 0, o = t.length; r < o; r++) se(e, t[r], n);
                                    return n
                                }(t || "*", s.nodeType ? [s] : s, []), g = !e || !i && t ? m : Ce(m, f, e, s, l),
                                v = n ? o || (i ? e : h || r) ? [] : a : g;
                            if (n && n(g, v, s, l), r) for (u = Ce(v, d), r(u, [], s, l), c = u.length; c--;) (p = u[c]) && (v[d[c]] = !(g[d[c]] = p));
                            if (i) {
                                if (o || e) {
                                    if (o) {
                                        for (u = [], c = v.length; c--;) (p = v[c]) && u.push(g[c] = p);
                                        o(null, v = [], u, l)
                                    }
                                    for (c = v.length; c--;) (p = v[c]) && (u = o ? q(i, p) : f[c]) > -1 && (i[u] = !(a[u] = p))
                                }
                            } else v = Ce(v === a ? v.splice(h, v.length) : v), o ? o(null, a, v, l) : L.apply(a, v)
                        }))
                    }

                    function Se(e) {
                        for (var t, n, o, i = e.length, a = r.relative[e[0].type], s = a || r.relative[" "], l = a ? 1 : 0, c = xe((function(e) {
                            return e === t
                        }), s, !0), p       = xe((function(e) {
                            return q(t, e) > -1
                        }), s, !0), f       = [function(e, n, r) {
                            var o = !a && (r || n !== u) || ((t = n).nodeType ? c(e, n, r) : p(e, n, r));
                            return t = null, o
                        }]; l < i; l++) if (n = r.relative[e[l].type]) f = [xe(we(f), n)]; else {
                            if ((n = r.filter[e[l].type].apply(null, e[l].matches))[x]) {
                                for (o = ++l; o < i && !r.relative[e[o].type]; o++) ;
                                return Te(l > 1 && we(f), l > 1 && be(e.slice(0, l - 1).concat({value : " " === e[l - 2].type ? "*" : ""})).replace(F, "$1"), n, l < o && Se(e.slice(l, o)), o < i && Se(e = e.slice(o)), o < i && be(e))
                            }
                            f.push(n)
                        }
                        return we(f)
                    }

                    return ye.prototype = r.filters = r.pseudos, r.setFilters = new ye, a = se.tokenize = function(e, t) {
                        var n, o, i, a, s, l, u, c = E[e + " "];
                        if (c) return t ? 0 : c.slice(0);
                        for (s = e, l = [], u = r.preFilter; s;) {
                            for (a in n && !(o = _.exec(s)) || (o && (s = s.slice(o[0].length) || s), l.push(i = [])), n = !1, (o = z.exec(s)) && (n = o.shift(), i.push({
                                value : n,
                                type  : o[0].replace(F, " ")
                            }), s = s.slice(n.length)), r.filter) !(o = G[a].exec(s)) || u[a] && !(o = u[a](o)) || (n = o.shift(), i.push({
                                value   : n,
                                type    : a,
                                matches : o
                            }), s = s.slice(n.length));
                            if (!n) break
                        }
                        return t ? s.length : s ? se.error(e) : E(e, l).slice(0)
                    }, s = se.compile = function(e, t) {
                        var n, o = [], i = [], s = k[e + " "];
                        if (!s) {
                            for (t || (t = a(e)), n = t.length; n--;) (s = Se(t[n]))[x] ? o.push(s) : i.push(s);
                            s = k(e, function(e, t) {
                                var n = t.length > 0, o = e.length > 0, i = function(i, a, s, l, c) {
                                    var p, h, g, v = 0, y = "0", b = i && [], x = [], w = u,
                                        T = i || o && r.find["TAG"]("*", c),
                                        S = C += null == w ? 1 : Math.random() || .1, E = T.length;
                                    for (c && (u = a == d || a || c); y !== E && null != (p = T[y]); y++) {
                                        if (o && p) {
                                            for (h = 0, a || p.ownerDocument == d || (f(p), s = !m); g = e[h++];) if (g(p, a || d, s)) {
                                                l.push(p);
                                                break
                                            }
                                            c && (C = S)
                                        }
                                        n && ((p = !g && p) && v--, i && b.push(p))
                                    }
                                    if (v += y, n && y !== v) {
                                        for (h = 0; g = t[h++];) g(b, x, a, s);
                                        if (i) {
                                            if (v > 0) for (; y--;) b[y] || x[y] || (x[y] = H.call(l));
                                            x = Ce(x)
                                        }
                                        L.apply(l, x), c && !i && x.length > 0 && v + t.length > 1 && se.uniqueSort(l)
                                    }
                                    return c && (C = S, u = w), b
                                };
                                return n ? ue(i) : i
                            }(i, o)), s.selector = e
                        }
                        return s
                    }, l = se.select = function(e, t, n, o) {
                        var i, l, u, c, p, f = "function" == typeof e && e, d = !o && a(e = f.selector || e);
                        if (n = n || [], 1 === d.length) {
                            if ((l = d[0] = d[0].slice(0)).length > 2 && "ID" === (u = l[0]).type && 9 === t.nodeType && m && r.relative[l[1].type]) {
                                if (!(t = (r.find["ID"](u.matches[0].replace(te, ne), t) || [])[0])) return n;
                                f && (t = t.parentNode), e = e.slice(l.shift().value.length)
                            }
                            for (i = G["needsContext"].test(e) ? 0 : l.length; i-- && (u = l[i], !r.relative[c = u.type]);) if ((p = r.find[c]) && (o = p(u.matches[0].replace(te, ne), ee.test(l[0].type) && ve(t.parentNode) || t))) {
                                if (l.splice(i, 1), !(e = o.length && be(l))) return L.apply(n, o), n;
                                break
                            }
                        }
                        return (f || s(e, d))(o, t, !m, n, !t || ee.test(e) && ve(t.parentNode) || t), n
                    }, n.sortStable = x.split("").sort(j).join("") === x, n.detectDuplicates = !!p, f(), n.sortDetached = ce((function(e) {
                        return 1 & e.compareDocumentPosition(d.createElement("fieldset"))
                    })), ce((function(e) {
                        return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
                    })) || pe("type|href|height|width", (function(e, t, n) {
                        if (!n) return e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
                    })), n.attributes && ce((function(e) {
                        return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
                    })) || pe("value", (function(e, t, n) {
                        if (!n && "input" === e.nodeName.toLowerCase()) return e.defaultValue
                    })), ce((function(e) {
                        return null == e.getAttribute("disabled")
                    })) || pe(P, (function(e, t, n) {
                        var r;
                        if (!n) return !0 === e[t] ? t.toLowerCase() : (r = e.getAttributeNode(t)) && r.specified ? r.value : null
                    })), se
                }(r);
                S.find = k, S.expr = k.selectors, S.expr[":"] = S.expr.pseudos, S.uniqueSort = S.unique = k.uniqueSort, S.text = k.getText, S.isXMLDoc = k.isXML, S.contains = k.contains, S.escapeSelector = k.escape;
                var A = function(e, t, n) {
                    for (var r = [], o = void 0 !== n; (e = e[t]) && 9 !== e.nodeType;) if (1 === e.nodeType) {
                        if (o && S(e).is(n)) break;
                        r.push(e)
                    }
                    return r
                }, j  = function(e, t) {
                    for (var n = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && n.push(e);
                    return n
                }, N  = S.expr.match.needsContext;

                function D(e, t) {
                    return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
                }

                var H = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;

                function O(e, t, n) {
                    return v(t) ? S.grep(e, (function(e, r) {
                        return !!t.call(e, r, e) !== n
                    })) : t.nodeType ? S.grep(e, (function(e) {
                        return e === t !== n
                    })) : "string" != typeof t ? S.grep(e, (function(e) {
                        return c.call(t, e) > -1 !== n
                    })) : S.filter(t, e, n)
                }

                S.filter = function(e, t, n) {
                    var r = t[0];
                    return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === r.nodeType ? S.find.matchesSelector(r, e) ? [r] : [] : S.find.matches(e, S.grep(t, (function(e) {
                        return 1 === e.nodeType
                    })))
                }, S.fn.extend({
                    find      : function(e) {
                        var t, n, r = this.length, o = this;
                        if ("string" != typeof e) return this.pushStack(S(e).filter((function() {
                            for (t = 0; t < r; t++) if (S.contains(o[t], this)) return !0
                        })));
                        for (n = this.pushStack([]), t = 0; t < r; t++) S.find(e, o[t], n);
                        return r > 1 ? S.uniqueSort(n) : n
                    }, filter : function(e) {
                        return this.pushStack(O(this, e || [], !1))
                    }, not    : function(e) {
                        return this.pushStack(O(this, e || [], !0))
                    }, is     : function(e) {
                        return !!O(this, "string" == typeof e && N.test(e) ? S(e) : e || [], !1).length
                    }
                });
                var L, R = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;
                (S.fn.init = function(e, t, n) {
                    var r, o;
                    if (!e) return this;
                    if (n = n || L, "string" == typeof e) {
                        if (!(r = "<" === e[0] && ">" === e[e.length - 1] && e.length >= 3 ? [null, e, null] : R.exec(e)) || !r[1] && t) return !t || t.jquery ? (t || n).find(e) : this.constructor(t).find(e);
                        if (r[1]) {
                            if (t = t instanceof S ? t[0] : t, S.merge(this, S.parseHTML(r[1], t && t.nodeType ? t.ownerDocument || t : b, !0)), H.test(r[1]) && S.isPlainObject(t)) for (r in t) v(this[r]) ? this[r](t[r]) : this.attr(r, t[r]);
                            return this
                        }
                        return (o = b.getElementById(r[2])) && (this[0] = o, this.length = 1), this
                    }
                    return e.nodeType ? (this[0] = e, this.length = 1, this) : v(e) ? void 0 !== n.ready ? n.ready(e) : e(S) : S.makeArray(e, this)
                }).prototype = S.fn, L = S(b);
                var q = /^(?:parents|prev(?:Until|All))/, P = {children : !0, contents : !0, next : !0, prev : !0};

                function M(e, t) {
                    for (; (e = e[t]) && 1 !== e.nodeType;) ;
                    return e
                }

                S.fn.extend({
                    has        : function(e) {
                        var t = S(e, this), n = t.length;
                        return this.filter((function() {
                            for (var e = 0; e < n; e++) if (S.contains(this, t[e])) return !0
                        }))
                    }, closest : function(e, t) {
                        var n, r = 0, o = this.length, i = [], a = "string" != typeof e && S(e);
                        if (!N.test(e)) for (; r < o; r++) for (n = this[r]; n && n !== t; n = n.parentNode) if (n.nodeType < 11 && (a ? a.index(n) > -1 : 1 === n.nodeType && S.find.matchesSelector(n, e))) {
                            i.push(n);
                            break
                        }
                        return this.pushStack(i.length > 1 ? S.uniqueSort(i) : i)
                    }, index   : function(e) {
                        return e ? "string" == typeof e ? c.call(S(e), this[0]) : c.call(this, e.jquery ? e[0] : e) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
                    }, add     : function(e, t) {
                        return this.pushStack(S.uniqueSort(S.merge(this.get(), S(e, t))))
                    }, addBack : function(e) {
                        return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
                    }
                }), S.each({
                    parent          : function(e) {
                        var t = e.parentNode;
                        return t && 11 !== t.nodeType ? t : null
                    }, parents      : function(e) {
                        return A(e, "parentNode")
                    }, parentsUntil : function(e, t, n) {
                        return A(e, "parentNode", n)
                    }, next         : function(e) {
                        return M(e, "nextSibling")
                    }, prev         : function(e) {
                        return M(e, "previousSibling")
                    }, nextAll      : function(e) {
                        return A(e, "nextSibling")
                    }, prevAll      : function(e) {
                        return A(e, "previousSibling")
                    }, nextUntil    : function(e, t, n) {
                        return A(e, "nextSibling", n)
                    }, prevUntil    : function(e, t, n) {
                        return A(e, "previousSibling", n)
                    }, siblings     : function(e) {
                        return j((e.parentNode || {}).firstChild, e)
                    }, children     : function(e) {
                        return j(e.firstChild)
                    }, contents     : function(e) {
                        return null != e.contentDocument && a(e.contentDocument) ? e.contentDocument : (D(e, "template") && (e = e.content || e), S.merge([], e.childNodes))
                    }
                }, (function(e, t) {
                    S.fn[e] = function(n, r) {
                        var o = S.map(this, t, n);
                        return "Until" !== e.slice(-5) && (r = n), r && "string" == typeof r && (o = S.filter(r, o)), this.length > 1 && (P[e] || S.uniqueSort(o), q.test(e) && o.reverse()), this.pushStack(o)
                    }
                }));
                var I = /[^\x20\t\r\n\f]+/g;

                function $(e) {
                    return e
                }

                function B(e) {
                    throw e
                }

                function W(e, t, n, r) {
                    var o;
                    try {
                        e && v(o = e.promise) ? o.call(e).done(t).fail(n) : e && v(o = e.then) ? o.call(e, t, n) : t.apply(void 0, [e].slice(r))
                    } catch (e) {
                        n.apply(void 0, [e])
                    }
                }

                S.Callbacks = function(e) {
                    e = "string" == typeof e ? function(e) {
                        var t = {};
                        return S.each(e.match(I) || [], (function(e, n) {
                            t[n] = !0
                        })), t
                    }(e) : S.extend({}, e);
                    var t, n, r, o, i = [], a = [], s = -1, l = function() {
                        for (o = o || e.once, r = t = !0; a.length; s = -1) for (n = a.shift(); ++s < i.length;) !1 === i[s].apply(n[0], n[1]) && e.stopOnFalse && (s = i.length, n = !1);
                        e.memory || (n = !1), t = !1, o && (i = n ? [] : "")
                    }, u              = {
                        add         : function() {
                            return i && (n && !t && (s = i.length - 1, a.push(n)), function t(n) {
                                S.each(n, (function(n, r) {
                                    v(r) ? e.unique && u.has(r) || i.push(r) : r && r.length && "string" !== C(r) && t(r)
                                }))
                            }(arguments), n && !t && l()), this
                        }, remove   : function() {
                            return S.each(arguments, (function(e, t) {
                                for (var n; (n = S.inArray(t, i, n)) > -1;) i.splice(n, 1), n <= s && s--
                            })), this
                        }, has      : function(e) {
                            return e ? S.inArray(e, i) > -1 : i.length > 0
                        }, empty    : function() {
                            return i && (i = []), this
                        }, disable  : function() {
                            return o = a = [], i = n = "", this
                        }, disabled : function() {
                            return !i
                        }, lock     : function() {
                            return o = a = [], n || t || (i = n = ""), this
                        }, locked   : function() {
                            return !!o
                        }, fireWith : function(e, n) {
                            return o || (n = [e, (n = n || []).slice ? n.slice() : n], a.push(n), t || l()), this
                        }, fire     : function() {
                            return u.fireWith(this, arguments), this
                        }, fired    : function() {
                            return !!r
                        }
                    };
                    return u
                }, S.extend({
                    Deferred : function(e) {
                        var t = [["notify", "progress", S.Callbacks("memory"), S.Callbacks("memory"), 2], ["resolve", "done", S.Callbacks("once memory"), S.Callbacks("once memory"), 0, "resolved"], ["reject", "fail", S.Callbacks("once memory"), S.Callbacks("once memory"), 1, "rejected"]],
                            n = "pending", o = {
                                state : function() {
                                    return n
                                }, always : function() {
                                    return i.done(arguments).fail(arguments), this
                                }, catch : function(e) {
                                    return o.then(null, e)
                                }, pipe : function() {
                                    var e = arguments;
                                    return S.Deferred((function(n) {
                                        S.each(t, (function(t, r) {
                                            var o = v(e[r[4]]) && e[r[4]];
                                            i[r[1]]((function() {
                                                var e = o && o.apply(this, arguments);
                                                e && v(e.promise) ? e.promise().progress(n.notify).done(n.resolve).fail(n.reject) : n[r[0] + "With"](this, o ? [e] : arguments)
                                            }))
                                        })), e = null
                                    })).promise()
                                }, then : function(e, n, o) {
                                    var i = 0;

                                    function a(e, t, n, o) {
                                        return function() {
                                            var s = this, l = arguments, u = function() {
                                                var r, u;
                                                if (!(e < i)) {
                                                    if ((r = n.apply(s, l)) === t.promise()) throw new TypeError("Thenable self-resolution");
                                                    u = r && ("object" == typeof r || "function" == typeof r) && r.then, v(u) ? o ? u.call(r, a(i, t, $, o), a(i, t, B, o)) : (i++, u.call(r, a(i, t, $, o), a(i, t, B, o), a(i, t, $, t.notifyWith))) : (n !== $ && (s = void 0, l = [r]), (o || t.resolveWith)(s, l))
                                                }
                                            }, c = o ? u : function() {
                                                try {
                                                    u()
                                                } catch (r) {
                                                    S.Deferred.exceptionHook && S.Deferred.exceptionHook(r, c.stackTrace), e + 1 >= i && (n !== B && (s = void 0, l = [r]), t.rejectWith(s, l))
                                                }
                                            };
                                            e ? c() : (S.Deferred.getStackHook && (c.stackTrace = S.Deferred.getStackHook()), r.setTimeout(c))
                                        }
                                    }

                                    return S.Deferred((function(r) {
                                        t[0][3].add(a(0, r, v(o) ? o : $, r.notifyWith)), t[1][3].add(a(0, r, v(e) ? e : $)), t[2][3].add(a(0, r, v(n) ? n : B))
                                    })).promise()
                                }, promise : function(e) {
                                    return null != e ? S.extend(e, o) : o
                                }
                            }, i = {};
                        return S.each(t, (function(e, r) {
                            var a = r[2], s = r[5];
                            o[r[1]] = a.add, s && a.add((function() {
                                n = s
                            }), t[3 - e][2].disable, t[3 - e][3].disable, t[0][2].lock, t[0][3].lock), a.add(r[3].fire), i[r[0]] = function() {
                                return i[r[0] + "With"](this === i ? void 0 : this, arguments), this
                            }, i[r[0] + "With"] = a.fireWith
                        })), o.promise(i), e && e.call(i, i), i
                    }, when  : function(e) {
                        var t = arguments.length, n = t, r = Array(n), o = s.call(arguments), i = S.Deferred(),
                            a = function(e) {
                                return function(n) {
                                    r[e] = this, o[e] = arguments.length > 1 ? s.call(arguments) : n, --t || i.resolveWith(r, o)
                                }
                            };
                        if (t <= 1 && (W(e, i.done(a(n)).resolve, i.reject, !t), "pending" === i.state() || v(o[n] && o[n].then))) return i.then();
                        for (; n--;) W(o[n], a(n), i.reject);
                        return i.promise()
                    }
                });
                var F = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
                S.Deferred.exceptionHook = function(e, t) {
                    r.console && r.console.warn && e && F.test(e.name) && r.console.warn("jQuery.Deferred exception: " + e.message, e.stack, t)
                }, S.readyException = function(e) {
                    r.setTimeout((function() {
                        throw e
                    }))
                };
                var _ = S.Deferred();

                function z() {
                    b.removeEventListener("DOMContentLoaded", z), r.removeEventListener("load", z), S.ready()
                }

                S.fn.ready = function(e) {
                    return _.then(e).catch((function(e) {
                        S.readyException(e)
                    })), this
                }, S.extend({
                    isReady : !1, readyWait : 1, ready : function(e) {
                        (!0 === e ? --S.readyWait : S.isReady) || (S.isReady = !0, !0 !== e && --S.readyWait > 0 || _.resolveWith(b, [S]))
                    }
                }), S.ready.then = _.then, "complete" === b.readyState || "loading" !== b.readyState && !b.documentElement.doScroll ? r.setTimeout(S.ready) : (b.addEventListener("DOMContentLoaded", z), r.addEventListener("load", z));
                var U = function(e, t, n, r, o, i, a) {
                    var s = 0, l = e.length, u = null == n;
                    if ("object" === C(n)) for (s in o = !0, n) U(e, t, s, n[s], !0, i, a); else if (void 0 !== r && (o = !0, v(r) || (a = !0), u && (a ? (t.call(e, r), t = null) : (u = t, t = function(e, t, n) {
                        return u.call(S(e), n)
                    })), t)) for (; s < l; s++) t(e[s], n, a ? r : r.call(e[s], s, t(e[s], n)));
                    return o ? e : u ? t.call(e) : l ? t(e[0], n) : i
                }, X  = /^-ms-/, V = /-([a-z])/g;

                function G(e, t) {
                    return t.toUpperCase()
                }

                function Y(e) {
                    return e.replace(X, "ms-").replace(V, G)
                }

                var Q = function(e) {
                    return 1 === e.nodeType || 9 === e.nodeType || !+e.nodeType
                };

                function J() {
                    this.expando = S.expando + J.uid++
                }

                J.uid = 1, J.prototype = {
                    cache      : function(e) {
                        var t = e[this.expando];
                        return t || (t = {}, Q(e) && (e.nodeType ? e[this.expando] = t : Object.defineProperty(e, this.expando, {
                            value        : t,
                            configurable : !0
                        }))), t
                    }, set     : function(e, t, n) {
                        var r, o = this.cache(e);
                        if ("string" == typeof t) o[Y(t)] = n; else for (r in t) o[Y(r)] = t[r];
                        return o
                    }, get     : function(e, t) {
                        return void 0 === t ? this.cache(e) : e[this.expando] && e[this.expando][Y(t)]
                    }, access  : function(e, t, n) {
                        return void 0 === t || t && "string" == typeof t && void 0 === n ? this.get(e, t) : (this.set(e, t, n), void 0 !== n ? n : t)
                    }, remove  : function(e, t) {
                        var n, r = e[this.expando];
                        if (void 0 !== r) {
                            if (void 0 !== t) {
                                n = (t = Array.isArray(t) ? t.map(Y) : (t = Y(t)) in r ? [t] : t.match(I) || []).length;
                                for (; n--;) delete r[t[n]]
                            }
                            (void 0 === t || S.isEmptyObject(r)) && (e.nodeType ? e[this.expando] = void 0 : delete e[this.expando])
                        }
                    }, hasData : function(e) {
                        var t = e[this.expando];
                        return void 0 !== t && !S.isEmptyObject(t)
                    }
                };
                var K = new J, Z = new J, ee = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, te = /[A-Z]/g;

                function ne(e, t, n) {
                    var r;
                    if (void 0 === n && 1 === e.nodeType) if (r = "data-" + t.replace(te, "-$&").toLowerCase(), "string" == typeof(n = e.getAttribute(r))) {
                        try {
                            n = function(e) {
                                return "true" === e || "false" !== e && ("null" === e ? null : e === +e + "" ? +e : ee.test(e) ? JSON.parse(e) : e)
                            }(n)
                        } catch (e) {
                        }
                        Z.set(e, t, n)
                    } else n = void 0;
                    return n
                }

                S.extend({
                    hasData        : function(e) {
                        return Z.hasData(e) || K.hasData(e)
                    }, data        : function(e, t, n) {
                        return Z.access(e, t, n)
                    }, removeData  : function(e, t) {
                        Z.remove(e, t)
                    }, _data       : function(e, t, n) {
                        return K.access(e, t, n)
                    }, _removeData : function(e, t) {
                        K.remove(e, t)
                    }
                }), S.fn.extend({
                    data          : function(e, t) {
                        var n, r, o, i = this[0], a = i && i.attributes;
                        if (void 0 === e) {
                            if (this.length && (o = Z.get(i), 1 === i.nodeType && !K.get(i, "hasDataAttrs"))) {
                                for (n = a.length; n--;) a[n] && 0 === (r = a[n].name).indexOf("data-") && (r = Y(r.slice(5)), ne(i, r, o[r]));
                                K.set(i, "hasDataAttrs", !0)
                            }
                            return o
                        }
                        return "object" == typeof e ? this.each((function() {
                            Z.set(this, e)
                        })) : U(this, (function(t) {
                            var n;
                            if (i && void 0 === t) return void 0 !== (n = Z.get(i, e)) || void 0 !== (n = ne(i, e)) ? n : void 0;
                            this.each((function() {
                                Z.set(this, e, t)
                            }))
                        }), null, t, arguments.length > 1, null, !0)
                    }, removeData : function(e) {
                        return this.each((function() {
                            Z.remove(this, e)
                        }))
                    }
                }), S.extend({
                    queue          : function(e, t, n) {
                        var r;
                        if (e) return t = (t || "fx") + "queue", r = K.get(e, t), n && (!r || Array.isArray(n) ? r = K.access(e, t, S.makeArray(n)) : r.push(n)), r || []
                    }, dequeue     : function(e, t) {
                        t = t || "fx";
                        var n = S.queue(e, t), r = n.length, o = n.shift(), i = S._queueHooks(e, t);
                        "inprogress" === o && (o = n.shift(), r--), o && ("fx" === t && n.unshift("inprogress"), delete i.stop, o.call(e, (function() {
                            S.dequeue(e, t)
                        }), i)), !r && i && i.empty.fire()
                    }, _queueHooks : function(e, t) {
                        var n = t + "queueHooks";
                        return K.get(e, n) || K.access(e, n, {
                            empty : S.Callbacks("once memory").add((function() {
                                K.remove(e, [t + "queue", n])
                            }))
                        })
                    }
                }), S.fn.extend({
                    queue         : function(e, t) {
                        var n = 2;
                        return "string" != typeof e && (t = e, e = "fx", n--), arguments.length < n ? S.queue(this[0], e) : void 0 === t ? this : this.each((function() {
                            var n = S.queue(this, e, t);
                            S._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && S.dequeue(this, e)
                        }))
                    }, dequeue    : function(e) {
                        return this.each((function() {
                            S.dequeue(this, e)
                        }))
                    }, clearQueue : function(e) {
                        return this.queue(e || "fx", [])
                    }, promise    : function(e, t) {
                        var n, r = 1, o = S.Deferred(), i = this, a = this.length, s = function() {
                            --r || o.resolveWith(i, [i])
                        };
                        for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; a--;) (n = K.get(i[a], e + "queueHooks")) && n.empty && (r++, n.empty.add(s));
                        return s(), o.promise(t)
                    }
                });
                var re = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
                    oe = new RegExp("^(?:([+-])=|)(" + re + ")([a-z%]*)$", "i"),
                    ie = ["Top", "Right", "Bottom", "Left"], ae = b.documentElement, se = function(e) {
                        return S.contains(e.ownerDocument, e)
                    }, le = {composed : !0};
                ae.getRootNode && (se = function(e) {
                    return S.contains(e.ownerDocument, e) || e.getRootNode(le) === e.ownerDocument
                });
                var ue = function(e, t) {
                    return "none" === (e = t || e).style.display || "" === e.style.display && se(e) && "none" === S.css(e, "display")
                };

                function ce(e, t, n, r) {
                    var o, i, a = 20, s = r ? function() {
                            return r.cur()
                        } : function() {
                            return S.css(e, t, "")
                        }, l = s(), u = n && n[3] || (S.cssNumber[t] ? "" : "px"),
                        c = e.nodeType && (S.cssNumber[t] || "px" !== u && +l) && oe.exec(S.css(e, t));
                    if (c && c[3] !== u) {
                        for (l /= 2, u = u || c[3], c = +l || 1; a--;) S.style(e, t, c + u), (1 - i) * (1 - (i = s() / l || .5)) <= 0 && (a = 0), c /= i;
                        c *= 2, S.style(e, t, c + u), n = n || []
                    }
                    return n && (c = +c || +l || 0, o = n[1] ? c + (n[1] + 1) * n[2] : +n[2], r && (r.unit = u, r.start = c, r.end = o)), o
                }

                var pe = {};

                function fe(e) {
                    var t, n = e.ownerDocument, r = e.nodeName, o = pe[r];
                    return o || (t = n.body.appendChild(n.createElement(r)), o = S.css(t, "display"), t.parentNode.removeChild(t), "none" === o && (o = "block"), pe[r] = o, o)
                }

                function de(e, t) {
                    for (var n, r, o = [], i = 0, a = e.length; i < a; i++) (r = e[i]).style && (n = r.style.display, t ? ("none" === n && (o[i] = K.get(r, "display") || null, o[i] || (r.style.display = "")), "" === r.style.display && ue(r) && (o[i] = fe(r))) : "none" !== n && (o[i] = "none", K.set(r, "display", n)));
                    for (i = 0; i < a; i++) null != o[i] && (e[i].style.display = o[i]);
                    return e
                }

                S.fn.extend({
                    show      : function() {
                        return de(this, !0)
                    }, hide   : function() {
                        return de(this)
                    }, toggle : function(e) {
                        return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each((function() {
                            ue(this) ? S(this).show() : S(this).hide()
                        }))
                    }
                });
                var he, me, ge = /^(?:checkbox|radio)$/i, ve = /<([a-z][^\/\0>\x20\t\r\n\f]*)/i,
                    ye = /^$|^module$|\/(?:java|ecma)script/i;
                he = b.createDocumentFragment().appendChild(b.createElement("div")), (me = b.createElement("input")).setAttribute("type", "radio"), me.setAttribute("checked", "checked"), me.setAttribute("name", "t"), he.appendChild(me), g.checkClone = he.cloneNode(!0).cloneNode(!0).lastChild.checked, he.innerHTML = "<textarea>x</textarea>", g.noCloneChecked = !!he.cloneNode(!0).lastChild.defaultValue, he.innerHTML = "<option></option>", g.option = !!he.lastChild;
                var be = {
                    thead    : [1, "<table cellpadding=0 cellspacing=0 border=0>", "</table>"],
                    col      : [2, "<table cellpadding=0 cellspacing=0 border=0><colgroup>", "</colgroup></table>"],
                    tr       : [2, "<table cellpadding=0 cellspacing=0 border=0><tbody>", "</tbody></table>"],
                    td       : [3, "<table cellpadding=0 cellspacing=0 border=0><tbody><tr>", "</tr></tbody></table>"],
                    _default : [0, "", ""]
                };

                function xe(e, t) {
                    var n;
                    return n = void 0 !== e.getElementsByTagName ? e.getElementsByTagName(t || "*") : void 0 !== e.querySelectorAll ? e.querySelectorAll(t || "*") : [], void 0 === t || t && D(e, t) ? S.merge([e], n) : n
                }

                function we(e, t) {
                    for (var n = 0, r = e.length; n < r; n++) K.set(e[n], "globalEval", !t || K.get(t[n], "globalEval"))
                }

                be.tbody = be.tfoot = be.colgroup = be.caption = be.thead, be.th = be.td, g.option || (be.optgroup = be.option = [1, "<select multiple='multiple'>", "</select>"]);
                var Ce = /<|&#?\w+;/;

                function Te(e, t, n, r, o) {
                    for (var i, a, s, l, u, c, p = t.createDocumentFragment(), f = [], d = 0, h = e.length; d < h; d++) if ((i = e[d]) || 0 === i) if ("object" === C(i)) S.merge(f, i.nodeType ? [i] : i); else if (Ce.test(i)) {
                        for (a = a || p.appendChild(t.createElement("div")), s = (ve.exec(i) || ["", ""])[1].toLowerCase(), l = be[s] || be._default, a.innerHTML = l[1] + S.htmlPrefilter(i) + l[2], c = l[0]; c--;) a = a.lastChild;
                        S.merge(f, a.childNodes), (a = p.firstChild).textContent = ""
                    } else f.push(t.createTextNode(i));
                    for (p.textContent = "", d = 0; i = f[d++];) if (r && S.inArray(i, r) > -1) o && o.push(i); else if (u = se(i), a = xe(p.appendChild(i), "script"), u && we(a), n) for (c = 0; i = a[c++];) ye.test(i.type || "") && n.push(i);
                    return p
                }

                var Se = /^([^.]*)(?:\.(.+)|)/;

                function Ee() {
                    return !0
                }

                function ke() {
                    return !1
                }

                function Ae(e, t) {
                    return e === function() {
                        try {
                            return b.activeElement
                        } catch (e) {
                        }
                    }() == ("focus" === t)
                }

                function je(e, t, n, r, o, i) {
                    var a, s;
                    if ("object" == typeof t) {
                        for (s in"string" != typeof n && (r = r || n, n = void 0), t) je(e, s, n, r, t[s], i);
                        return e
                    }
                    if (null == r && null == o ? (o = n, r = n = void 0) : null == o && ("string" == typeof n ? (o = r, r = void 0) : (o = r, r = n, n = void 0)), !1 === o) o = ke; else if (!o) return e;
                    return 1 === i && (a = o, o = function(e) {
                        return S().off(e), a.apply(this, arguments)
                    }, o.guid = a.guid || (a.guid = S.guid++)), e.each((function() {
                        S.event.add(this, t, o, r, n)
                    }))
                }

                function Ne(e, t, n) {
                    n ? (K.set(e, t, !1), S.event.add(e, t, {
                        namespace : !1, handler : function(e) {
                            var r, o, i = K.get(this, t);
                            if (1 & e.isTrigger && this[t]) {
                                if (i.length) (S.event.special[t] || {}).delegateType && e.stopPropagation(); else if (i = s.call(arguments), K.set(this, t, i), r = n(this, t), this[t](), i !== (o = K.get(this, t)) || r ? K.set(this, t, !1) : o = {}, i !== o) return e.stopImmediatePropagation(), e.preventDefault(), o && o.value
                            } else i.length && (K.set(this, t, {value : S.event.trigger(S.extend(i[0], S.Event.prototype), i.slice(1), this)}), e.stopImmediatePropagation())
                        }
                    })) : void 0 === K.get(e, t) && S.event.add(e, t, Ee)
                }

                S.event = {
                    global      : {}, add : function(e, t, n, r, o) {
                        var i, a, s, l, u, c, p, f, d, h, m, g = K.get(e);
                        if (Q(e)) for (n.handler && (n = (i = n).handler, o = i.selector), o && S.find.matchesSelector(ae, o), n.guid || (n.guid = S.guid++), (l = g.events) || (l = g.events = Object.create(null)), (a = g.handle) || (a = g.handle = function(t) {
                            return void 0 !== S && S.event.triggered !== t.type ? S.event.dispatch.apply(e, arguments) : void 0
                        }), u = (t = (t || "").match(I) || [""]).length; u--;) d = m = (s = Se.exec(t[u]) || [])[1], h = (s[2] || "").split(".").sort(), d && (p = S.event.special[d] || {}, d = (o ? p.delegateType : p.bindType) || d, p = S.event.special[d] || {}, c = S.extend({
                            type         : d,
                            origType     : m,
                            data         : r,
                            handler      : n,
                            guid         : n.guid,
                            selector     : o,
                            needsContext : o && S.expr.match.needsContext.test(o),
                            namespace    : h.join(".")
                        }, i), (f = l[d]) || ((f = l[d] = []).delegateCount = 0, p.setup && !1 !== p.setup.call(e, r, h, a) || e.addEventListener && e.addEventListener(d, a)), p.add && (p.add.call(e, c), c.handler.guid || (c.handler.guid = n.guid)), o ? f.splice(f.delegateCount++, 0, c) : f.push(c), S.event.global[d] = !0)
                    }, remove   : function(e, t, n, r, o) {
                        var i, a, s, l, u, c, p, f, d, h, m, g = K.hasData(e) && K.get(e);
                        if (g && (l = g.events)) {
                            for (u = (t = (t || "").match(I) || [""]).length; u--;) if (d = m = (s = Se.exec(t[u]) || [])[1], h = (s[2] || "").split(".").sort(), d) {
                                for (p = S.event.special[d] || {}, f = l[d = (r ? p.delegateType : p.bindType) || d] || [], s = s[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), a = i = f.length; i--;) c = f[i], !o && m !== c.origType || n && n.guid !== c.guid || s && !s.test(c.namespace) || r && r !== c.selector && ("**" !== r || !c.selector) || (f.splice(i, 1), c.selector && f.delegateCount--, p.remove && p.remove.call(e, c));
                                a && !f.length && (p.teardown && !1 !== p.teardown.call(e, h, g.handle) || S.removeEvent(e, d, g.handle), delete l[d])
                            } else for (d in l) S.event.remove(e, d + t[u], n, r, !0);
                            S.isEmptyObject(l) && K.remove(e, "handle events")
                        }
                    }, dispatch : function(e) {
                        var t, n, r, o, i, a, s = new Array(arguments.length), l = S.event.fix(e),
                            u = (K.get(this, "events") || Object.create(null))[l.type] || [],
                            c = S.event.special[l.type] || {};
                        for (s[0] = l, t = 1; t < arguments.length; t++) s[t] = arguments[t];
                        if (l.delegateTarget = this, !c.preDispatch || !1 !== c.preDispatch.call(this, l)) {
                            for (a = S.event.handlers.call(this, l, u), t = 0; (o = a[t++]) && !l.isPropagationStopped();) for (l.currentTarget = o.elem, n = 0; (i = o.handlers[n++]) && !l.isImmediatePropagationStopped();) l.rnamespace && !1 !== i.namespace && !l.rnamespace.test(i.namespace) || (l.handleObj = i, l.data = i.data, void 0 !== (r = ((S.event.special[i.origType] || {}).handle || i.handler).apply(o.elem, s)) && !1 === (l.result = r) && (l.preventDefault(), l.stopPropagation()));
                            return c.postDispatch && c.postDispatch.call(this, l), l.result
                        }
                    }, handlers : function(e, t) {
                        var n, r, o, i, a, s = [], l = t.delegateCount, u = e.target;
                        if (l && u.nodeType && !("click" === e.type && e.button >= 1)) for (; u !== this; u = u.parentNode || this) if (1 === u.nodeType && ("click" !== e.type || !0 !== u.disabled)) {
                            for (i = [], a = {}, n = 0; n < l; n++) void 0 === a[o = (r = t[n]).selector + " "] && (a[o] = r.needsContext ? S(o, this).index(u) > -1 : S.find(o, this, null, [u]).length), a[o] && i.push(r);
                            i.length && s.push({elem : u, handlers : i})
                        }
                        return u = this, l < t.length && s.push({elem : u, handlers : t.slice(l)}), s
                    }, addProp  : function(e, t) {
                        Object.defineProperty(S.Event.prototype, e, {
                            enumerable   : !0,
                            configurable : !0,
                            get          : v(t) ? function() {
                                if (this.originalEvent) return t(this.originalEvent)
                            } : function() {
                                if (this.originalEvent) return this.originalEvent[e]
                            },
                            set          : function(t) {
                                Object.defineProperty(this, e, {
                                    enumerable   : !0,
                                    configurable : !0,
                                    writable     : !0,
                                    value        : t
                                })
                            }
                        })
                    }, fix      : function(e) {
                        return e[S.expando] ? e : new S.Event(e)
                    }, special  : {
                        load            : {noBubble : !0}, click : {
                            setup       : function(e) {
                                var t = this || e;
                                return ge.test(t.type) && t.click && D(t, "input") && Ne(t, "click", Ee), !1
                            }, trigger  : function(e) {
                                var t = this || e;
                                return ge.test(t.type) && t.click && D(t, "input") && Ne(t, "click"), !0
                            }, _default : function(e) {
                                var t = e.target;
                                return ge.test(t.type) && t.click && D(t, "input") && K.get(t, "click") || D(t, "a")
                            }
                        }, beforeunload : {
                            postDispatch : function(e) {
                                void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                            }
                        }
                    }
                }, S.removeEvent = function(e, t, n) {
                    e.removeEventListener && e.removeEventListener(t, n)
                }, S.Event = function(e, t) {
                    if (!(this instanceof S.Event)) return new S.Event(e, t);
                    e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && !1 === e.returnValue ? Ee : ke, this.target = e.target && 3 === e.target.nodeType ? e.target.parentNode : e.target, this.currentTarget = e.currentTarget, this.relatedTarget = e.relatedTarget) : this.type = e, t && S.extend(this, t), this.timeStamp = e && e.timeStamp || Date.now(), this[S.expando] = !0
                }, S.Event.prototype = {
                    constructor                   : S.Event,
                    isDefaultPrevented            : ke,
                    isPropagationStopped          : ke,
                    isImmediatePropagationStopped : ke,
                    isSimulated                   : !1,
                    preventDefault                : function() {
                        var e = this.originalEvent;
                        this.isDefaultPrevented = Ee, e && !this.isSimulated && e.preventDefault()
                    },
                    stopPropagation               : function() {
                        var e = this.originalEvent;
                        this.isPropagationStopped = Ee, e && !this.isSimulated && e.stopPropagation()
                    },
                    stopImmediatePropagation      : function() {
                        var e = this.originalEvent;
                        this.isImmediatePropagationStopped = Ee, e && !this.isSimulated && e.stopImmediatePropagation(), this.stopPropagation()
                    }
                }, S.each({
                    altKey         : !0,
                    bubbles        : !0,
                    cancelable     : !0,
                    changedTouches : !0,
                    ctrlKey        : !0,
                    detail         : !0,
                    eventPhase     : !0,
                    metaKey        : !0,
                    pageX          : !0,
                    pageY          : !0,
                    shiftKey       : !0,
                    view           : !0,
                    char           : !0,
                    code           : !0,
                    charCode       : !0,
                    key            : !0,
                    keyCode        : !0,
                    button         : !0,
                    buttons        : !0,
                    clientX        : !0,
                    clientY        : !0,
                    offsetX        : !0,
                    offsetY        : !0,
                    pointerId      : !0,
                    pointerType    : !0,
                    screenX        : !0,
                    screenY        : !0,
                    targetTouches  : !0,
                    toElement      : !0,
                    touches        : !0,
                    which          : !0
                }, S.event.addProp), S.each({focus : "focusin", blur : "focusout"}, (function(e, t) {
                    S.event.special[e] = {
                        setup           : function() {
                            return Ne(this, e, Ae), !1
                        }, trigger      : function() {
                            return Ne(this, e), !0
                        }, _default     : function() {
                            return !0
                        }, delegateType : t
                    }
                })), S.each({
                    mouseenter   : "mouseover",
                    mouseleave   : "mouseout",
                    pointerenter : "pointerover",
                    pointerleave : "pointerout"
                }, (function(e, t) {
                    S.event.special[e] = {
                        delegateType : t, bindType : t, handle : function(e) {
                            var n, r = this, o = e.relatedTarget, i = e.handleObj;
                            return o && (o === r || S.contains(r, o)) || (e.type = i.origType, n = i.handler.apply(this, arguments), e.type = t), n
                        }
                    }
                })), S.fn.extend({
                    on     : function(e, t, n, r) {
                        return je(this, e, t, n, r)
                    }, one : function(e, t, n, r) {
                        return je(this, e, t, n, r, 1)
                    }, off : function(e, t, n) {
                        var r, o;
                        if (e && e.preventDefault && e.handleObj) return r = e.handleObj, S(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace : r.origType, r.selector, r.handler), this;
                        if ("object" == typeof e) {
                            for (o in e) this.off(o, t, e[o]);
                            return this
                        }
                        return !1 !== t && "function" != typeof t || (n = t, t = void 0), !1 === n && (n = ke), this.each((function() {
                            S.event.remove(this, e, n, t)
                        }))
                    }
                });
                var De = /<script|<style|<link/i, He = /checked\s*(?:[^=]|=\s*.checked.)/i,
                    Oe = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;

                function Le(e, t) {
                    return D(e, "table") && D(11 !== t.nodeType ? t : t.firstChild, "tr") && S(e).children("tbody")[0] || e
                }

                function Re(e) {
                    return e.type = (null !== e.getAttribute("type")) + "/" + e.type, e
                }

                function qe(e) {
                    return "true/" === (e.type || "").slice(0, 5) ? e.type = e.type.slice(5) : e.removeAttribute("type"), e
                }

                function Pe(e, t) {
                    var n, r, o, i, a, s;
                    if (1 === t.nodeType) {
                        if (K.hasData(e) && (s = K.get(e).events)) for (o in K.remove(t, "handle events"), s) for (n = 0, r = s[o].length; n < r; n++) S.event.add(t, o, s[o][n]);
                        Z.hasData(e) && (i = Z.access(e), a = S.extend({}, i), Z.set(t, a))
                    }
                }

                function Me(e, t) {
                    var n = t.nodeName.toLowerCase();
                    "input" === n && ge.test(e.type) ? t.checked = e.checked : "input" !== n && "textarea" !== n || (t.defaultValue = e.defaultValue)
                }

                function Ie(e, t, n, r) {
                    t = l(t);
                    var o, i, a, s, u, c, p = 0, f = e.length, d = f - 1, h = t[0], m = v(h);
                    if (m || f > 1 && "string" == typeof h && !g.checkClone && He.test(h)) return e.each((function(o) {
                        var i = e.eq(o);
                        m && (t[0] = h.call(this, o, i.html())), Ie(i, t, n, r)
                    }));
                    if (f && (i = (o = Te(t, e[0].ownerDocument, !1, e, r)).firstChild, 1 === o.childNodes.length && (o = i), i || r)) {
                        for (s = (a = S.map(xe(o, "script"), Re)).length; p < f; p++) u = o, p !== d && (u = S.clone(u, !0, !0), s && S.merge(a, xe(u, "script"))), n.call(e[p], u, p);
                        if (s) for (c = a[a.length - 1].ownerDocument, S.map(a, qe), p = 0; p < s; p++) u = a[p], ye.test(u.type || "") && !K.access(u, "globalEval") && S.contains(c, u) && (u.src && "module" !== (u.type || "").toLowerCase() ? S._evalUrl && !u.noModule && S._evalUrl(u.src, {nonce : u.nonce || u.getAttribute("nonce")}, c) : w(u.textContent.replace(Oe, ""), u, c))
                    }
                    return e
                }

                function $e(e, t, n) {
                    for (var r, o = t ? S.filter(t, e) : e, i = 0; null != (r = o[i]); i++) n || 1 !== r.nodeType || S.cleanData(xe(r)), r.parentNode && (n && se(r) && we(xe(r, "script")), r.parentNode.removeChild(r));
                    return e
                }

                S.extend({
                    htmlPrefilter : function(e) {
                        return e
                    }, clone      : function(e, t, n) {
                        var r, o, i, a, s = e.cloneNode(!0), l = se(e);
                        if (!(g.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || S.isXMLDoc(e))) for (a = xe(s), r = 0, o = (i = xe(e)).length; r < o; r++) Me(i[r], a[r]);
                        if (t) if (n) for (i = i || xe(e), a = a || xe(s), r = 0, o = i.length; r < o; r++) Pe(i[r], a[r]); else Pe(e, s);
                        return (a = xe(s, "script")).length > 0 && we(a, !l && xe(e, "script")), s
                    }, cleanData  : function(e) {
                        for (var t, n, r, o = S.event.special, i = 0; void 0 !== (n = e[i]); i++) if (Q(n)) {
                            if (t = n[K.expando]) {
                                if (t.events) for (r in t.events) o[r] ? S.event.remove(n, r) : S.removeEvent(n, r, t.handle);
                                n[K.expando] = void 0
                            }
                            n[Z.expando] && (n[Z.expando] = void 0)
                        }
                    }
                }), S.fn.extend({
                    detach         : function(e) {
                        return $e(this, e, !0)
                    }, remove      : function(e) {
                        return $e(this, e)
                    }, text        : function(e) {
                        return U(this, (function(e) {
                            return void 0 === e ? S.text(this) : this.empty().each((function() {
                                1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = e)
                            }))
                        }), null, e, arguments.length)
                    }, append      : function() {
                        return Ie(this, arguments, (function(e) {
                            1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || Le(this, e).appendChild(e)
                        }))
                    }, prepend     : function() {
                        return Ie(this, arguments, (function(e) {
                            if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                                var t = Le(this, e);
                                t.insertBefore(e, t.firstChild)
                            }
                        }))
                    }, before      : function() {
                        return Ie(this, arguments, (function(e) {
                            this.parentNode && this.parentNode.insertBefore(e, this)
                        }))
                    }, after       : function() {
                        return Ie(this, arguments, (function(e) {
                            this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
                        }))
                    }, empty       : function() {
                        for (var e, t = 0; null != (e = this[t]); t++) 1 === e.nodeType && (S.cleanData(xe(e, !1)), e.textContent = "");
                        return this
                    }, clone       : function(e, t) {
                        return e = null != e && e, t = null == t ? e : t, this.map((function() {
                            return S.clone(this, e, t)
                        }))
                    }, html        : function(e) {
                        return U(this, (function(e) {
                            var t = this[0] || {}, n = 0, r = this.length;
                            if (void 0 === e && 1 === t.nodeType) return t.innerHTML;
                            if ("string" == typeof e && !De.test(e) && !be[(ve.exec(e) || ["", ""])[1].toLowerCase()]) {
                                e = S.htmlPrefilter(e);
                                try {
                                    for (; n < r; n++) 1 === (t = this[n] || {}).nodeType && (S.cleanData(xe(t, !1)), t.innerHTML = e);
                                    t = 0
                                } catch (e) {
                                }
                            }
                            t && this.empty().append(e)
                        }), null, e, arguments.length)
                    }, replaceWith : function() {
                        var e = [];
                        return Ie(this, arguments, (function(t) {
                            var n = this.parentNode;
                            S.inArray(this, e) < 0 && (S.cleanData(xe(this)), n && n.replaceChild(t, this))
                        }), e)
                    }
                }), S.each({
                    appendTo     : "append",
                    prependTo    : "prepend",
                    insertBefore : "before",
                    insertAfter  : "after",
                    replaceAll   : "replaceWith"
                }, (function(e, t) {
                    S.fn[e] = function(e) {
                        for (var n, r = [], o = S(e), i = o.length - 1, a = 0; a <= i; a++) n = a === i ? this : this.clone(!0), S(o[a])[t](n), u.apply(r, n.get());
                        return this.pushStack(r)
                    }
                }));
                var Be = new RegExp("^(" + re + ")(?!px)[a-z%]+$", "i"), We = function(e) {
                    var t = e.ownerDocument.defaultView;
                    return t && t.opener || (t = r), t.getComputedStyle(e)
                }, Fe  = function(e, t, n) {
                    var r, o, i = {};
                    for (o in t) i[o] = e.style[o], e.style[o] = t[o];
                    for (o in r = n.call(e), t) e.style[o] = i[o];
                    return r
                }, _e  = new RegExp(ie.join("|"), "i");

                function ze(e, t, n) {
                    var r, o, i, a, s = e.style;
                    return (n = n || We(e)) && ("" !== (a = n.getPropertyValue(t) || n[t]) || se(e) || (a = S.style(e, t)), !g.pixelBoxStyles() && Be.test(a) && _e.test(t) && (r = s.width, o = s.minWidth, i = s.maxWidth, s.minWidth = s.maxWidth = s.width = a, a = n.width, s.width = r, s.minWidth = o, s.maxWidth = i)), void 0 !== a ? a + "" : a
                }

                function Ue(e, t) {
                    return {
                        get : function() {
                            if (!e()) return (this.get = t).apply(this, arguments);
                            delete this.get
                        }
                    }
                }

                !function() {
                    function e() {
                        if (c) {
                            u.style.cssText = "position:absolute;left:-11111px;width:60px;" + "margin-top:1px;padding:0;border:0", c.style.cssText = "position:relative;display:block;box-sizing:border-box;overflow:scroll;" + "margin:auto;border:1px;padding:1px;" + "width:60%;top:1%", ae.appendChild(u).appendChild(c);
                            var e = r.getComputedStyle(c);
                            n = "1%" !== e.top, l = 12 === t(e.marginLeft), c.style.right = "60%", a = 36 === t(e.right), o = 36 === t(e.width), c.style.position = "absolute", i = 12 === t(c.offsetWidth / 3), ae.removeChild(u), c = null
                        }
                    }

                    function t(e) {
                        return Math.round(parseFloat(e))
                    }

                    var n, o, i, a, s, l, u = b.createElement("div"), c = b.createElement("div");
                    c.style && (c.style.backgroundClip = "content-box", c.cloneNode(!0).style.backgroundClip = "", g.clearCloneStyle = "content-box" === c.style.backgroundClip, S.extend(g, {
                        boxSizingReliable    : function() {
                            return e(), o
                        },
                        pixelBoxStyles       : function() {
                            return e(), a
                        },
                        pixelPosition        : function() {
                            return e(), n
                        },
                        reliableMarginLeft   : function() {
                            return e(), l
                        },
                        scrollboxSize        : function() {
                            return e(), i
                        },
                        reliableTrDimensions : function() {
                            var e, t, n, o;
                            return null == s && (e = b.createElement("table"), t = b.createElement("tr"), n = b.createElement("div"), e.style.cssText = "position:absolute;left:-11111px;border-collapse:separate", t.style.cssText = "border:1px solid", t.style.height = "1px", n.style.height = "9px", n.style.display = "block", ae.appendChild(e).appendChild(t).appendChild(n), o = r.getComputedStyle(t), s = parseInt(o.height, 10) + parseInt(o.borderTopWidth, 10) + parseInt(o.borderBottomWidth, 10) === t.offsetHeight, ae.removeChild(e)), s
                        }
                    }))
                }();
                var Xe = ["Webkit", "Moz", "ms"], Ve = b.createElement("div").style, Ge = {};

                function Ye(e) {
                    var t = S.cssProps[e] || Ge[e];
                    return t || (e in Ve ? e : Ge[e] = function(e) {
                        for (var t = e[0].toUpperCase() + e.slice(1), n = Xe.length; n--;) if ((e = Xe[n] + t) in Ve) return e
                    }(e) || e)
                }

                var Qe = /^(none|table(?!-c[ea]).+)/, Je = /^--/,
                    Ke = {position : "absolute", visibility : "hidden", display : "block"},
                    Ze = {letterSpacing : "0", fontWeight : "400"};

                function et(e, t, n) {
                    var r = oe.exec(t);
                    return r ? Math.max(0, r[2] - (n || 0)) + (r[3] || "px") : t
                }

                function tt(e, t, n, r, o, i) {
                    var a = "width" === t ? 1 : 0, s = 0, l = 0;
                    if (n === (r ? "border" : "content")) return 0;
                    for (; a < 4; a += 2) "margin" === n && (l += S.css(e, n + ie[a], !0, o)), r ? ("content" === n && (l -= S.css(e, "padding" + ie[a], !0, o)), "margin" !== n && (l -= S.css(e, "border" + ie[a] + "Width", !0, o))) : (l += S.css(e, "padding" + ie[a], !0, o), "padding" !== n ? l += S.css(e, "border" + ie[a] + "Width", !0, o) : s += S.css(e, "border" + ie[a] + "Width", !0, o));
                    return !r && i >= 0 && (l += Math.max(0, Math.ceil(e["offset" + t[0].toUpperCase() + t.slice(1)] - i - l - s - .5)) || 0), l
                }

                function nt(e, t, n) {
                    var r = We(e), o = (!g.boxSizingReliable() || n) && "border-box" === S.css(e, "boxSizing", !1, r),
                        i = o, a = ze(e, t, r), s = "offset" + t[0].toUpperCase() + t.slice(1);
                    if (Be.test(a)) {
                        if (!n) return a;
                        a = "auto"
                    }
                    return (!g.boxSizingReliable() && o || !g.reliableTrDimensions() && D(e, "tr") || "auto" === a || !parseFloat(a) && "inline" === S.css(e, "display", !1, r)) && e.getClientRects().length && (o = "border-box" === S.css(e, "boxSizing", !1, r), (i = s in e) && (a = e[s])), (a = parseFloat(a) || 0) + tt(e, t, n || (o ? "border" : "content"), i, r, a) + "px"
                }

                function rt(e, t, n, r, o) {
                    return new rt.prototype.init(e, t, n, r, o)
                }

                S.extend({
                    cssHooks  : {
                        opacity : {
                            get : function(e, t) {
                                if (t) {
                                    var n = ze(e, "opacity");
                                    return "" === n ? "1" : n
                                }
                            }
                        }
                    },
                    cssNumber : {
                        animationIterationCount : !0,
                        columnCount             : !0,
                        fillOpacity             : !0,
                        flexGrow                : !0,
                        flexShrink              : !0,
                        fontWeight              : !0,
                        gridArea                : !0,
                        gridColumn              : !0,
                        gridColumnEnd           : !0,
                        gridColumnStart         : !0,
                        gridRow                 : !0,
                        gridRowEnd              : !0,
                        gridRowStart            : !0,
                        lineHeight              : !0,
                        opacity                 : !0,
                        order                   : !0,
                        orphans                 : !0,
                        widows                  : !0,
                        zIndex                  : !0,
                        zoom                    : !0
                    },
                    cssProps  : {},
                    style     : function(e, t, n, r) {
                        if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                            var o, i, a, s = Y(t), l = Je.test(t), u = e.style;
                            if (l || (t = Ye(s)), a = S.cssHooks[t] || S.cssHooks[s], void 0 === n) return a && "get" in a && void 0 !== (o = a.get(e, !1, r)) ? o : u[t];
                            "string" === (i = typeof n) && (o = oe.exec(n)) && o[1] && (n = ce(e, t, o), i = "number"), null != n && n == n && ("number" !== i || l || (n += o && o[3] || (S.cssNumber[s] ? "" : "px")), g.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (u[t] = "inherit"), a && "set" in a && void 0 === (n = a.set(e, n, r)) || (l ? u.setProperty(t, n) : u[t] = n))
                        }
                    },
                    css       : function(e, t, n, r) {
                        var o, i, a, s = Y(t);
                        return Je.test(t) || (t = Ye(s)), (a = S.cssHooks[t] || S.cssHooks[s]) && "get" in a && (o = a.get(e, !0, n)), void 0 === o && (o = ze(e, t, r)), "normal" === o && t in Ze && (o = Ze[t]), "" === n || n ? (i = parseFloat(o), !0 === n || isFinite(i) ? i || 0 : o) : o
                    }
                }), S.each(["height", "width"], (function(e, t) {
                    S.cssHooks[t] = {
                        get    : function(e, n, r) {
                            if (n) return !Qe.test(S.css(e, "display")) || e.getClientRects().length && e.getBoundingClientRect().width ? nt(e, t, r) : Fe(e, Ke, (function() {
                                return nt(e, t, r)
                            }))
                        }, set : function(e, n, r) {
                            var o, i = We(e), a = !g.scrollboxSize() && "absolute" === i.position,
                                s = (a || r) && "border-box" === S.css(e, "boxSizing", !1, i),
                                l = r ? tt(e, t, r, s, i) : 0;
                            return s && a && (l -= Math.ceil(e["offset" + t[0].toUpperCase() + t.slice(1)] - parseFloat(i[t]) - tt(e, t, "border", !1, i) - .5)), l && (o = oe.exec(n)) && "px" !== (o[3] || "px") && (e.style[t] = n, n = S.css(e, t)), et(0, n, l)
                        }
                    }
                })), S.cssHooks.marginLeft = Ue(g.reliableMarginLeft, (function(e, t) {
                    if (t) return (parseFloat(ze(e, "marginLeft")) || e.getBoundingClientRect().left - Fe(e, {marginLeft : 0}, (function() {
                        return e.getBoundingClientRect().left
                    }))) + "px"
                })), S.each({margin : "", padding : "", border : "Width"}, (function(e, t) {
                    S.cssHooks[e + t] = {
                        expand : function(n) {
                            for (var r = 0, o = {}, i = "string" == typeof n ? n.split(" ") : [n]; r < 4; r++) o[e + ie[r] + t] = i[r] || i[r - 2] || i[0];
                            return o
                        }
                    }, "margin" !== e && (S.cssHooks[e + t].set = et)
                })), S.fn.extend({
                    css : function(e, t) {
                        return U(this, (function(e, t, n) {
                            var r, o, i = {}, a = 0;
                            if (Array.isArray(t)) {
                                for (r = We(e), o = t.length; a < o; a++) i[t[a]] = S.css(e, t[a], !1, r);
                                return i
                            }
                            return void 0 !== n ? S.style(e, t, n) : S.css(e, t)
                        }), e, t, arguments.length > 1)
                    }
                }), S.Tween = rt, rt.prototype = {
                    constructor : rt, init : function(e, t, n, r, o, i) {
                        this.elem = e, this.prop = n, this.easing = o || S.easing._default, this.options = t, this.start = this.now = this.cur(), this.end = r, this.unit = i || (S.cssNumber[n] ? "" : "px")
                    }, cur      : function() {
                        var e = rt.propHooks[this.prop];
                        return e && e.get ? e.get(this) : rt.propHooks._default.get(this)
                    }, run      : function(e) {
                        var t, n = rt.propHooks[this.prop];
                        return this.options.duration ? this.pos = t = S.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : rt.propHooks._default.set(this), this
                    }
                }, rt.prototype.init.prototype = rt.prototype, rt.propHooks = {
                    _default : {
                        get    : function(e) {
                            var t;
                            return 1 !== e.elem.nodeType || null != e.elem[e.prop] && null == e.elem.style[e.prop] ? e.elem[e.prop] : (t = S.css(e.elem, e.prop, "")) && "auto" !== t ? t : 0
                        }, set : function(e) {
                            S.fx.step[e.prop] ? S.fx.step[e.prop](e) : 1 !== e.elem.nodeType || !S.cssHooks[e.prop] && null == e.elem.style[Ye(e.prop)] ? e.elem[e.prop] = e.now : S.style(e.elem, e.prop, e.now + e.unit)
                        }
                    }
                }, rt.propHooks.scrollTop = rt.propHooks.scrollLeft = {
                    set : function(e) {
                        e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
                    }
                }, S.easing = {
                    linear      : function(e) {
                        return e
                    }, swing    : function(e) {
                        return .5 - Math.cos(e * Math.PI) / 2
                    }, _default : "swing"
                }, S.fx = rt.prototype.init, S.fx.step = {};
                var ot, it, at = /^(?:toggle|show|hide)$/, st = /queueHooks$/;

                function lt() {
                    it && (!1 === b.hidden && r.requestAnimationFrame ? r.requestAnimationFrame(lt) : r.setTimeout(lt, S.fx.interval), S.fx.tick())
                }

                function ut() {
                    return r.setTimeout((function() {
                        ot = void 0
                    })), ot = Date.now()
                }

                function ct(e, t) {
                    var n, r = 0, o = {height : e};
                    for (t = t ? 1 : 0; r < 4; r += 2 - t) o["margin" + (n = ie[r])] = o["padding" + n] = e;
                    return t && (o.opacity = o.width = e), o
                }

                function pt(e, t, n) {
                    for (var r, o = (ft.tweeners[t] || []).concat(ft.tweeners["*"]), i = 0, a = o.length; i < a; i++) if (r = o[i].call(n, t, e)) return r
                }

                function ft(e, t, n) {
                    var r, o, i = 0, a = ft.prefilters.length, s = S.Deferred().always((function() {
                        delete l.elem
                    })), l      = function() {
                        if (o) return !1;
                        for (var t = ot || ut(), n = Math.max(0, u.startTime + u.duration - t), r = 1 - (n / u.duration || 0), i = 0, a = u.tweens.length; i < a; i++) u.tweens[i].run(r);
                        return s.notifyWith(e, [u, r, n]), r < 1 && a ? n : (a || s.notifyWith(e, [u, 1, 0]), s.resolveWith(e, [u]), !1)
                    }, u        = s.promise({
                        elem               : e,
                        props              : S.extend({}, t),
                        opts               : S.extend(!0, {specialEasing : {}, easing : S.easing._default}, n),
                        originalProperties : t,
                        originalOptions    : n,
                        startTime          : ot || ut(),
                        duration           : n.duration,
                        tweens             : [],
                        createTween        : function(t, n) {
                            var r = S.Tween(e, u.opts, t, n, u.opts.specialEasing[t] || u.opts.easing);
                            return u.tweens.push(r), r
                        },
                        stop               : function(t) {
                            var n = 0, r = t ? u.tweens.length : 0;
                            if (o) return this;
                            for (o = !0; n < r; n++) u.tweens[n].run(1);
                            return t ? (s.notifyWith(e, [u, 1, 0]), s.resolveWith(e, [u, t])) : s.rejectWith(e, [u, t]), this
                        }
                    }), c       = u.props;
                    for (!function(e, t) {
                        var n, r, o, i, a;
                        for (n in e) if (o = t[r = Y(n)], i = e[n], Array.isArray(i) && (o = i[1], i = e[n] = i[0]), n !== r && (e[r] = i, delete e[n]), (a = S.cssHooks[r]) && "expand" in a) for (n in i = a.expand(i), delete e[r], i) n in e || (e[n] = i[n], t[n] = o); else t[r] = o
                    }(c, u.opts.specialEasing); i < a; i++) if (r = ft.prefilters[i].call(u, e, c, u.opts)) return v(r.stop) && (S._queueHooks(u.elem, u.opts.queue).stop = r.stop.bind(r)), r;
                    return S.map(c, pt, u), v(u.opts.start) && u.opts.start.call(e, u), u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always), S.fx.timer(S.extend(l, {
                        elem  : e,
                        anim  : u,
                        queue : u.opts.queue
                    })), u
                }

                S.Animation = S.extend(ft, {
                    tweeners      : {
                        "*" : [function(e, t) {
                            var n = this.createTween(e, t);
                            return ce(n.elem, e, oe.exec(t), n), n
                        }]
                    }, tweener    : function(e, t) {
                        v(e) ? (t = e, e = ["*"]) : e = e.match(I);
                        for (var n, r = 0, o = e.length; r < o; r++) n = e[r], ft.tweeners[n] = ft.tweeners[n] || [], ft.tweeners[n].unshift(t)
                    }, prefilters : [function(e, t, n) {
                        var r, o, i, a, s, l, u, c, p = "width" in t || "height" in t, f = this, d = {}, h = e.style,
                            m = e.nodeType && ue(e), g = K.get(e, "fxshow");
                        for (r in n.queue || (null == (a = S._queueHooks(e, "fx")).unqueued && (a.unqueued = 0, s = a.empty.fire, a.empty.fire = function() {
                            a.unqueued || s()
                        }), a.unqueued++, f.always((function() {
                            f.always((function() {
                                a.unqueued--, S.queue(e, "fx").length || a.empty.fire()
                            }))
                        }))), t) if (o = t[r], at.test(o)) {
                            if (delete t[r], i = i || "toggle" === o, o === (m ? "hide" : "show")) {
                                if ("show" !== o || !g || void 0 === g[r]) continue;
                                m = !0
                            }
                            d[r] = g && g[r] || S.style(e, r)
                        }
                        if ((l = !S.isEmptyObject(t)) || !S.isEmptyObject(d)) for (r in p && 1 === e.nodeType && (n.overflow = [h.overflow, h.overflowX, h.overflowY], null == (u = g && g.display) && (u = K.get(e, "display")), "none" === (c = S.css(e, "display")) && (u ? c = u : (de([e], !0), u = e.style.display || u, c = S.css(e, "display"), de([e]))), ("inline" === c || "inline-block" === c && null != u) && "none" === S.css(e, "float") && (l || (f.done((function() {
                            h.display = u
                        })), null == u && (c = h.display, u = "none" === c ? "" : c)), h.display = "inline-block")), n.overflow && (h.overflow = "hidden", f.always((function() {
                            h.overflow = n.overflow[0], h.overflowX = n.overflow[1], h.overflowY = n.overflow[2]
                        }))), l = !1, d) l || (g ? "hidden" in g && (m = g.hidden) : g = K.access(e, "fxshow", {display : u}), i && (g.hidden = !m), m && de([e], !0), f.done((function() {
                            for (r in m || de([e]), K.remove(e, "fxshow"), d) S.style(e, r, d[r])
                        }))), l = pt(m ? g[r] : 0, r, f), r in g || (g[r] = l.start, m && (l.end = l.start, l.start = 0))
                    }], prefilter : function(e, t) {
                        t ? ft.prefilters.unshift(e) : ft.prefilters.push(e)
                    }
                }), S.speed = function(e, t, n) {
                    var r = e && "object" == typeof e ? S.extend({}, e) : {
                        complete : n || !n && t || v(e) && e,
                        duration : e,
                        easing   : n && t || t && !v(t) && t
                    };
                    return S.fx.off ? r.duration = 0 : "number" != typeof r.duration && (r.duration in S.fx.speeds ? r.duration = S.fx.speeds[r.duration] : r.duration = S.fx.speeds._default), null != r.queue && !0 !== r.queue || (r.queue = "fx"), r.old = r.complete, r.complete = function() {
                        v(r.old) && r.old.call(this), r.queue && S.dequeue(this, r.queue)
                    }, r
                }, S.fn.extend({
                    fadeTo     : function(e, t, n, r) {
                        return this.filter(ue).css("opacity", 0).show().end().animate({opacity : t}, e, n, r)
                    }, animate : function(e, t, n, r) {
                        var o = S.isEmptyObject(e), i = S.speed(t, n, r), a = function() {
                            var t = ft(this, S.extend({}, e), i);
                            (o || K.get(this, "finish")) && t.stop(!0)
                        };
                        return a.finish = a, o || !1 === i.queue ? this.each(a) : this.queue(i.queue, a)
                    }, stop    : function(e, t, n) {
                        var r = function(e) {
                            var t = e.stop;
                            delete e.stop, t(n)
                        };
                        return "string" != typeof e && (n = t, t = e, e = void 0), t && this.queue(e || "fx", []), this.each((function() {
                            var t = !0, o = null != e && e + "queueHooks", i = S.timers, a = K.get(this);
                            if (o) a[o] && a[o].stop && r(a[o]); else for (o in a) a[o] && a[o].stop && st.test(o) && r(a[o]);
                            for (o = i.length; o--;) i[o].elem !== this || null != e && i[o].queue !== e || (i[o].anim.stop(n), t = !1, i.splice(o, 1));
                            !t && n || S.dequeue(this, e)
                        }))
                    }, finish  : function(e) {
                        return !1 !== e && (e = e || "fx"), this.each((function() {
                            var t, n = K.get(this), r = n[e + "queue"], o = n[e + "queueHooks"], i = S.timers,
                                a = r ? r.length : 0;
                            for (n.finish = !0, S.queue(this, e, []), o && o.stop && o.stop.call(this, !0), t = i.length; t--;) i[t].elem === this && i[t].queue === e && (i[t].anim.stop(!0), i.splice(t, 1));
                            for (t = 0; t < a; t++) r[t] && r[t].finish && r[t].finish.call(this);
                            delete n.finish
                        }))
                    }
                }), S.each(["toggle", "show", "hide"], (function(e, t) {
                    var n = S.fn[t];
                    S.fn[t] = function(e, r, o) {
                        return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(ct(t, !0), e, r, o)
                    }
                })), S.each({
                    slideDown   : ct("show"),
                    slideUp     : ct("hide"),
                    slideToggle : ct("toggle"),
                    fadeIn      : {opacity : "show"},
                    fadeOut     : {opacity : "hide"},
                    fadeToggle  : {opacity : "toggle"}
                }, (function(e, t) {
                    S.fn[e] = function(e, n, r) {
                        return this.animate(t, e, n, r)
                    }
                })), S.timers = [], S.fx.tick = function() {
                    var e, t = 0, n = S.timers;
                    for (ot = Date.now(); t < n.length; t++) (e = n[t])() || n[t] !== e || n.splice(t--, 1);
                    n.length || S.fx.stop(), ot = void 0
                }, S.fx.timer = function(e) {
                    S.timers.push(e), S.fx.start()
                }, S.fx.interval = 13, S.fx.start = function() {
                    it || (it = !0, lt())
                }, S.fx.stop = function() {
                    it = null
                }, S.fx.speeds = {slow : 600, fast : 200, _default : 400}, S.fn.delay = function(e, t) {
                    return e = S.fx && S.fx.speeds[e] || e, t = t || "fx", this.queue(t, (function(t, n) {
                        var o = r.setTimeout(t, e);
                        n.stop = function() {
                            r.clearTimeout(o)
                        }
                    }))
                }, function() {
                    var e = b.createElement("input"),
                        t = b.createElement("select").appendChild(b.createElement("option"));
                    e.type = "checkbox", g.checkOn = "" !== e.value, g.optSelected = t.selected, (e = b.createElement("input")).value = "t", e.type = "radio", g.radioValue = "t" === e.value
                }();
                var dt, ht = S.expr.attrHandle;
                S.fn.extend({
                    attr          : function(e, t) {
                        return U(this, S.attr, e, t, arguments.length > 1)
                    }, removeAttr : function(e) {
                        return this.each((function() {
                            S.removeAttr(this, e)
                        }))
                    }
                }), S.extend({
                    attr          : function(e, t, n) {
                        var r, o, i = e.nodeType;
                        if (3 !== i && 8 !== i && 2 !== i) return void 0 === e.getAttribute ? S.prop(e, t, n) : (1 === i && S.isXMLDoc(e) || (o = S.attrHooks[t.toLowerCase()] || (S.expr.match.bool.test(t) ? dt : void 0)), void 0 !== n ? null === n ? void S.removeAttr(e, t) : o && "set" in o && void 0 !== (r = o.set(e, n, t)) ? r : (e.setAttribute(t, n + ""), n) : o && "get" in o && null !== (r = o.get(e, t)) ? r : null == (r = S.find.attr(e, t)) ? void 0 : r)
                    }, attrHooks  : {
                        type : {
                            set : function(e, t) {
                                if (!g.radioValue && "radio" === t && D(e, "input")) {
                                    var n = e.value;
                                    return e.setAttribute("type", t), n && (e.value = n), t
                                }
                            }
                        }
                    }, removeAttr : function(e, t) {
                        var n, r = 0, o = t && t.match(I);
                        if (o && 1 === e.nodeType) for (; n = o[r++];) e.removeAttribute(n)
                    }
                }), dt = {
                    set : function(e, t, n) {
                        return !1 === t ? S.removeAttr(e, n) : e.setAttribute(n, n), n
                    }
                }, S.each(S.expr.match.bool.source.match(/\w+/g), (function(e, t) {
                    var n = ht[t] || S.find.attr;
                    ht[t] = function(e, t, r) {
                        var o, i, a = t.toLowerCase();
                        return r || (i = ht[a], ht[a] = o, o = null != n(e, t, r) ? a : null, ht[a] = i), o
                    }
                }));
                var mt = /^(?:input|select|textarea|button)$/i, gt = /^(?:a|area)$/i;

                function vt(e) {
                    return (e.match(I) || []).join(" ")
                }

                function yt(e) {
                    return e.getAttribute && e.getAttribute("class") || ""
                }

                function bt(e) {
                    return Array.isArray(e) ? e : "string" == typeof e && e.match(I) || []
                }

                S.fn.extend({
                    prop          : function(e, t) {
                        return U(this, S.prop, e, t, arguments.length > 1)
                    }, removeProp : function(e) {
                        return this.each((function() {
                            delete this[S.propFix[e] || e]
                        }))
                    }
                }), S.extend({
                    prop         : function(e, t, n) {
                        var r, o, i = e.nodeType;
                        if (3 !== i && 8 !== i && 2 !== i) return 1 === i && S.isXMLDoc(e) || (t = S.propFix[t] || t, o = S.propHooks[t]), void 0 !== n ? o && "set" in o && void 0 !== (r = o.set(e, n, t)) ? r : e[t] = n : o && "get" in o && null !== (r = o.get(e, t)) ? r : e[t]
                    }, propHooks : {
                        tabIndex : {
                            get : function(e) {
                                var t = S.find.attr(e, "tabindex");
                                return t ? parseInt(t, 10) : mt.test(e.nodeName) || gt.test(e.nodeName) && e.href ? 0 : -1
                            }
                        }
                    }, propFix   : {for : "htmlFor", class : "className"}
                }), g.optSelected || (S.propHooks.selected = {
                    get    : function(e) {
                        var t = e.parentNode;
                        return t && t.parentNode && t.parentNode.selectedIndex, null
                    }, set : function(e) {
                        var t = e.parentNode;
                        t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex)
                    }
                }), S.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], (function() {
                    S.propFix[this.toLowerCase()] = this
                })), S.fn.extend({
                    addClass       : function(e) {
                        var t, n, r, o, i, a, s, l = 0;
                        if (v(e)) return this.each((function(t) {
                            S(this).addClass(e.call(this, t, yt(this)))
                        }));
                        if ((t = bt(e)).length) for (; n = this[l++];) if (o = yt(n), r = 1 === n.nodeType && " " + vt(o) + " ") {
                            for (a = 0; i = t[a++];) r.indexOf(" " + i + " ") < 0 && (r += i + " ");
                            o !== (s = vt(r)) && n.setAttribute("class", s)
                        }
                        return this
                    }, removeClass : function(e) {
                        var t, n, r, o, i, a, s, l = 0;
                        if (v(e)) return this.each((function(t) {
                            S(this).removeClass(e.call(this, t, yt(this)))
                        }));
                        if (!arguments.length) return this.attr("class", "");
                        if ((t = bt(e)).length) for (; n = this[l++];) if (o = yt(n), r = 1 === n.nodeType && " " + vt(o) + " ") {
                            for (a = 0; i = t[a++];) for (; r.indexOf(" " + i + " ") > -1;) r = r.replace(" " + i + " ", " ");
                            o !== (s = vt(r)) && n.setAttribute("class", s)
                        }
                        return this
                    }, toggleClass : function(e, t) {
                        var n = typeof e, r = "string" === n || Array.isArray(e);
                        return "boolean" == typeof t && r ? t ? this.addClass(e) : this.removeClass(e) : v(e) ? this.each((function(n) {
                            S(this).toggleClass(e.call(this, n, yt(this), t), t)
                        })) : this.each((function() {
                            var t, o, i, a;
                            if (r) for (o = 0, i = S(this), a = bt(e); t = a[o++];) i.hasClass(t) ? i.removeClass(t) : i.addClass(t); else void 0 !== e && "boolean" !== n || ((t = yt(this)) && K.set(this, "__className__", t), this.setAttribute && this.setAttribute("class", t || !1 === e ? "" : K.get(this, "__className__") || ""))
                        }))
                    }, hasClass    : function(e) {
                        var t, n, r = 0;
                        for (t = " " + e + " "; n = this[r++];) if (1 === n.nodeType && (" " + vt(yt(n)) + " ").indexOf(t) > -1) return !0;
                        return !1
                    }
                });
                var xt = /\r/g;
                S.fn.extend({
                    val : function(e) {
                        var t, n, r, o = this[0];
                        return arguments.length ? (r = v(e), this.each((function(n) {
                            var o;
                            1 === this.nodeType && (null == (o = r ? e.call(this, n, S(this).val()) : e) ? o = "" : "number" == typeof o ? o += "" : Array.isArray(o) && (o = S.map(o, (function(e) {
                                return null == e ? "" : e + ""
                            }))), (t = S.valHooks[this.type] || S.valHooks[this.nodeName.toLowerCase()]) && "set" in t && void 0 !== t.set(this, o, "value") || (this.value = o))
                        }))) : o ? (t = S.valHooks[o.type] || S.valHooks[o.nodeName.toLowerCase()]) && "get" in t && void 0 !== (n = t.get(o, "value")) ? n : "string" == typeof(n = o.value) ? n.replace(xt, "") : null == n ? "" : n : void 0
                    }
                }), S.extend({
                    valHooks : {
                        option    : {
                            get : function(e) {
                                var t = S.find.attr(e, "value");
                                return null != t ? t : vt(S.text(e))
                            }
                        }, select : {
                            get    : function(e) {
                                var t, n, r, o = e.options, i = e.selectedIndex, a = "select-one" === e.type,
                                    s = a ? null : [], l = a ? i + 1 : o.length;
                                for (r = i < 0 ? l : a ? i : 0; r < l; r++) if (((n = o[r]).selected || r === i) && !n.disabled && (!n.parentNode.disabled || !D(n.parentNode, "optgroup"))) {
                                    if (t = S(n).val(), a) return t;
                                    s.push(t)
                                }
                                return s
                            }, set : function(e, t) {
                                for (var n, r, o = e.options, i = S.makeArray(t), a = o.length; a--;) ((r = o[a]).selected = S.inArray(S.valHooks.option.get(r), i) > -1) && (n = !0);
                                return n || (e.selectedIndex = -1), i
                            }
                        }
                    }
                }), S.each(["radio", "checkbox"], (function() {
                    S.valHooks[this] = {
                        set : function(e, t) {
                            if (Array.isArray(t)) return e.checked = S.inArray(S(e).val(), t) > -1
                        }
                    }, g.checkOn || (S.valHooks[this].get = function(e) {
                        return null === e.getAttribute("value") ? "on" : e.value
                    })
                })), g.focusin = "onfocusin" in r;
                var wt = /^(?:focusinfocus|focusoutblur)$/, Ct = function(e) {
                    e.stopPropagation()
                };
                S.extend(S.event, {
                    trigger     : function(e, t, n, o) {
                        var i, a, s, l, u, c, p, f, h = [n || b], m = d.call(e, "type") ? e.type : e,
                            g = d.call(e, "namespace") ? e.namespace.split(".") : [];
                        if (a = f = s = n = n || b, 3 !== n.nodeType && 8 !== n.nodeType && !wt.test(m + S.event.triggered) && (m.indexOf(".") > -1 && (g = m.split("."), m = g.shift(), g.sort()), u = m.indexOf(":") < 0 && "on" + m, (e = e[S.expando] ? e : new S.Event(m, "object" == typeof e && e)).isTrigger = o ? 2 : 3, e.namespace = g.join("."), e.rnamespace = e.namespace ? new RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, e.result = void 0, e.target || (e.target = n), t = null == t ? [e] : S.makeArray(t, [e]), p = S.event.special[m] || {}, o || !p.trigger || !1 !== p.trigger.apply(n, t))) {
                            if (!o && !p.noBubble && !y(n)) {
                                for (l = p.delegateType || m, wt.test(l + m) || (a = a.parentNode); a; a = a.parentNode) h.push(a), s = a;
                                s === (n.ownerDocument || b) && h.push(s.defaultView || s.parentWindow || r)
                            }
                            for (i = 0; (a = h[i++]) && !e.isPropagationStopped();) f = a, e.type = i > 1 ? l : p.bindType || m, (c = (K.get(a, "events") || Object.create(null))[e.type] && K.get(a, "handle")) && c.apply(a, t), (c = u && a[u]) && c.apply && Q(a) && (e.result = c.apply(a, t), !1 === e.result && e.preventDefault());
                            return e.type = m, o || e.isDefaultPrevented() || p._default && !1 !== p._default.apply(h.pop(), t) || !Q(n) || u && v(n[m]) && !y(n) && ((s = n[u]) && (n[u] = null), S.event.triggered = m, e.isPropagationStopped() && f.addEventListener(m, Ct), n[m](), e.isPropagationStopped() && f.removeEventListener(m, Ct), S.event.triggered = void 0, s && (n[u] = s)), e.result
                        }
                    }, simulate : function(e, t, n) {
                        var r = S.extend(new S.Event, n, {type : e, isSimulated : !0});
                        S.event.trigger(r, null, t)
                    }
                }), S.fn.extend({
                    trigger           : function(e, t) {
                        return this.each((function() {
                            S.event.trigger(e, t, this)
                        }))
                    }, triggerHandler : function(e, t) {
                        var n = this[0];
                        if (n) return S.event.trigger(e, t, n, !0)
                    }
                }), g.focusin || S.each({focus : "focusin", blur : "focusout"}, (function(e, t) {
                    var n = function(e) {
                        S.event.simulate(t, e.target, S.event.fix(e))
                    };
                    S.event.special[t] = {
                        setup       : function() {
                            var r = this.ownerDocument || this.document || this, o = K.access(r, t);
                            o || r.addEventListener(e, n, !0), K.access(r, t, (o || 0) + 1)
                        }, teardown : function() {
                            var r = this.ownerDocument || this.document || this, o = K.access(r, t) - 1;
                            o ? K.access(r, t, o) : (r.removeEventListener(e, n, !0), K.remove(r, t))
                        }
                    }
                }));
                var Tt = r.location, St = {guid : Date.now()}, Et = /\?/;
                S.parseXML = function(e) {
                    var t, n;
                    if (!e || "string" != typeof e) return null;
                    try {
                        t = (new r.DOMParser).parseFromString(e, "text/xml")
                    } catch (e) {
                    }
                    return n = t && t.getElementsByTagName("parsererror")[0], t && !n || S.error("Invalid XML: " + (n ? S.map(n.childNodes, (function(e) {
                        return e.textContent
                    })).join("\n") : e)), t
                };
                var kt = /\[\]$/, At = /\r?\n/g, jt = /^(?:submit|button|image|reset|file)$/i,
                    Nt = /^(?:input|select|textarea|keygen)/i;

                function Dt(e, t, n, r) {
                    var o;
                    if (Array.isArray(t)) S.each(t, (function(t, o) {
                        n || kt.test(e) ? r(e, o) : Dt(e + "[" + ("object" == typeof o && null != o ? t : "") + "]", o, n, r)
                    })); else if (n || "object" !== C(t)) r(e, t); else for (o in t) Dt(e + "[" + o + "]", t[o], n, r)
                }

                S.param = function(e, t) {
                    var n, r = [], o = function(e, t) {
                        var n = v(t) ? t() : t;
                        r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(null == n ? "" : n)
                    };
                    if (null == e) return "";
                    if (Array.isArray(e) || e.jquery && !S.isPlainObject(e)) S.each(e, (function() {
                        o(this.name, this.value)
                    })); else for (n in e) Dt(n, e[n], t, o);
                    return r.join("&")
                }, S.fn.extend({
                    serialize         : function() {
                        return S.param(this.serializeArray())
                    }, serializeArray : function() {
                        return this.map((function() {
                            var e = S.prop(this, "elements");
                            return e ? S.makeArray(e) : this
                        })).filter((function() {
                            var e = this.type;
                            return this.name && !S(this).is(":disabled") && Nt.test(this.nodeName) && !jt.test(e) && (this.checked || !ge.test(e))
                        })).map((function(e, t) {
                            var n = S(this).val();
                            return null == n ? null : Array.isArray(n) ? S.map(n, (function(e) {
                                return {name : t.name, value : e.replace(At, "\r\n")}
                            })) : {name : t.name, value : n.replace(At, "\r\n")}
                        })).get()
                    }
                });
                var Ht = /%20/g, Ot = /#.*$/, Lt = /([?&])_=[^&]*/, Rt = /^(.*?):[ \t]*([^\r\n]*)$/gm,
                    qt = /^(?:GET|HEAD)$/, Pt = /^\/\//, Mt = {}, It = {}, $t = "*/".concat("*"),
                    Bt = b.createElement("a");

                function Wt(e) {
                    return function(t, n) {
                        "string" != typeof t && (n = t, t = "*");
                        var r, o = 0, i = t.toLowerCase().match(I) || [];
                        if (v(n)) for (; r = i[o++];) "+" === r[0] ? (r = r.slice(1) || "*", (e[r] = e[r] || []).unshift(n)) : (e[r] = e[r] || []).push(n)
                    }
                }

                function Ft(e, t, n, r) {
                    var o = {}, i = e === It;

                    function a(s) {
                        var l;
                        return o[s] = !0, S.each(e[s] || [], (function(e, s) {
                            var u = s(t, n, r);
                            return "string" != typeof u || i || o[u] ? i ? !(l = u) : void 0 : (t.dataTypes.unshift(u), a(u), !1)
                        })), l
                    }

                    return a(t.dataTypes[0]) || !o["*"] && a("*")
                }

                function _t(e, t) {
                    var n, r, o = S.ajaxSettings.flatOptions || {};
                    for (n in t) void 0 !== t[n] && ((o[n] ? e : r || (r = {}))[n] = t[n]);
                    return r && S.extend(!0, e, r), e
                }

                Bt.href = Tt.href, S.extend({
                    active        : 0,
                    lastModified  : {},
                    etag          : {},
                    ajaxSettings  : {
                        url            : Tt.href,
                        type           : "GET",
                        isLocal        : /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(Tt.protocol),
                        global         : !0,
                        processData    : !0,
                        async          : !0,
                        contentType    : "application/x-www-form-urlencoded; charset=UTF-8",
                        accepts        : {
                            "*"  : $t,
                            text : "text/plain",
                            html : "text/html",
                            xml  : "application/xml, text/xml",
                            json : "application/json, text/javascript"
                        },
                        contents       : {xml : /\bxml\b/, html : /\bhtml/, json : /\bjson\b/},
                        responseFields : {xml : "responseXML", text : "responseText", json : "responseJSON"},
                        converters     : {
                            "* text"    : String,
                            "text html" : !0,
                            "text json" : JSON.parse,
                            "text xml"  : S.parseXML
                        },
                        flatOptions    : {url : !0, context : !0}
                    },
                    ajaxSetup     : function(e, t) {
                        return t ? _t(_t(e, S.ajaxSettings), t) : _t(S.ajaxSettings, e)
                    },
                    ajaxPrefilter : Wt(Mt),
                    ajaxTransport : Wt(It),
                    ajax          : function(e, t) {
                        "object" == typeof e && (t = e, e = void 0), t = t || {};
                        var n, o, i, a, s, l, u, c, p, f, d = S.ajaxSetup({}, t), h = d.context || d,
                            m = d.context && (h.nodeType || h.jquery) ? S(h) : S.event,
                            g = S.Deferred(), v = S.Callbacks("once memory"),
                            y = d.statusCode || {}, x = {}, w = {}, C = "canceled", T = {
                                readyState : 0, getResponseHeader : function(e) {
                                    var t;
                                    if (u) {
                                        if (!a) for (a = {}; t = Rt.exec(i);) a[t[1].toLowerCase() + " "] = (a[t[1].toLowerCase() + " "] || []).concat(t[2]);
                                        t = a[e.toLowerCase() + " "]
                                    }
                                    return null == t ? null : t.join(", ")
                                }, getAllResponseHeaders : function() {
                                    return u ? i : null
                                }, setRequestHeader : function(e, t) {
                                    return null == u && (e = w[e.toLowerCase()] = w[e.toLowerCase()] || e, x[e] = t), this
                                }, overrideMimeType : function(e) {
                                    return null == u && (d.mimeType = e), this
                                }, statusCode : function(e) {
                                    var t;
                                    if (e) if (u) T.always(e[T.status]); else for (t in e) y[t] = [y[t], e[t]];
                                    return this
                                }, abort : function(e) {
                                    var t = e || C;
                                    return n && n.abort(t), E(0, t), this
                                }
                            };
                        if (g.promise(T), d.url = ((e || d.url || Tt.href) + "").replace(Pt, Tt.protocol + "//"), d.type = t.method || t.type || d.method || d.type, d.dataTypes = (d.dataType || "*").toLowerCase().match(I) || [""], null == d.crossDomain) {
                            l = b.createElement("a");
                            try {
                                l.href = d.url, l.href = l.href, d.crossDomain = Bt.protocol + "//" + Bt.host != l.protocol + "//" + l.host
                            } catch (e) {
                                d.crossDomain = !0
                            }
                        }
                        if (d.data && d.processData && "string" != typeof d.data && (d.data = S.param(d.data, d.traditional)), Ft(Mt, d, t, T), u) return T;
                        for (p in(c = S.event && d.global) && 0 == S.active++ && S.event.trigger("ajaxStart"), d.type = d.type.toUpperCase(), d.hasContent = !qt.test(d.type), o = d.url.replace(Ot, ""), d.hasContent ? d.data && d.processData && 0 === (d.contentType || "").indexOf("application/x-www-form-urlencoded") && (d.data = d.data.replace(Ht, "+")) : (f = d.url.slice(o.length), d.data && (d.processData || "string" == typeof d.data) && (o += (Et.test(o) ? "&" : "?") + d.data, delete d.data), !1 === d.cache && (o = o.replace(Lt, "$1"), f = (Et.test(o) ? "&" : "?") + "_=" + St.guid++ + f), d.url = o + f), d.ifModified && (S.lastModified[o] && T.setRequestHeader("If-Modified-Since", S.lastModified[o]), S.etag[o] && T.setRequestHeader("If-None-Match", S.etag[o])), (d.data && d.hasContent && !1 !== d.contentType || t.contentType) && T.setRequestHeader("Content-Type", d.contentType), T.setRequestHeader("Accept", d.dataTypes[0] && d.accepts[d.dataTypes[0]] ? d.accepts[d.dataTypes[0]] + ("*" !== d.dataTypes[0] ? ", " + $t + "; q=0.01" : "") : d.accepts["*"]), d.headers) T.setRequestHeader(p, d.headers[p]);
                        if (d.beforeSend && (!1 === d.beforeSend.call(h, T, d) || u)) return T.abort();
                        if (C = "abort", v.add(d.complete), T.done(d.success), T.fail(d.error), n = Ft(It, d, t, T)) {
                            if (T.readyState = 1, c && m.trigger("ajaxSend", [T, d]), u) return T;
                            d.async && d.timeout > 0 && (s = r.setTimeout((function() {
                                T.abort("timeout")
                            }), d.timeout));
                            try {
                                u = !1, n.send(x, E)
                            } catch (e) {
                                if (u) throw e;
                                E(-1, e)
                            }
                        } else E(-1, "No Transport");

                        function E(e, t, a, l) {
                            var p, f, b, x, w, C = t;
                            u || (u = !0, s && r.clearTimeout(s), n = void 0, i = l || "", T.readyState = e > 0 ? 4 : 0, p = e >= 200 && e < 300 || 304 === e, a && (x = function(e, t, n) {
                                for (var r, o, i, a, s = e.contents, l = e.dataTypes; "*" === l[0];) l.shift(), void 0 === r && (r = e.mimeType || t.getResponseHeader("Content-Type"));
                                if (r) for (o in s) if (s[o] && s[o].test(r)) {
                                    l.unshift(o);
                                    break
                                }
                                if (l[0] in n) i = l[0]; else {
                                    for (o in n) {
                                        if (!l[0] || e.converters[o + " " + l[0]]) {
                                            i = o;
                                            break
                                        }
                                        a || (a = o)
                                    }
                                    i = i || a
                                }
                                if (i) return i !== l[0] && l.unshift(i), n[i]
                            }(d, T, a)), !p && S.inArray("script", d.dataTypes) > -1 && S.inArray("json", d.dataTypes) < 0 && (d.converters["text script"] = function() {
                            }), x = function(e, t, n, r) {
                                var o, i, a, s, l, u = {}, c = e.dataTypes.slice();
                                if (c[1]) for (a in e.converters) u[a.toLowerCase()] = e.converters[a];
                                for (i = c.shift(); i;) if (e.responseFields[i] && (n[e.responseFields[i]] = t), !l && r && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = i, i = c.shift()) if ("*" === i) i = l; else if ("*" !== l && l !== i) {
                                    if (!(a = u[l + " " + i] || u["* " + i])) for (o in u) if ((s = o.split(" "))[1] === i && (a = u[l + " " + s[0]] || u["* " + s[0]])) {
                                        !0 === a ? a = u[o] : !0 !== u[o] && (i = s[0], c.unshift(s[1]));
                                        break
                                    }
                                    if (!0 !== a) if (a && e.throws) t = a(t); else try {
                                        t = a(t)
                                    } catch (e) {
                                        return {
                                            state : "parsererror",
                                            error : a ? e : "No conversion from " + l + " to " + i
                                        }
                                    }
                                }
                                return {state : "success", data : t}
                            }(d, x, T, p), p ? (d.ifModified && ((w = T.getResponseHeader("Last-Modified")) && (S.lastModified[o] = w), (w = T.getResponseHeader("etag")) && (S.etag[o] = w)), 204 === e || "HEAD" === d.type ? C = "nocontent" : 304 === e ? C = "notmodified" : (C = x.state, f = x.data, p = !(b = x.error))) : (b = C, !e && C || (C = "error", e < 0 && (e = 0))), T.status = e, T.statusText = (t || C) + "", p ? g.resolveWith(h, [f, C, T]) : g.rejectWith(h, [T, C, b]), T.statusCode(y), y = void 0, c && m.trigger(p ? "ajaxSuccess" : "ajaxError", [T, d, p ? f : b]), v.fireWith(h, [T, C]), c && (m.trigger("ajaxComplete", [T, d]), --S.active || S.event.trigger("ajaxStop")))
                        }

                        return T
                    },
                    getJSON       : function(e, t, n) {
                        return S.get(e, t, n, "json")
                    },
                    getScript     : function(e, t) {
                        return S.get(e, void 0, t, "script")
                    }
                }), S.each(["get", "post"], (function(e, t) {
                    S[t] = function(e, n, r, o) {
                        return v(n) && (o = o || r, r = n, n = void 0), S.ajax(S.extend({
                            url      : e,
                            type     : t,
                            dataType : o,
                            data     : n,
                            success  : r
                        }, S.isPlainObject(e) && e))
                    }
                })), S.ajaxPrefilter((function(e) {
                    var t;
                    for (t in e.headers) "content-type" === t.toLowerCase() && (e.contentType = e.headers[t] || "")
                })), S._evalUrl = function(e, t, n) {
                    return S.ajax({
                        url        : e,
                        type       : "GET",
                        dataType   : "script",
                        cache      : !0,
                        async      : !1,
                        global     : !1,
                        converters : {
                            "text script" : function() {
                            }
                        },
                        dataFilter : function(e) {
                            S.globalEval(e, t, n)
                        }
                    })
                }, S.fn.extend({
                    wrapAll      : function(e) {
                        var t;
                        return this[0] && (v(e) && (e = e.call(this[0])), t = S(e, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && t.insertBefore(this[0]), t.map((function() {
                            for (var e = this; e.firstElementChild;) e = e.firstElementChild;
                            return e
                        })).append(this)), this
                    }, wrapInner : function(e) {
                        return v(e) ? this.each((function(t) {
                            S(this).wrapInner(e.call(this, t))
                        })) : this.each((function() {
                            var t = S(this), n = t.contents();
                            n.length ? n.wrapAll(e) : t.append(e)
                        }))
                    }, wrap      : function(e) {
                        var t = v(e);
                        return this.each((function(n) {
                            S(this).wrapAll(t ? e.call(this, n) : e)
                        }))
                    }, unwrap    : function(e) {
                        return this.parent(e).not("body").each((function() {
                            S(this).replaceWith(this.childNodes)
                        })), this
                    }
                }), S.expr.pseudos.hidden = function(e) {
                    return !S.expr.pseudos.visible(e)
                }, S.expr.pseudos.visible = function(e) {
                    return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length)
                }, S.ajaxSettings.xhr = function() {
                    try {
                        return new r.XMLHttpRequest
                    } catch (e) {
                    }
                };
                var zt = {0 : 200, 1223 : 204}, Ut = S.ajaxSettings.xhr();
                g.cors = !!Ut && "withCredentials" in Ut, g.ajax = Ut = !!Ut, S.ajaxTransport((function(e) {
                    var t, n;
                    if (g.cors || Ut && !e.crossDomain) return {
                        send     : function(o, i) {
                            var a, s = e.xhr();
                            if (s.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields) for (a in e.xhrFields) s[a] = e.xhrFields[a];
                            for (a in e.mimeType && s.overrideMimeType && s.overrideMimeType(e.mimeType), e.crossDomain || o["X-Requested-With"] || (o["X-Requested-With"] = "XMLHttpRequest"), o) s.setRequestHeader(a, o[a]);
                            t = function(e) {
                                return function() {
                                    t && (t = n = s.onload = s.onerror = s.onabort = s.ontimeout = s.onreadystatechange = null, "abort" === e ? s.abort() : "error" === e ? "number" != typeof s.status ? i(0, "error") : i(s.status, s.statusText) : i(zt[s.status] || s.status, s.statusText, "text" !== (s.responseType || "text") || "string" != typeof s.responseText ? {binary : s.response} : {text : s.responseText}, s.getAllResponseHeaders()))
                                }
                            }, s.onload = t(), n = s.onerror = s.ontimeout = t("error"), void 0 !== s.onabort ? s.onabort = n : s.onreadystatechange = function() {
                                4 === s.readyState && r.setTimeout((function() {
                                    t && n()
                                }))
                            }, t = t("abort");
                            try {
                                s.send(e.hasContent && e.data || null)
                            } catch (e) {
                                if (t) throw e
                            }
                        }, abort : function() {
                            t && t()
                        }
                    }
                })), S.ajaxPrefilter((function(e) {
                    e.crossDomain && (e.contents.script = !1)
                })), S.ajaxSetup({
                    accepts    : {script : "text/javascript, application/javascript, " + "application/ecmascript, application/x-ecmascript"},
                    contents   : {script : /\b(?:java|ecma)script\b/},
                    converters : {
                        "text script" : function(e) {
                            return S.globalEval(e), e
                        }
                    }
                }), S.ajaxPrefilter("script", (function(e) {
                    void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET")
                })), S.ajaxTransport("script", (function(e) {
                    var t, n;
                    if (e.crossDomain || e.scriptAttrs) return {
                        send     : function(r, o) {
                            t = S("<script>").attr(e.scriptAttrs || {}).prop({
                                charset : e.scriptCharset,
                                src     : e.url
                            }).on("load error", n = function(e) {
                                t.remove(), n = null, e && o("error" === e.type ? 404 : 200, e.type)
                            }), b.head.appendChild(t[0])
                        }, abort : function() {
                            n && n()
                        }
                    }
                }));
                var Xt, Vt = [], Gt = /(=)\?(?=&|$)|\?\?/;
                S.ajaxSetup({
                    jsonp : "callback", jsonpCallback : function() {
                        var e = Vt.pop() || S.expando + "_" + St.guid++;
                        return this[e] = !0, e
                    }
                }), S.ajaxPrefilter("json jsonp", (function(e, t, n) {
                    var o, i, a,
                        s = !1 !== e.jsonp && (Gt.test(e.url) ? "url" : "string" == typeof e.data && 0 === (e.contentType || "").indexOf("application/x-www-form-urlencoded") && Gt.test(e.data) && "data");
                    if (s || "jsonp" === e.dataTypes[0]) return o = e.jsonpCallback = v(e.jsonpCallback) ? e.jsonpCallback() : e.jsonpCallback, s ? e[s] = e[s].replace(Gt, "$1" + o) : !1 !== e.jsonp && (e.url += (Et.test(e.url) ? "&" : "?") + e.jsonp + "=" + o), e.converters["script json"] = function() {
                        return a || S.error(o + " was not called"), a[0]
                    }, e.dataTypes[0] = "json", i = r[o], r[o] = function() {
                        a = arguments
                    }, n.always((function() {
                        void 0 === i ? S(r).removeProp(o) : r[o] = i, e[o] && (e.jsonpCallback = t.jsonpCallback, Vt.push(o)), a && v(i) && i(a[0]), a = i = void 0
                    })), "script"
                })), g.createHTMLDocument = ((Xt = b.implementation.createHTMLDocument("").body).innerHTML = "<form></form><form></form>", 2 === Xt.childNodes.length), S.parseHTML = function(e, t, n) {
                    return "string" != typeof e ? [] : ("boolean" == typeof t && (n = t, t = !1), t || (g.createHTMLDocument ? ((r = (t = b.implementation.createHTMLDocument("")).createElement("base")).href = b.location.href, t.head.appendChild(r)) : t = b), i = !n && [], (o = H.exec(e)) ? [t.createElement(o[1])] : (o = Te([e], t, i), i && i.length && S(i).remove(), S.merge([], o.childNodes)));
                    var r, o, i
                }, S.fn.load = function(e, t, n) {
                    var r, o, i, a = this, s = e.indexOf(" ");
                    return s > -1 && (r = vt(e.slice(s)), e = e.slice(0, s)), v(t) ? (n = t, t = void 0) : t && "object" == typeof t && (o = "POST"), a.length > 0 && S.ajax({
                        url      : e,
                        type     : o || "GET",
                        dataType : "html",
                        data     : t
                    }).done((function(e) {
                        i = arguments, a.html(r ? S("<div>").append(S.parseHTML(e)).find(r) : e)
                    })).always(n && function(e, t) {
                        a.each((function() {
                            n.apply(this, i || [e.responseText, t, e])
                        }))
                    }), this
                }, S.expr.pseudos.animated = function(e) {
                    return S.grep(S.timers, (function(t) {
                        return e === t.elem
                    })).length
                }, S.offset = {
                    setOffset : function(e, t, n) {
                        var r, o, i, a, s, l, u = S.css(e, "position"), c = S(e), p = {};
                        "static" === u && (e.style.position = "relative"), s = c.offset(), i = S.css(e, "top"), l = S.css(e, "left"), ("absolute" === u || "fixed" === u) && (i + l).indexOf("auto") > -1 ? (a = (r = c.position()).top, o = r.left) : (a = parseFloat(i) || 0, o = parseFloat(l) || 0), v(t) && (t = t.call(e, n, S.extend({}, s))), null != t.top && (p.top = t.top - s.top + a), null != t.left && (p.left = t.left - s.left + o), "using" in t ? t.using.call(e, p) : c.css(p)
                    }
                }, S.fn.extend({
                    offset          : function(e) {
                        if (arguments.length) return void 0 === e ? this : this.each((function(t) {
                            S.offset.setOffset(this, e, t)
                        }));
                        var t, n, r = this[0];
                        return r ? r.getClientRects().length ? (t = r.getBoundingClientRect(), n = r.ownerDocument.defaultView, {
                            top  : t.top + n.pageYOffset,
                            left : t.left + n.pageXOffset
                        }) : {top : 0, left : 0} : void 0
                    }, position     : function() {
                        if (this[0]) {
                            var e, t, n, r = this[0], o = {top : 0, left : 0};
                            if ("fixed" === S.css(r, "position")) t = r.getBoundingClientRect(); else {
                                for (t = this.offset(), n = r.ownerDocument, e = r.offsetParent || n.documentElement; e && (e === n.body || e === n.documentElement) && "static" === S.css(e, "position");) e = e.parentNode;
                                e && e !== r && 1 === e.nodeType && ((o = S(e).offset()).top += S.css(e, "borderTopWidth", !0), o.left += S.css(e, "borderLeftWidth", !0))
                            }
                            return {
                                top  : t.top - o.top - S.css(r, "marginTop", !0),
                                left : t.left - o.left - S.css(r, "marginLeft", !0)
                            }
                        }
                    }, offsetParent : function() {
                        return this.map((function() {
                            for (var e = this.offsetParent; e && "static" === S.css(e, "position");) e = e.offsetParent;
                            return e || ae
                        }))
                    }
                }), S.each({scrollLeft : "pageXOffset", scrollTop : "pageYOffset"}, (function(e, t) {
                    var n = "pageYOffset" === t;
                    S.fn[e] = function(r) {
                        return U(this, (function(e, r, o) {
                            var i;
                            if (y(e) ? i = e : 9 === e.nodeType && (i = e.defaultView), void 0 === o) return i ? i[t] : e[r];
                            i ? i.scrollTo(n ? i.pageXOffset : o, n ? o : i.pageYOffset) : e[r] = o
                        }), e, r, arguments.length)
                    }
                })), S.each(["top", "left"], (function(e, t) {
                    S.cssHooks[t] = Ue(g.pixelPosition, (function(e, n) {
                        if (n) return n = ze(e, t), Be.test(n) ? S(e).position()[t] + "px" : n
                    }))
                })), S.each({Height : "height", Width : "width"}, (function(e, t) {
                    S.each({padding : "inner" + e, content : t, "" : "outer" + e}, (function(n, r) {
                        S.fn[r] = function(o, i) {
                            var a = arguments.length && (n || "boolean" != typeof o),
                                s = n || (!0 === o || !0 === i ? "margin" : "border");
                            return U(this, (function(t, n, o) {
                                var i;
                                return y(t) ? 0 === r.indexOf("outer") ? t["inner" + e] : t.document.documentElement["client" + e] : 9 === t.nodeType ? (i = t.documentElement, Math.max(t.body["scroll" + e], i["scroll" + e], t.body["offset" + e], i["offset" + e], i["client" + e])) : void 0 === o ? S.css(t, n, s) : S.style(t, n, o, s)
                            }), t, a ? o : void 0, a)
                        }
                    }))
                })), S.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], (function(e, t) {
                    S.fn[t] = function(e) {
                        return this.on(t, e)
                    }
                })), S.fn.extend({
                    bind          : function(e, t, n) {
                        return this.on(e, null, t, n)
                    }, unbind     : function(e, t) {
                        return this.off(e, null, t)
                    }, delegate   : function(e, t, n, r) {
                        return this.on(t, e, n, r)
                    }, undelegate : function(e, t, n) {
                        return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
                    }, hover      : function(e, t) {
                        return this.mouseenter(e).mouseleave(t || e)
                    }
                }), S.each(("blur focus focusin focusout resize scroll click dblclick " + "mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave " + "change select submit keydown keypress keyup contextmenu").split(" "), (function(e, t) {
                    S.fn[t] = function(e, n) {
                        return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
                    }
                }));
                var Yt = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                S.proxy = function(e, t) {
                    var n, r, o;
                    if ("string" == typeof t && (n = e[t], t = e, e = n), v(e)) return r = s.call(arguments, 2), o = function() {
                        return e.apply(t || this, r.concat(s.call(arguments)))
                    }, o.guid = e.guid = e.guid || S.guid++, o
                }, S.holdReady = function(e) {
                    e ? S.readyWait++ : S.ready(!0)
                }, S.isArray = Array.isArray, S.parseJSON = JSON.parse, S.nodeName = D, S.isFunction = v, S.isWindow = y, S.camelCase = Y, S.type = C, S.now = Date.now, S.isNumeric = function(e) {
                    var t = S.type(e);
                    return ("number" === t || "string" === t) && !isNaN(e - parseFloat(e))
                }, S.trim = function(e) {
                    return null == e ? "" : (e + "").replace(Yt, "")
                }, 1 && (void 0 === (n = function() {
                    return S
                }.apply(t, [])) || (e.exports = n));
                var Qt = r.jQuery, Jt = r.$;
                return S.noConflict = function(e) {
                    return r.$ === S && (r.$ = Jt), e && r.jQuery === S && (r.jQuery = Qt), S
                }, void 0 === o && (r.jQuery = r.$ = S), S
            }))
        }
    }, t  = {};

    function n(r) {
        var o = t[r];
        if (void 0 !== o) return o.exports;
        var i = t[r] = {exports : {}};
        return e[r].call(i.exports, i, i.exports, n), i.exports
    }

    n.n = e => {
        var t = e && e.__esModule ? () => e['default'] : () => e;
        return n.d(t, {a : t}), t
    }, n.d = (e, t) => {
        for (var r in t) n.o(t, r) && !n.o(e, r) && Object.defineProperty(e, r, {enumerable : !0, get : t[r]})
    }, n.o = (e, t) => Object.prototype.hasOwnProperty.call(e, t), n.r = e => {
        'undefined' != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value : 'Module'}), Object.defineProperty(e, '__esModule', {value : !0})
    };
    var r = {};
    return (() => {
        "use strict";

        function e(e, t, n) {
            return t in e ? Object.defineProperty(e, t, {
                value        : n,
                enumerable   : !0,
                configurable : !0,
                writable     : !0
            }) : e[t] = n, e
        }

        n.r(r), n.d(r, {default : () => j});
        var t = n(755), o = n.n(t);

        function i(e, t) {
            (null == t || t > e.length) && (t = e.length);
            for (var n = 0, r = new Array(t); n < t; n++) r[n] = e[n];
            return r
        }

        function a(e) {
            return function(e) {
                if (Array.isArray(e)) return i(e)
            }(e) || function(e) {
                if ("undefined" != typeof Symbol && null != e[Symbol.iterator] || null != e["@@iterator"]) return Array.from(e)
            }(e) || function(e, t) {
                if (e) {
                    if ("string" == typeof e) return i(e, t);
                    var n = Object.prototype.toString.call(e).slice(8, -1);
                    return "Object" === n && e.constructor && (n = e.constructor.name), "Map" === n || "Set" === n ? Array.from(e) : "Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n) ? i(e, t) : void 0
                }
            }(e) || function() {
                throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
            }()
        }

        var s = function() {
            var e = [{
                attributes : {class : 'column-actions columns-operations', title : 'Columns operations'},
                command    : 'table-show-columns-operations'
            }, {
                attributes : {class : 'row-actions rows-operations', title : 'Rows operations'},
                command    : 'table-show-rows-operations'
            }, {attributes : {class : 'fa fa-arrow-up', title : 'Select parent component'}, command : 'table-select'}];
            return (editor.getSelected().getAttributes()['colspan'] > 1 || editor.getSelected().getAttributes()['rowspan'] > 1) && e.push({
                attributes : {
                    class : 'fa fa fa-th-large',
                    title : 'Unmerge cells'
                },
                command    : 'table-unmerge-cells'
            }), e
        }, l  = function(e) {
            var t = e.get('toolbar');
            return t.find((function(e) {
                return 'open-traits-settings' === e.command
            })) || t.push({command : 'open-traits-settings', attributes : {class : 'fa fa-cog', title : 'Settings'}}), t
        };

        function u(e, t, n, r) {
            var o = arguments.length > 4 && void 0 !== arguments[4] && arguments[4];
            e.components().forEach((function(o, i) {
                0 === i && e.props().hasHeaders ? o.components().add({type : r}, {at : t}) : o.components().add({type : n}, {at : t})
            })), o && e.set({nColumns : Number(e.props().nColumns) + 1})
        }

        function c(e, t, n, r) {
            var o = arguments.length > 4 && void 0 !== arguments[4] && arguments[4];
            e.components().add({
                type       : n,
                components : a(Array(e.components().at(0).components().length).keys()).map((function() {
                    return {type : r}
                }))
            }, {at : t}), o && e.set({nRows : Number(e.props().nRows) + 1})
        }

        function p(e, t) {
            var n = arguments.length > 2 && void 0 !== arguments[2] && arguments[2];
            e.components().forEach((function(e) {
                e.components().at(t).remove()
            })), n && e.set({nColumns : Number(e.props().nColumns) - 1})
        }

        function f(e, t) {
            var n = arguments.length > 2 && void 0 !== arguments[2] && arguments[2];
            e.components().at(t).remove(), n && e.set({nRows : Number(e.props().nRows) - 1})
        }

        function d(e, t, n) {
            var r = arguments.length > 3 && void 0 !== arguments[3] && arguments[3],
                o = 0 == r ? e.props().hasHeaders : !e.props().hasHeaders;
            if (o) {
                for (var i = [], a = 0; a < e.props().nColumns; a++) i.push({type : n});
                e.components().add({type : t, components : i}, {at : 0})
            } else e.components().at(0).remove();
            r && e.set({hasHeaders : o})
        }

        function h(e) {
            var t = document.getElementById('nRows').value, n = document.getElementById('nColumns').value;
            if (t && n && t > 0 && n > 0) {
                var r = v(editor.getWrapper()).find((function(t) {
                    return t.cid == e
                }));
                r.props().nRows = t, r.props().nColumns = n, r.createTable()
            } else alert('Missing number of rows or number of columns.'), tableModel.remove();
            editor.Modal.close()
        }

        function m(e, t, n, r) {
            var o = editor.getSelected();
            if ($('ul#toolbar-submenu-' + e).length > 0) $('.toolbar-submenu').slideUp('slow'), $('ul#toolbar-submenu-' + e).slideDown('slow'); else if (o && (o.is(n) || o.is(r))) {
                var i = o.parent();
                if ($('.' + t + '-operations .toolbar-submenu').length > 0 && $('.' + t + '-operations .toolbar-submenu').slideUp('slow'), $('.' + e + '-operations .toolbar-submenu').length > 0) {
                    if ('none' != $('.' + e + '-operations .toolbar-submenu').css('display')) return void $('.' + e + '-operations .toolbar-submenu').slideUp('slow');
                    $('.' + e + '-operations .toolbar-submenu').slideDown('slow')
                } else {
                    var a = '';
                    if ('rows' === e) a = "<ul id=\"toolbar-submenu-rows\" class=\"toolbar-submenu " + ($('.gjs-toolbar').position().left > 150 ? 'toolbar-submenu-right' : '') + "\" style=\"display: none;\"><li class=\"table-toolbar-submenu-run-command\" data-command=\"table-insert-row-above\" " + (o.is(r) ? 'style="display: none;"' : '') + "><i class=\"fa fa-chevron-up\" aria-hidden=\"true\"></i> Insert row above</li><li class=\"table-toolbar-submenu-run-command\" data-command=\"table-insert-row-below\" ><i class=\"fa fa-chevron-down\" aria-hidden=\"true\"></i> Insert row below</li><li class=\"table-toolbar-submenu-run-command\" data-command=\"table-delete-row\" " + (o.is(r) ? 'style="display: none;"' : '') + " ><i class=\"fa fa-trash\" aria-hidden=\"true\"></i> Delete Row</li><li class=\"table-toolbar-submenu-run-command\" data-command=\"table-toggle-header\" " + (o.is(n) ? 'style="display: none;"' : '') + "><i class=\"fa fa-trash\" aria-hidden=\"true\"></i> Remove Header</li><li id=\"button-merge-cells-right\" class=\"table-toolbar-submenu-run-command\" data-command=\"table-merge-cells-right\" " + (o.collection.indexOf(o) + 1 == o.parent().components().length ? 'style="display: none;"' : '') + "><i class=\"fa fa-arrows-h\" aria-hidden=\"true\"></i> Merge cell right</li></ul>"; else {
                        var s = o.getAttributes()['rowspan'] ? o.getAttributes()['rowspan'] : 0;
                        a = "<ul id=\"toolbar-submenu-columns\" class=\"toolbar-submenu " + ($('.gjs-toolbar').position().left > 150 ? 'toolbar-submenu-right' : '') + "\" style=\"display: none;\"><li class=\"table-toolbar-submenu-run-command\" data-command=\"table-insert-column-left\" ><i class=\"fa fa-chevron-left\" aria-hidden=\"true\"></i> Insert column left</li><li class=\"table-toolbar-submenu-run-command\" data-command=\"table-insert-column-right\" ><i class=\"fa fa-chevron-right\" aria-hidden=\"true\"></i> Insert column right</li><li class=\"table-toolbar-submenu-run-command\" data-command=\"table-delete-column\" ><i class=\"fa fa-trash\" aria-hidden=\"true\"></i> Delete column</li><li id=\"button-merge-cells-down\" class=\"table-toolbar-submenu-run-command\" data-command=\"table-merge-cells-down\" " + (i.collection.indexOf(i) + s == i.parent().components().length || o.is(r) ? 'style="display: none;"' : '') + "><i class=\"fa fa-arrows-v\" aria-hidden=\"true\"></i> Merge cell down</li></ul>"
                    }
                    $('.toolbar-submenu').slideUp('slow'), $('.' + e + '-operations').parent().append(a), $('ul#toolbar-submenu-' + e).slideDown('slow')
                }
            }
        }

        function g() {
            var e = editor.getSelected();
            editor.selectRemove(e), setTimeout((function() {
                editor.select(e)
            }), 50)
        }

        function v(e) {
            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : [];
            return t.push(e), e.components().each((function(e) {
                return v(e, t)
            })), t
        }

        const y = function(e) {
            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
            o()((function() {
                var n = ".column-actions {background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\"><path stroke=\"%23ffffff\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 3h3M3 21h3m0 0h4a2 2 0 0 0 2-2V9M6 21V9m0-6h4a2 2 0 0 1 2 2v4M6 3v6M3 9h3m0 0h6m-9 6h9m3-3h3m0 0h3m-3 0v3m0-3V9\"/></svg>');background-size: cover;background-repeat: no-repeat;content: '';background-size: 23px 23px;height: 23px;width: 23px;margin: 2px 3px;}.table-toolbar-submenu-run-command {margin: 2px 3px;padding: 2px;cursor: pointer;}.row-actions {background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\"><path stroke=\"%23ffffff\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M3 3v3m18-3v3m0 0v4a2 2 0 0 1-2 2H9m12-6H9M3 6v4a2 2 0 0 0 2 2h4M3 6h6m0-3v3m0 0v6m6-9v9m-3 3v3m0 0v3m0-3h3m-3 0H9\"/></svg>');background-size: cover;background-repeat: no-repeat;content: '';background-size: 23px 23px;height: 23px;width: 23px;margin: 2px 3px;}.new-table-form label { min-width: 160px;display: inline-block;}.new-table-form .form-control { padding: 3px 5px;margin-bottom: 10px;}#table-button-create-new { margin-top:10px}.table-cell-highlight {background-color: #ffcccc !important;}",
                    r = document.head || document.getElementsByTagName('head')[0], i = document.createElement('style');
                r.appendChild(i), i.type = 'text/css', i.styleSheet ? i.styleSheet.cssText = n : i.appendChild(document.createTextNode(n)), o()(t.containerId ? t.containerId : document).on('click', 'li.table-toolbar-submenu-run-command', (function() {
                    e.runCommand(this.dataset.command)
                })), o()(t.containerId ? t.containerId : document).on('click', 'input#table-button-create-new', (function() {
                    h(this.dataset.componentId)
                }))
            })), e.on('component:add', (function(t) {
                'customTable' === t.attributes.type && 0 == t.components().length && e.runCommand('open-table-settings-modal', {model : t})
            })), e.on('component:selected', (function(e) {
                e.get('type') != t.componentCell && e.get('type') != t.componentCellHeader || e.set('toolbar', s()), 'customTable' == e.get('type') && e.set('toolbar', l(e))
            }))
        }, b    = function(e, t) {
            var n = t.tblResizable;
            e.addType('customTable', {
                isComponent : function(e) {
                    return 'TABLE' === e.tagName
                },
                model       : {
                    defaults              : {
                        nRows      : 4,
                        nColumns   : 3,
                        hasHeaders : !1,
                        tagName    : 'table',
                        droppable  : t.componentRow,
                        resizable  : n,
                        traits     : [{
                            type       : 'number',
                            name       : 'nColumns',
                            label      : 'Number of Columns',
                            min        : 1,
                            changeProp : 1
                        }, {
                            type       : 'number',
                            name       : 'nRows',
                            label      : 'Number of Rows',
                            min        : 1,
                            changeProp : 1
                        }, {
                            type       : 'checkbox',
                            name       : 'hasHeaders',
                            label      : 'Enable headers',
                            changeProp : 1
                        }, {
                            type    : 'button',
                            label   : !1,
                            name    : 'highlightCells',
                            text    : 'Toggle highlight cells with size',
                            command : function(e) {
                                e.getSelected().components().forEach((function(e) {
                                    e.components().forEach((function(e) {
                                        var t = e.getStyle();
                                        t && (t.width || t.height) && (e.getClasses().includes('table-cell-highlight') ? e.removeClass('table-cell-highlight') : e.addClass('table-cell-highlight'))
                                    }))
                                }))
                            }
                        }, {
                            type    : 'button',
                            label   : !1,
                            name    : 'highlightCellsRemove',
                            text    : 'Clear all cells width and height',
                            command : function(e) {
                                e.getSelected().components().forEach((function(e) {
                                    e.components().forEach((function(e) {
                                        var t = e.getStyle();
                                        t && (t.width && e.removeStyle('width'), t.height && e.removeStyle('height'), e.getClasses().includes('table-cell-highlight') && e.removeClass('table-cell-highlight'))
                                    }))
                                }))
                            }
                        }]
                    }, init               : function() {
                        this.listenTo(this, 'change:nColumns', this.columnsChanged), this.listenTo(this, 'change:nRows', this.rowsChanged), this.listenTo(this, 'change:hasHeaders', this.headerChanged)
                    }, createTable        : function() {
                        var e = 46 * Number(this.props().nColumns), n = e < 900 ? e : 900,
                            r = 22 * Number(this.props().nRows);
                        this.setStyle({width : n + 'px', height : r + 'px'});
                        for (var o = [], i = this.props().hasHeader, a = [], s = 0; s < this.props().nColumns; s++) o.push({type : t.componentCell});
                        for (var l = 0; l < this.props().nRows; l++) this.components().add({
                            type       : t.componentRow,
                            components : o
                        }, {at : -1});
                        if (i) {
                            for (var u = 0; u < this.props().nColumns; u++) a.push({type : t.componentCellHeader});
                            this.components().add({type : t.componentRow, components : a}, {at : 0})
                        }
                    }, columnsChanged     : function(e, n, r) {
                        if (this.columnCount() !== n) {
                            var o = n - this.columnCount(), i = Math.abs(n - this.columnCount());
                            if (o < 0) for (var a = 0; a < i; a++) p(this, this.getLastColumnIndex()); else for (var s = 0; s < i; s++) u(this, this.columnCount(), t.componentCell, t.componentCellHeader)
                        }
                    }, rowsChanged        : function(e, n, r) {
                        if (this.rowCount() !== n) {
                            var o = n - this.rowCount(), i = Math.abs(n - this.rowCount());
                            if (o < 0) for (var a = 0; a < i; a++) f(this, this.getLastRowIndex()); else for (var s = 0; s < i; s++) c(this, this.rowCount(), t.componentRow, t.componentCell)
                        }
                    }, headerChanged      : function(e, n, r) {
                        this.checkHeaderExists() != this.props().hasHeaders && d(this, t.componentRow, t.componentCellHeader)
                    }, checkHeaderExists  : function() {
                        return this.components().at(0).components().at(0).is(t.componentCellHeader)
                    }, hasChildren        : function() {
                        return this.components().length > 0
                    }, rowCount           : function() {
                        var e = this.components().length;
                        return this.components().at(0).components().at(0).is(t.componentCellHeader) && e--, e
                    }, columnCount        : function() {
                        return this.components().at(0).components().length
                    }, getLastRowIndex    : function() {
                        return this.rowCount() - 1
                    }, getLastColumnIndex : function() {
                        return this.columnCount() - 1
                    }
                }
            })
        }, x    = function(e, t) {
            e.addType(t.componentRow, {
                isComponent : function(e) {
                    return 'TR' === e.tagName
                },
                model       : {
                    defaults : {
                        name      : 'Row',
                        tagName   : 'tr',
                        droppable : t.componentCell + ',' + t.componentCellHeader,
                        draggable : !1,
                        classes   : []
                    }
                }
            })
        }, w    = function(e, t) {
            var n = t.cellsResizable;
            e.addType(t.componentCellHeader, {
                isComponent : function(e) {
                    return 'TH' === e.tagName
                },
                model       : {
                    defaults : {
                        name      : 'Header Cell',
                        tagName   : 'th',
                        draggable : !1,
                        removable : !1,
                        resizable : n,
                        classes   : []
                    }
                },
                view        : {
                    onRender : function() {
                        var e = this;
                        o()(this.$el).dblclick((function() {
                            0 === o()(this).children().length && e.model.components().add({
                                type    : 'text',
                                content : 'Text'
                            })
                        }))
                    }
                }
            })
        }, C    = function(e, t) {
            var n = t.cellsResizable;
            e.addType(t.componentCell, {
                isComponent : function(e) {
                    return 'TD' === e.tagName
                },
                model       : {
                    defaults : {
                        name      : 'Cell',
                        tagName   : 'td',
                        draggable : !1,
                        removable : !1,
                        resizable : n,
                        classes   : []
                    }
                },
                view        : {
                    onRender : function() {
                        var e = this;
                        o()(this.$el).dblclick((function() {
                            0 === o()(this).children().length && e.model.components().add({
                                type    : 'text',
                                content : 'Text'
                            })
                        }))
                    }
                }
            })
        }, T    = function(e) {
            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {}, n = e.DomComponents;
            b(n, t), x(n, t), w(n, t), C(n, t)
        }, S    = function(e) {
            var t = e.BlockManager;
            t.add('table-block', {
                label      : 'Table',
                category   : 'Basic',
                attributes : {class : 'fa fa-table'},
                content    : {type : 'customTable'},
                activate   : !0
            })
        }, E    = function(e) {
            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {}, n = e.Commands;
            n.add('table-show-columns-operations', (function() {
                m('columns', 'rows', t.componentCell, t.componentCellHeader)
            })), n.add('table-show-rows-operations', (function() {
                m('rows', 'columns', t.componentCell, t.componentCellHeader)
            })), n.add('table-toggle-header', (function() {
                var n = e.getSelected();
                n.is(t.componentCellHeader) && d(n.parent().parent(), t.componentRow, t.componentCellHeader, !0)
            })), n.add('table-select', (function(e) {
                e.runCommand('core:component-exit'), e.runCommand('core:component-exit')
            })), n.add('table-insert-row-above', (function(e) {
                var n = e.getSelected();
                if (n.is(t.componentCell)) {
                    var r = n.parent();
                    c(n.parent().parent(), r.collection.indexOf(r), t.componentRow, t.componentCell, !0), g()
                }
            })), n.add('table-insert-row-below', (function(e) {
                var n = e.getSelected();
                if (n.is(t.componentCell) || n.is(t.componentCellHeader)) {
                    var r = n.parent();
                    c(n.parent().parent(), r.collection.indexOf(r) + 1, t.componentRow, t.componentCell, !0), g()
                }
            })), n.add('table-insert-column-left', (function(e) {
                var n = e.getSelected();
                (n.is(t.componentCell) || n.is(t.componentCellHeader)) && (u(n.parent().parent(), n.collection.indexOf(n), t.componentCell, t.componentCellHeader, !0), g())
            })), n.add('table-insert-column-right', (function(e) {
                var n = e.getSelected();
                (n.is(t.componentCell) || n.is(t.componentCellHeader)) && (u(n.parent().parent(), n.collection.indexOf(n) + 1, t.componentCell, t.componentCellHeader, !0), g())
            })), n.add('table-delete-row', (function(e) {
                var n = e.getSelected();
                if (n.is(t.componentCell) || n.is(t.componentCellHeader)) {
                    var r = n.parent().parent();
                    e.selectRemove(n);
                    var o = n.parent();
                    f(r, o.collection.indexOf(o), !0), 0 === r.components().length && r.parent().remove(r)
                }
            })), n.add('table-delete-column', (function(e) {
                var n = e.getSelected();
                if (n.is(t.componentCell) || n.is(t.componentCellHeader)) {
                    var r = n.parent().parent(), o = n.collection.indexOf(n);
                    e.selectRemove(n), p(r, o, !0), r.components().every((function(e) {
                        return 0 === e.components().length
                    })) && r.parent().remove(r)
                }
            })), n.add('table-merge-cells-right', (function(e) {
                var n = e.getSelected();
                if (n.is(t.componentCell) || n.is(t.componentCellHeader)) {
                    for (var r = n.getAttributes()['colspan'] ? n.getAttributes()['colspan'] : 1, i = n.collection.indexOf(n), a = n.parent().collection.indexOf(n.parent()), s = n.parent().parent(), l = n.getAttributes()['rowspan'] > 0 ? n.getAttributes()['rowspan'] : 1, u = 0; u < l; u++) {
                        var c = s.components().at(a + u).components().at(0 === u ? i + 1 : i);
                        c.components().forEach((function(e) {
                            n.append(e.toHTML())
                        })), c.remove()
                    }
                    n.addAttributes({colspan : ++r}), g(), i + 1 == n.parent().components().length && o()('#button-merge-cells-right').hide()
                }
            })), n.add('table-merge-cells-down', (function(e) {
                var n = e.getSelected();
                if (n.is(t.componentCell)) {
                    for (var r = n.getAttributes()['colspan'] ? n.getAttributes()['colspan'] : 1, i = n.getAttributes()['rowspan'] ? n.getAttributes()['rowspan'] : 1, a = n.collection.indexOf(n), s = n.parent().collection.indexOf(n.parent()) + i - 1, l = n.parent().parent(), u = 0; u < r; u++) {
                        var c = l.components().at(s + 1).components().at(a);
                        c.components().forEach((function(e) {
                            n.append(e.toHTML())
                        })), c.remove()
                    }
                    n.addAttributes({rowspan : ++i}), g(), s + 2 == l.components().length && o()('#button-merge-cells-down').hide()
                }
            })), n.add('table-unmerge-cells', (function(e) {
                var n = e.getSelected();
                if (n.is(t.componentCell) || n.is(t.componentCellHeader)) {
                    for (var r = n.getAttributes()['colspan'] ? n.getAttributes()['colspan'] : 1, i = n.getAttributes()['rowspan'] ? n.getAttributes()['rowspan'] : 1, a = n.collection.indexOf(n), s = n.parent().collection.indexOf(n.parent()), l = n.parent().parent(), u = 0; u < i; u++) for (var c = 0; c < r; c++) 0 === u && 1 === r || (0 === u && 0 === c && r > 1 && (c = 1), l.components().at(s + u).components().add({type : t.componentCell}, {at : 0 === u ? a + 1 : a}));
                    n.setAttributes({colspan : 1}), n.setAttributes({rowspan : 1}), g(), o()('#button-merge-cells-down').show(), o()('#button-merge-cells-right').show()
                }
            })), n.add('open-traits-settings', {
                run : function(e, t) {
                    e.Panels.getButton('views', 'open-tm').set('active', !0)
                }
            }), n.add('open-table-settings-modal', {
                run     : function(e, t) {
                    var n = this, r = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};
                    e.Modal.open({
                        title   : 'Create new Table',
                        content : "<div class=\"new-table-form\"><div><label for=\"nColumns\">Number of columns</label><input type=\"number\" class=\"form-control\" value=\"" + r.model.props()['nColumns'] + "\" name=\"nColumns\" id=\"nColumns\" min=\"1\"></div><div><label for=\"nRows\">Number of rows</label><input type=\"number\" class=\"form-control\" value=\"" + r.model.props()['nRows'] + "\" name=\"nRows\" id=\"nRows\" min=\"1\"></div><div><input id=\"table-button-create-new\" type=\"button\" value=\"Create Table\" data-component-id=\"" + r.model.cid + "\">"
                    }).onceClose((function() {
                        r.model.components() && 0 !== r.model.components().length || r.model.remove(), n.stopCommand()
                    }))
                }, stop : function(e) {
                    e.Modal.close()
                }
            })
        };

        function k(e, t) {
            var n = Object.keys(e);
            if (Object.getOwnPropertySymbols) {
                var r = Object.getOwnPropertySymbols(e);
                t && (r = r.filter((function(t) {
                    return Object.getOwnPropertyDescriptor(e, t).enumerable
                }))), n.push.apply(n, r)
            }
            return n
        }

        function A(t) {
            for (var n = 1; n < arguments.length; n++) {
                var r = null != arguments[n] ? arguments[n] : {};
                n % 2 ? k(Object(r), !0).forEach((function(n) {
                    e(t, n, r[n])
                })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(r)) : k(Object(r)).forEach((function(e) {
                    Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(r, e))
                }))
            }
            return t
        }

        const j = function(e) {
            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {}, n = {
                tblResizable        : !0,
                cellsResizable      : !0,
                componentCell       : 'customCell',
                componentCellHeader : 'customHeaderCell',
                componentRow        : 'customRow'
            }, r  = A(A({}, n), t);
            y(e, r), T(e, r), S(e, r), E(e, r)
        }
    })(), r
})()));

tinymce.ThemeManager.add ( "modern", function ( i ) {
    var o = this, f = i.settings, c = tinymce.ui.Factory, m = tinymce.each, n = tinymce.DOM;
    var d = {
        file   : { title : "File", items : "newdocument" },
        edit   : { title : "Edit", items : "undo redo | cut copy paste pastetext | selectall" },
        insert : { title : "Insert", items : "|" },
        view   : { title : "View", items : "visualaid |" },
        format : {
            title : "Format",
            items : "bold italic underline strikethrough superscript subscript | formats | removeformat"
        },
        table  : { title : "Table" },
        tools  : { title : "Tools" }
    };
    var b = "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image";

    function l () {
        var q = [];

        function r ( t ) {
            var u = [], s;
            if ( !t ) {
                return
            }
            m ( t.split ( /[ ,]/ ), function ( w ) {
                var x;

                function v () {
                    var y = i.selection;
                    if ( x == "bullist" ) {
                        y.selectorChanged ( "ul > li", function ( B, z ) {
                            var C, A = z.parents.length;
                            while ( A-- ) {
                                C = z.parents[ A ].nodeName;
                                if ( C == "OL" || C == "UL" ) {
                                    break
                                }
                            }
                            w.active ( B && C == "UL" )
                        } )
                    }
                    if ( x == "numlist" ) {
                        y.selectorChanged ( "ol > li", function ( B, z ) {
                            var C, A = z.parents.length;
                            while ( A-- ) {
                                C = z.parents[ A ].nodeName;
                                if ( C == "OL" || C == "UL" ) {
                                    break
                                }
                            }
                            w.active ( B && C == "OL" )
                        } )
                    }
                    if ( w.settings.stateSelector ) {
                        y.selectorChanged ( w.settings.stateSelector, function ( z ) {
                            w.active ( z )
                        }, true )
                    }
                    if ( w.settings.disabledStateSelector ) {
                        y.selectorChanged ( w.settings.disabledStateSelector, function ( z ) {
                            w.disabled ( z )
                        } )
                    }
                }

                if ( w == "|" ) {
                    s = null
                } else {
                    if ( c.has ( w ) ) {
                        w = { type : w };
                        if ( f.toolbar_items_size ) {
                            w.size = f.toolbar_items_size
                        }
                        u.push ( w );
                        s = null
                    } else {
                        if ( !s ) {
                            s = { type : "buttongroup", items : [] };
                            u.push ( s )
                        }
                        if ( i.buttons[ w ] ) {
                            x = w;
                            w = i.buttons[ x ];
                            if ( typeof w == "function" ) {
                                w = w ()
                            }
                            w.type = w.type || "button";
                            if ( f.toolbar_items_size ) {
                                w.size = f.toolbar_items_size
                            }
                            w = c.create ( w );
                            s.items.push ( w );
                            if ( i.initialized ) {
                                v ()
                            } else {
                                i.on ( "init", v )
                            }
                        }
                    }
                }
            } );
            q.push ( { type : "toolbar", layout : "flow", items : u } );
            return true
        }

        if ( tinymce.isArray ( f.toolbar ) ) {
            if ( f.toolbar.length === 0 ) {
                return
            }
            tinymce.each ( f.toolbar, function ( t, s ) {
                f[ "toolbar" + (s + 1) ] = t
            } );
            delete f.toolbar
        }
        for ( var p = 1; p < 10; p++ ) {
            if ( !r ( f[ "toolbar" + p ] ) ) {
                break
            }
        }
        if ( !q.length && f.toolbar !== false ) {
            r ( f.toolbar || b )
        }
        if ( q.length ) {
            return {
                type         : "panel",
                layout       : "stack",
                classes      : "toolbar-grp",
                ariaRoot     : true,
                ariaRemember : true,
                items        : q
            }
        }
    }

    function k () {
        var r, w = [];

        function u ( x ) {
            var y;
            if ( x == "|" ) {
                return { text : "|" }
            }
            y = i.menuItems[ x ];
            return y
        }

        function p ( B ) {
            var x, C, A, D, z;
            z = tinymce.makeMap ( (f.removed_menuitems || "").split ( /[ ,]/ ) );
            if ( f.menu ) {
                C = f.menu[ B ];
                D = true
            } else {
                C = d[ B ]
            }
            if ( C ) {
                x = { text : C.title };
                A = [];
                m ( (C.items || "").split ( /[ ,]/ ), function ( F ) {
                    var E = u ( F );
                    if ( E && !z[ F ] ) {
                        A.push ( u ( F ) )
                    }
                } );
                if ( !D ) {
                    m ( i.menuItems, function ( E ) {
                        if ( E.context == B ) {
                            if ( E.separator == "before" ) {
                                A.push ( { text : "|" } )
                            }
                            if ( E.prependToContext ) {
                                A.unshift ( E )
                            } else {
                                A.push ( E )
                            }
                            if ( E.separator == "after" ) {
                                A.push ( { text : "|" } )
                            }
                        }
                    } )
                }
                for ( var y = 0; y < A.length; y++ ) {
                    if ( A[ y ].text == "|" ) {
                        if ( y === 0 || y == A.length - 1 ) {
                            A.splice ( y, 1 )
                        }
                    }
                }
                x.menu = A;
                if ( !x.menu.length ) {
                    return null
                }
            }
            return x
        }

        var q = [];
        if ( f.menu ) {
            for ( r in f.menu ) {
                q.push ( r )
            }
        } else {
            for ( r in d ) {
                q.push ( r )
            }
        }
        var t = typeof f.menubar == "string" ? f.menubar.split ( /[ ,]/ ) : q;
        for ( var s = 0; s < t.length; s++ ) {
            var v = t[ s ];
            v     = p ( v );
            if ( v ) {
                w.push ( v )
            }
        }
        return w
    }

    function g ( q ) {
        function p ( r ) {
            var s = q.find ( r )[ 0 ];
            if ( s ) {
                s.focus ( true )
            }
        }

        i.shortcuts.add ( "Alt+F9", "", function () {
            p ( "menubar" )
        } );
        i.shortcuts.add ( "Alt+F10", "", function () {
            p ( "toolbar" )
        } );
        i.shortcuts.add ( "Alt+F11", "", function () {
            p ( "elementpath" )
        } );
        q.on ( "cancel", function () {
            i.focus ()
        } )
    }

    function h ( r, q ) {
        var v, p, t, u;

        function s ( w ) {
            return { width : w.clientWidth, height : w.clientHeight }
        }

        v = i.getContainer ();
        p = i.getContentAreaContainer ().firstChild;
        t = s ( v );
        u = s ( p );
        if ( r !== null ) {
            r = Math.max ( f.min_width || 100, r );
            r = Math.min ( f.max_width || 65535, r );
            n.setStyle ( v, "width", r + (t.width - u.width) );
            n.setStyle ( p, "width", r )
        }
        q = Math.max ( f.min_height || 100, q );
        q = Math.min ( f.max_height || 65535, q );
        n.setStyle ( p, "height", q );
        i.fire ( "ResizeEditor" )
    }

    function e ( p, q ) {
        var r = i.getContentAreaContainer ();
        o.resizeTo ( r.clientWidth + p, r.clientHeight + q )
    }

    function a ( s ) {
        var r, v;
        if ( f.fixed_toolbar_container ) {
            v = n.select ( f.fixed_toolbar_container )[ 0 ]
        }
        function q () {
            if ( r && r.moveRel && r.visible () && !r._fixed ) {
                var A = i.selection.getScrollContainer (), x = i.getBody ();
                var y = 0, w = 0;
                if ( A ) {
                    var B = n.getPos ( x ), z = n.getPos ( A );
                    y     = Math.max ( 0, z.x - B.x );
                    w     = Math.max ( 0, z.y - B.y )
                }
                r.fixed ( false ).moveRel ( x, i.rtl ? [ "tr-br", "br-tr" ] : [ "tl-bl", "bl-tl", "tr-br" ] ).moveBy ( y, w )
            }
        }

        function p () {
            if ( r ) {
                r.show ();
                q ();
                n.addClass ( i.getBody (), "mce-edit-focus" )
            }
        }

        function t () {
            if ( r ) {
                r.hide ();
                r.hideAll ();
                n.removeClass ( i.getBody (), "mce-edit-focus" )
            }
        }

        function u () {
            if ( r ) {
                if ( !r.visible () ) {
                    p ()
                }
                return
            }
            r = o.panel = c.create ( {
                type      : v ? "panel" : "floatpanel",
                role      : "application",
                classes   : "tinymce tinymce-inline",
                layout    : "flex",
                direction : "column",
                align     : "stretch",
                autohide  : false,
                autofix   : true,
                fixed     : !!v,
                border    : 1,
                items     : [ f.menubar === false ? null : {
                    type   : "menubar",
                    border : "0 0 1 0",
                    items  : k ()
                }, l () ]
            } );
            i.fire ( "BeforeRenderUI" );
            r.renderTo ( v || document.body ).reflow ();
            g ( r );
            p ();
            i.on ( "nodeChange", q );
            i.on ( "activate", p );
            i.on ( "deactivate", t );
            i.nodeChanged ()
        }

        f.content_editable = true;
        i.on ( "focus", function () {
            if ( s.skinUiCss ) {
                tinymce.DOM.styleSheetLoader.load ( s.skinUiCss, u, u )
            } else {
                u ()
            }
        } );
        i.on ( "blur hide", t );
        i.on ( "remove", function () {
            if ( r ) {
                r.remove ();
                r = null
            }
        } );
        if ( s.skinUiCss ) {
            tinymce.DOM.styleSheetLoader.load ( s.skinUiCss )
        }
        return {}
    }

    function j ( r ) {
        var q, s, p;
        if ( r.skinUiCss ) {
            tinymce.DOM.loadCSS ( r.skinUiCss )
        }
        q = o.panel = c.create ( {
            type    : "panel",
            role    : "application",
            classes : "tinymce",
            style   : "visibility: hidden",
            layout  : "stack",
            border  : 1,
            items   : [ f.menubar === false ? null : {
                type   : "menubar",
                border : "0 0 1 0",
                items  : k ()
            }, l (), {
                type    : "panel",
                name    : "iframe",
                layout  : "stack",
                classes : "edit-area",
                html    : "",
                border  : "1 0 0 0"
            } ]
        } );
        if ( f.resize !== false ) {
            s = {
                type        : "resizehandle", direction : f.resize, onResizeStart : function () {
                    var t = i.getContentAreaContainer ().firstChild;
                    p     = { width : t.clientWidth, height : t.clientHeight }
                }, onResize : function ( t ) {
                    if ( f.resize == "both" ) {
                        h ( p.width + t.deltaX, p.height + t.deltaY )
                    } else {
                        h ( null, p.height + t.deltaY )
                    }
                }
            }
        }
        if ( f.statusbar !== false ) {
            q.add ( {
                type     : "panel",
                name     : "statusbar",
                classes  : "statusbar",
                layout   : "flow",
                border   : "1 0 0 0",
                ariaRoot : true,
                items    : [ { type : "elementpath" }, s ]
            } )
        }
        if ( f.readonly ) {
            q.find ( "*" ).disabled ( true )
        }
        i.fire ( "BeforeRenderUI" );
        q.renderBefore ( r.targetNode ).reflow ();
        if ( f.width ) {
            tinymce.DOM.setStyle ( q.getEl (), "width", f.width )
        }
        i.on ( "remove", function () {
            q.remove ();
            q = null
        } );
        g ( q );
        return { iframeContainer : q.find ( "#iframe" )[ 0 ].getEl (), editorContainer : q.getEl () }
    }

    o.renderUI = function ( q ) {
        var r = f.skin !== false ? f.skin || "lightgray" : false;
        if ( r ) {
            var p = f.skin_url;
            if ( p ) {
                p = i.documentBaseURI.toAbsolute ( p )
            } else {
                p = tinymce.baseURL + "/skins/" + r
            }
            if ( tinymce.Env.documentMode <= 7 ) {
                q.skinUiCss = p + "/skin.ie7.min.css"
            } else {
                q.skinUiCss = p + "/skin.min.css"
            }
            i.contentCSS.push ( p + "/content" + (i.inline ? ".inline" : "") + ".min.css" )
        }
        i.on ( "ProgressState", function ( s ) {
            o.throbber = o.throbber || new tinymce.ui.Throbber ( o.panel.getEl ( "body" ) );
            if ( s.state ) {
                o.throbber.show ( s.time )
            } else {
                o.throbber.hide ()
            }
        } );
        if ( f.inline ) {
            return a ( q )
        }
        return j ( q )
    };
    o.resizeTo = h;
    o.resizeBy = e
} );
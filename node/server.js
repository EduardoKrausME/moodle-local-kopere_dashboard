"use strict";
/**
 * Created by kraus on 20/05/17.
 */
var settings  = require ( './util/settings.js' );
var socket    = require ( 'socket.io' );
var fs        = require ( 'fs' );
var useragent = require ( 'useragent' );
var accessId  = 0;
var server, app, http, io;

/**
 * SSL Logic and Server bindings
 */
if ( settings.ssl ) {
    console.log ( "SSL key:  " + settings.ssl.key );
    console.log ( "SSL cert: " + settings.ssl.cert );
    console.log ( "SSL ca:   " + settings.ssl.ca );
    var options = {
        key  : fs.readFileSync ( settings.ssl.key ),
        cert : fs.readFileSync ( settings.ssl.cert ),
        ca   : fs.readFileSync ( settings.ssl.ca )
    };

    app    = require ( "express" ) ( options );
    server = require('https').createServer( options );
} else {
    app    = require ( "express" ) ();
    server = require('http').Server( app );
}

// Starts listening socket.io
http = server.listen ( settings.port );
io   = require('socket.io')(http);

console.log ( "OK, porta " + settings.port );


io.sockets.on ( 'connection', function ( socket ) {
    /**
     * Login
     */
    socket.on ( 'login', function ( data ) {

        //console.log ( 'La vamos nóis' );
        var statusDomain = domainIsValid ( socket.handshake.headers );
        if ( !statusDomain ) {
            socket.emit ( 'logof', 'Domain not allow' );
            socket.leave ( socket.room );
            console.log ( 'Domain not allow' );
            return;
        }

        var agent = useragent.parse ( data.userAgent );
        //console.log ( agent );

        socket.room       = data.room;
        socket.accessId   = accessId++;
        socket.atualurl   = data.atualurl;
        socket.title      = data.title;
        socket.userAgent  = data.userAgent;
        socket.userid     = data.userid;
        socket.fullname   = data.fullname;
        socket.servertime = data.servertime;
        socket.focus      = data.focus;
        socket.privilegio = data.privilegio;
        socket.screen     = data.screen;
        socket.navigator  = agent.toAgent ().replace ( '0.0.0', '' );
        socket.os         = agent.os.toString ().replace ( '0.0.0', '' );
        socket.device     = agent.device.toString ().replace ( '0.0.0', '' ).replace ( 'Other', '' );


        // join the room
        socket.join ( data.room );

        if ( data.privilegio == 'z35admin' )
            sendToDashboardOnline ( data.room );
        else
            sendUserToDashboard ( socket.room, 'connect', createObjectUser ( socket ) );
    } );

    socket.on ( 'isFocus', function ( data ) {
        socket.focus = data.focus;

        console.log ( socket.focus );

        if ( socket.privilegio != 'z35admin' )
            sendUserToDashboard ( socket.room, 'focus', createObjectUser ( socket ) );
    } );

    socket.on ( 'disconnect', function () {
        // leave the room
        socket.leave ( socket.room );

        if ( socket.privilegio == 'z35admin' )
            sendToDashboardOnline ( socket.room );
        else
            sendUserToDashboard ( socket.room, 'disconnect', createObjectUser ( socket ) );
    } );

    /**
     * sistema de Upload
     */
    socket.on ( 'connection', function ( socket ) {
        socket.on ( 'message_to_server', function ( data ) {
            io.sockets.emit ( 'message_to_client', data );
        } );
    } );
} );


// Debug message
setInterval ( function () {
    console.log ( new Date () );
}, 10000 );


function sendUserToDashboard ( roomid, status, user ) {
    for ( var _id in io.sockets.sockets ) {
        var _socket = io.sockets.sockets[ _id ];
        if ( _socket.room == roomid ) {
            if ( _socket.privilegio == 'z35admin' )
                _socket.emit ( 'statusOnlineUser', status, user );
        }
    }
}

function sendToDashboardOnline ( roomid ) {
    var onlineUsers = [];
    for ( var _id in io.sockets.sockets ) {
        var _socket = io.sockets.sockets[ _id ];
        if ( _socket.room == roomid ) {
            if ( _socket.privilegio != 'z35admin' ) {
                onlineUsers.push ( createObjectUser ( _socket ) );
            }
        }
    }

    for ( var _id in io.sockets.sockets ) {
        var _socket = io.sockets.sockets[ _id ];
        if ( _socket.room == roomid ) {
            if ( _socket.privilegio == 'z35admin' )
                _socket.emit ( 'allOnnlineUsers', onlineUsers );
        }
    }
}

function createObjectUser ( socket ) {
    return {
        accessId   : socket.accessId,
        atualurl   : socket.atualurl,
        title      : socket.title,
        userAgent  : socket.userAgent,
        userid     : socket.userid,
        fullname   : socket.fullname,
        servertime : socket.servertime,
        focus      : socket.focus,
        screen     : socket.screen,
        navigator  : socket.navigator,
        os         : socket.os,
        device     : socket.device
    };
}

function domainIsValid ( headers ) {
    // get domain from referer
    var domainReferer = headers.referer.split ( '/' )[ 2 ];

    for ( var key in settings.domains ) {
        var alowedDomain = ""+settings.domains[ key ].trim ();

        // console.log(alowedDomain);

        // It is an asterisk
        if ( alowedDomain == '*' )
            return true;

        // It begins with asterisk
        if ( "*" == alowedDomain[ 0 ] ) {
            alowedDomain = alowedDomain.slice ( 1 );
            var reg      = new RegExp ( alowedDomain + "$" );
            if ( reg.test ( domainReferer ) )
                return true;
        }
        // It is equal
        if ( domainReferer == alowedDomain )
            return true;
    }

    console.log ( 'Domain not Found: ' + domainReferer );
    return false;
}
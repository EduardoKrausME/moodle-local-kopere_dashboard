/**
 * Created by kraus on 20/05/17.
 */
var httpPort = 8080;

var fs        = require ( 'fs' );
var app       = require ( 'express' ) ();
var https     = require ( 'https' );
var useragent = require ( 'useragent' );
var settings  = require ( './util/settings.js' );
var accessId  = 0;


if ( settings.ssl ) {
    console.log ( "SSL Enabled" );
    console.log ( "SSL Key File" + settings.ssl.key );
    console.log ( "SSL Cert Auth File" + settings.ssl.cert );
    console.log ( "SSL Cert Authority File" + settings.ssl.ca );

    var options = {
        key  : fs.readFileSync ( settings.ssl.key ),
        cert : fs.readFileSync ( settings.ssl.cert ),
        ca   : fs.readFileSync ( settings.ssl.ca )
    };
    app         = express ( options );
    server      = https.createServer ( options, app ).listen ( settings.port );
} else {
    app    = express ();
    server = app.listen ( settings.port );
}

// Starts listening socket.io
var io = require ( 'socket.io' ).listen ( server );


io.sockets.on ( 'connection', function ( socket ) {
    /**
     * Login
     */
    socket.on ( 'login', function ( data ) {

        console.log ( 'La vamos nóis' );
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


console.log ( 'Start on port: ' + httpPort );
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
        var alowedDomain = settings.domains[ key ].trim ();

        // It is an asterisk
        if ( alowedDomain.equals ( '*' ) )
            return true;

        // It begins with asterisk
        if ( "*".equals ( alowedDomain[ 0 ] ) ) {
            alowedDomain = alowedDomain.slice ( 1 );
            var reg      = new RegExp ( alowedDomain + "$" );
            if ( reg.test ( domainReferer ) )
                return true;
        }
        // It is equal
        if ( domainReferer.equals ( alowedDomain ) )
            return true;
    }

    console.log ( domainReferer + ' Domain not Found' );
    return false;
}
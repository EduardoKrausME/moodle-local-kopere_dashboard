/**
 * Created by kraus on 20/05/17.
 */

define([
    "jquery",
    "local_kopere_dashboard/socket.io",
], function($, io) {
    return {
        connectServer : function(userid, fullname, servertime, url, privilegio) {
            var socketio;
            var isFocus = true;
            var isConnected = false;
            var allUsersLength = 0;

            try {
                socketio = io.connect(url);
            } catch (error) {
                console.log(error);
                console.log('Error connect server');
            }

            function sendFocus(isFocus) {
                if (isConnected && privilegio == undefined) {
                    console.log("send isFocus = " + isFocus);
                    socketio.emit("isFocus", {
                        userid : userid,
                        focus  : isFocus + ""
                    });
                }
            }

            $(window)
                .blur(function() {
                    sendFocus(false);
                })
                .focus(function() {
                    sendFocus(true);
                });

            // When connecting make:
            socketio.on('connect', function() {
                isConnected = true;
                console.log('connect OK');

                socketio.emit("login", {
                    atualurl   : location.href,
                    title      : document.title,
                    userAgent  : navigator.userAgent,
                    userid     : userid,
                    fullname   : fullname,
                    servertime : servertime,
                    focus      : isFocus + "",
                    privilegio : privilegio,
                    screen     : screen.width + "x" + screen.height,
                    room       : 'dashboard-' + location.hostname
                });

            });
            socketio.on('disconnect', function() {
                isConnected = false;
                console.log('disconnect OK');
            });

            socketio.on("message_to_client", function(dataSend) {
                console.log(dataSend);
            });

            // process list on-line user
            socketio.on("allOnnlineUsers", function(allUsers) {

                console.log("allOnnlineUsers", allUsers);

                if (document.getElementById('user-count-online')) {
                    allUsersLength = allUsers.length;
                    $('#user-count-online').html(allUsersLength);
                    //console.log ( allUsers.lenght );
                }
                if (document.getElementById('user-list-online')) {
                    var table = $('#user-list-online').attr('data-tableid');

                    window[table].rows().remove();

                    for (var _id in allUsers) {
                        var user = allUsers[_id];
                        if (!user.screen)
                            user.screen = '';
                        user.page = createPageLink(user);

                        window[table].row.add(user);
                    }

                    window[table].rows().draw();
                }
                //console.log ( allUsers );
            });

            socketio.on("statusOnlineUser", function(status, user) {

                console.log("statusOnlineUser", status, user);

                if (document.getElementById('user-count-online')) {
                    if (status == 'connect')
                        allUsersLength++;
                    else if (status == 'disconnect')
                        allUsersLength--;

                    $('#user-count-online').html(allUsersLength);
                }
                if (document.getElementById('user-list-online')) {
                    var table = $('#user-list-online').attr('data-tableid');

                    if (!user.screen)
                        user.screen = '';
                    user.page = createPageLink(user);

                    if (status == 'connect') {
                        window[table].row.add(user).draw();
                    }
                    else if (status == 'disconnect') {
                        var numLInesi = window[table].rows().data().count();

                        for (var i = 0; i < numLInesi; i++) {
                            if (window[table].row(i).data().accessId == user.accessId) {
                                window[table].row(i).remove().draw();
                            }
                        }
                    }
                    else if (status == 'focus') {
                        var numLInesj = window[table].rows().data().count();

                        for (var j = 0; j < numLInesj; j++) {

                            if (window[table].row(j).data().accessId == user.accessId) {
                                window[table].row(j).data(user).draw();
                            }
                        }
                    }
                }
            });
        },
    };
});

function createPageLink(user) {
    return '<a target="_blank" href="' + user.atualurl + '">' + user.title + '</a>';
}

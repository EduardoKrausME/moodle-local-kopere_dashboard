/**
 * Created by kraus on 30/01/17.
 */

$ ( function () {

    $ ( '#open-startup' ).click ( function ( event ) {
        event.preventDefault ();
        openModal ();
    } );

    $ ( '#base-popup-close, .ui-widget-overlay' ).click ( function ( event ) {
        event.preventDefault ();
        closeModal ();
    } );

    if ( document.getElementById ( 'open-startup' ) )
        openModal ();

    $ ( window ).resize ( resizeModal );
} );

function openModal () {
    if ( $ ( window ).width () < 1000 ) {
        var popupHref = $ ( '.dashboard-load-popup' ).attr ( 'href' );
        if ( popupHref ) {
            location.href = popupHref;
            return;
        }
    }

    resizeModal ();

    $ ( '#modalWindow' ).show ();
    $ ( 'body' ).addClass ( 'remove-overflow' );
}

function closeModal () {
    $ ( 'body' ).removeClass ( 'remove-overflow' );

    $ ( '#modalWindow' ).hide ( 400, function () {
        $ ( '#base-popup' ).css ( { bottom : 'initial' } );
    } );
}

function resizeModal () {
    var newWidth = $ ( window ).width ();
    if ( newWidth > 1400 )
        newWidth = 1400;

    var newHeight = $ ( window ).height ();
    if ( newHeight > 1000 )
        newHeight = 1000;

    newWidth  = newWidth - 50;
    newHeight = newHeight - 50;

    var newTop = $ ( window ).height () - newHeight;

    $ ( '#base-popup' ).css ( {
        width  : newWidth + 'px',
        left   : '-' + ((newWidth + 20) / 2) + 'px',
        height : newHeight + 'px',
        top    : ((newTop - 16) / 2) + 'px'
    } );
}

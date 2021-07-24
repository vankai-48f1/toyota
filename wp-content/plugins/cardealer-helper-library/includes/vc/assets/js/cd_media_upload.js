/**
 * Backend JS for cd_media_upload - parameter
 */
!function($) {

  var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment

  $( '#vc_ui-panel-edit-element .file-picker-button' ).click( function( e ) {
    var send_attachment_bkp = wp.media.editor.send.attachment,
        cd_media_upload_button = $( this ),
        file_remover_button = $( this ).parent().find( '.file-remover-button' ),
        input = $( this ).parent().find( '.cd_media_upload_field' ),
        display = $( this ).parent().find( '.cd_media_upload_display' );

    _custom_media = true;
    wp.media.editor.send.attachment = function( props, attachment ) {
      if ( _custom_media ) {
        display.html( attachment.filename );
        input.val( attachment.id );
        cd_media_upload_button.addClass( 'hidden' );
        file_remover_button.removeClass( 'hidden' );
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }

    wp.media.editor.open( cd_media_upload_button );
    return false;
  });

  $( '#vc_ui-panel-edit-element .file-remover-button' ).click( function( e ) {
    var cd_media_upload_button = $( this ).parent().find( '.file-picker-button' ),
        file_remover_button = $( this ),
        input = $( this ).parent().find( '.cd_media_upload_field' ),
        display = $( this ).parent().find( '.cd_media_upload_display' );

    display.html( '' );
    input.val( '' );
    cd_media_upload_button.removeClass( 'hidden' );
    file_remover_button.addClass( 'hidden' );
  });

  $( '.add_media' ).on( 'click', function() {
    _custom_media = false;
  } );

}(window.jQuery);

    function getfilelist( cont, root ) {
      var daten = 'page=structure';
      daten += '&rex-api-call=treeview';
      daten += '&request=folder_open';
      daten += '&dir='+root;
      $( cont ).addClass( 'wait' );
      
      $.post( 'index.php', daten, function( data ) {
  
      $( cont ).find( '.start' ).html( '' );
      $( cont ).removeClass( 'wait' ).append( data );
      $( cont ).find('UL:hidden').slideDown({ duration: 500, easing: null });
      
    });
  }
  function getfilecontents(path,parent) {
      var daten = 'page=structure';
      daten += '&rex-api-call=treeview';
      daten += '&request=file_read';
      daten += '&file='+path;
      daten += '&class='+parent.attr('class');
      
      $.post( 'index.php', daten, function( data ) {
      if (data.request == 'md') {
        $('#classicModal').modal('show');
        $( '#md' ).html( data.html );
      }
      if (data.request == 'editor') {
        $('#classicModal2').modal('show');
        $( '#editor' ).html( data.html );
//        var editor = CodeMirror.fromTextArea(document.getElementById("editor"));
//        editor.setValue(data.html);
        $('#editor').addClass('codemirror');
        container = $('#editor');
        container.trigger('rex:ready', [container]);
      }
    },'json');
  }
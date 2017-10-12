<?php
if (rex_post ( 'show_folders', 'boolean' )) {
  $content1 = showfolders ( $this );
} elseif (rex_post ( 'show_parameters', 'boolean' )) {
  $content1 = showParameters ( $this );
} elseif (rex_post ( 'show_addon_list', 'boolean' )) {
  $content1 = showAddonList ( $this );
} else {
  $this->setConfig ( 'source', '' );
  $content1 = showAddonList ( $this );
}

echo '
    <form action="' . rex_url::currentBackendPage () . '" method="post">
        ' . $content1 . '
    </form>';
function showFolders($obj) {
  $content = "";
  $modal = '<!-- classic modal -->
	<div class="modal fade" id="classicModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                    <div id="modal-title-info"></div>
				</div>
				<div class="modal-body">
                  <div id="md"></div>
				</div>
				<div class="modal-footer">
				    <div class = "right-side">
					    <button type="button" class="btn btn-danger btn-simple" data-dismiss="modal">Schließen</button>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="classicModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                    <div id="modal-title-info"></div>
				</div>
				<div class="modal-body">
                  <textarea id="editor" style="width:100%; height:200px;"></textarea>
				</div>
				<div class="modal-footer">
				    <div class= "right-side">
					    <button type="button" class="btn btn-danger btn-simple" data-dismiss="modal">Schließen</button>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<!-- end modal -->'.PHP_EOL;
  $source = rex_post ( 'show_folders', 'string' );
  if ($source) {
    $obj->setConfig ( 'source', $source );
  } else {
    $source = $obj->getConfig ( 'source' );
  }
  $source_addon = rex_addon::get ( $source );
  $source_path = rex_path::addon ( $source );
  $source_path = "../redaxo/src/addons/".$source."/";
  $destination_path = str_replace ( "//", "/" . $obj->getConfig ( 'name' ) . "/", rex_path::addon ( "" ) );
  $source_folders = [ ];
  $dir_obj = dir ( $source_path );
  while ( false !== ($entry = $dir_obj->read ()) ) {
    if (! ($entry == "." || $entry == "..")) {
      if (is_dir ( $source_path . $entry )) {
        $source_folders [] = $entry;
      }
    }
  }

  $content .= "<h2>Folders 'n' Files</h2>";
  $tv = new treeview ( $source_path );
  $content .= "<div id='ft_container'>" . $tv->create_tree () . "</div>";
  $formElements = [ ];
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-cancel rex-form-aligned" type="submit" name="show_parameters" value="' . $source . '" ' . rex::getAccesskey ( $obj->i18n ( 'addon_viewer_cancel' ), 'save' ) . '>' . $obj->i18n ( 'addon_viewer_cancel' ) . '</button>';
  $formElements [] = $n;
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="create_copy" value="' . $source . '" ' . rex::getAccesskey ( $obj->i18n ( 'addon_viewer_continue' ), 'save' ) . '>' . $obj->i18n ( 'addon_viewer_continue' ) . '</button>';
  $formElements [] = $n;
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'flush', true );
  $fragment->setVar ( 'elements', $formElements, false );
  $buttons = $fragment->parse ( 'core/form/submit.php' );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'class', 'edit' );
  $fragment->setVar ( 'title', $obj->i18n ( 'addon_viewer_structure' ) );
  $fragment->setVar ( 'body', $content, false );
  $fragment->setVar ( 'buttons', $buttons, false );
  $content1 = $fragment->parse ( 'core/page/section.php' );
  $java = "  <script type='text/javascript'>
  $( '#ft_container' ).on('click', 'LI A', function() {
    var entry = $(this).parent();
    
    if( entry.hasClass('folder') ) {
      if( entry.hasClass('collapsed') ) {
            
        entry.find('UL').remove();
        getfilelist( entry, escape( $(this).attr('rel') ));
        entry.removeClass('collapsed').addClass('expanded');
      }
      else {
        
        entry.find('UL').slideUp({ duration: 500, easing: null });
        entry.removeClass('expanded').addClass('collapsed');
      }
    } else {
      getfilecontents($(this).attr( 'rel' ),entry);
      $('.modal-title').html($(this).html( ));
      $('#modal-title-info').html($(this).attr( 'rel' ));
      $( '#selected_file' ).text('File: ' + $(this).attr( 'rel' ));
    }
  return false;
  });</script>" . PHP_EOL;
  
//   return $content1 . $java . $modal;  
  return $java . $content1 . $modal;
}
function showParameters($obj) {
  $source = rex_post ( 'addon', 'string' );
  if ($source) {
    $obj->setConfig ( 'source', $source );
  } else {
    $source = $obj->getConfig ( 'source' );
  }
  $destination = $source;
  $candidat = rex_addon::get ( $source );
  $page = $candidat->getProperty ( 'page' );
  $requires = $candidat->getProperty ( 'requires' );
  $pieces = [ ];
  $pieces [] = "<b>" . $source . "</b> wird als <b>" . $destination . "</b> kopiert<br/>";
  $pieces [] = "Pfad: " . rex_path::core ();
  // $pieces[] = '<input class="form-control icp" type="text" name="icon" value="'.str_replace("rex-icon ", "", $page['icon']).'">';
  
  // $content = rex_view::success (join("<br />", $pieces) );
  $content = join ( "<br />", $pieces );
  $formElements = [ ];
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-cancel rex-form-aligned" type="submit" name="show_addon_list" value="' . $source . '" ' . rex::getAccesskey ( $obj->i18n ( 'addon_viewer_cancel' ), 'save' ) . '>' . $obj->i18n ( 'addon_viewer_cancel' ) . '</button>';
  $formElements [] = $n;
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="show_folders" value="' . $source . '" ' . rex::getAccesskey ( $obj->i18n ( 'addon_viewer_continue' ), 'save' ) . '>' . $obj->i18n ( 'addon_viewer_continue' ) . '</button>';
  $formElements [] = $n;
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'flush', true );
  $fragment->setVar ( 'elements', $formElements, false );
  $buttons = $fragment->parse ( 'core/form/submit.php' );
  
  $content = '<fieldset>';
  $formElements = [ ];
  
  $parameter = [ 
      "name",
      "title",
      "author",
      "version",
      "release",
      "icon",
      "supportpage",
      "info",
      "permission" 
  ];
  $label = [ 
      "Addon Name",
      "Titel",
      "Author",
      "Version",
      "REDAXO Release",
      "Icon",
      "Support Page",
      "Info",
      "Permission" 
  ];
  $values = [ 
      $destination,
      (is_array ( $page ) && array_key_exists ( 'title', $page ) ? $page ['title'] : ""),
      $candidat->getProperty ( 'author' ),
      $candidat->getProperty ( 'version' ),
      $requires ['redaxo'],
      str_replace ( array (
          "rex-icon ",
          "fa " 
      ), array (
          "",
          "" 
      ), (is_array ( $page ) && array_key_exists ( 'icon', $page ) ? $page ['icon'] : "") ),
      $candidat->getProperty ( 'supportpage' ),
      $candidat->getProperty ( 'info' ),
      (is_array ( $page ) && array_key_exists ( 'perm', $page ) ? $page ['perm'] : "") 
  ];
  foreach ( $parameter as $k => $p ) {
    $n = [ ];
    $n ['label'] = '<label for="rex-template-' . $p . '"> ' . $label [$k] . '</label>';
    if ($p == "icon") {
      $n ['field'] = '<input class="form-control icp" type="text" id="rex-template-' . $p . '" name="config[' . $p . ']" value="' . $values [$k] . '" />&nbsp&nbsp';
    } else {
      $n ['field'] = '<input class="form-control" type="text" id="rex-template-' . $p . '" name="config[' . $p . ']" value="' . $values [$k] . '" />&nbsp&nbsp';
    }
    $formElements [] = $n;
  }
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'elements', $formElements, false );
  $content .= $fragment->parse ( 'core/form/form.php' );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'class', 'edit' );
  $fragment->setVar ( 'title', $obj->i18n ( 'addon_viewer_settings' ) );
  $fragment->setVar ( 'body', $content, false );
  $fragment->setVar ( 'buttons', $buttons, false );
  $content1 = $fragment->parse ( 'core/page/section.php' );
  return $content1;
}
function showAddonList($obj) {
  $source = $obj->getConfig ( 'source' );
  $pieces = array ();
  $path = rex_path::addon ( "" );
  $d = dir ( $path );
  
  $pieces = array ();
  $pieces [] = "<table class='table'>";
  $pieces [] = "<thead><tr><th class='text-center'>" . $obj->i18n ( 'addon_viewer_select' ) . "</th><th>" . "Addon" . "</th></tr></thead>";
  
  if ($d) {
    while ( ($entry = $d->read ()) !== FALSE ) {
      if ($entry != "." and $entry != "..") {
        if (is_dir ( $path . "/" . $entry . "/" )) {
          $pieces [] = "<tr>";
          $pieces [] = "<td class='text-center'>";
          if ($entry == $source) {
            $pieces [] = "<input type='radio' name='addon' value='" . $entry . "' checked='checked'>";
          } else {
            $pieces [] = "<input type='radio' name='addon' value='" . $entry . "'>";
          }
          $pieces [] = "</td>";
          $pieces [] = "<td>";
          $pieces [] = $entry;
          $pieces [] = "</td>";
          $pieces [] = "</tr>";
        }
      }
    }
    $d->close ();
  }
  
  $pieces [] = "</table>";
  
  $content = join ( "\n", $pieces );
  
  $formElements = [ ];
  
  $n = [ ];
  $n ['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="show_parameters" value="1" ' . rex::getAccesskey ( $obj->i18n ( 'addon_viewer_continue' ), 'save' ) . '>' . $obj->i18n ( 'addon_viewer_continue' ) . '</button>';
  $formElements [] = $n;
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'flush', true );
  $fragment->setVar ( 'elements', $formElements, false );
  $buttons = $fragment->parse ( 'core/form/submit.php' );
  
  $fragment = new rex_fragment ();
  $fragment->setVar ( 'class', 'edit' );
  $fragment->setVar ( 'title', "AddOns" );
  $fragment->setVar ( 'body', $content, false );
  $fragment->setVar ( 'buttons', $buttons, false );
  $content1 = $fragment->parse ( 'core/page/section.php' );
  return $content1;
}
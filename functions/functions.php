<?php

function replaceDummies($filename,$search,$replace) {
  $status = FALSE;
  if($filename) {
    if($data = file_get_contents($filename)){
      $data = str_replace($search, $replace, $data);
      if(file_put_contents($filename, $data)) {
        $status = TRUE;
      }
    }

  }
  return $status;
}
function showDescription($path, $folder, $file) {
  $data = array (
      "" 
  );
  $filename = $path . $folder . $file;
  if (file_exists ( $filename )) {
    $data = file ( $filename );
  }
  return join ( "<br>", $data );
}
function showXMLDescription($path, $folder, $file) {
  $filename = $path . $folder . $file;
  $xml = simplexml_load_file($filename);
  $loc = rex_i18n::getLocale();
  $code = substr($loc,0,2);
  $string = $xml->{$code};
  return $string;
}
function createTemplate($obj) {
    $package = new addonPackage();
    $package->setDataSource($obj->getDataPath ());
    $package->initialise(array("name"=>$obj->getConfig ( 'name' ),
        "title"=>$obj->getConfig ( 'title' ),
        "author"=>$obj->getConfig ( 'author' ),
        "version"=>$obj->getConfig ( 'version' ),
        "release"=>$obj->getConfig ( 'release' ),
        "icon"=>$obj->getConfig ( 'icon' ),
        "supportpage"=>$obj->getConfig ( 'supportpage' ),
        "info"=>$obj->getConfig ( 'info' ),
        "permission"=>$obj->getConfig ( 'permission' )
    ));
    $package->addFile(($obj->getConfig ( 'boot_php' )?"boot.php":""));
    $package->addFile(($obj->getConfig ( 'help_php' )?"help.php":""));
    $package->addFile(($obj->getConfig ( 'install_php' )?"install.php":""));
    $package->addFile(($obj->getConfig ( 'install_sql' )?"install.sql":""));
    $package->addFile(($obj->getConfig ( 'uninstall_sql' )?"unnstall.sql":""));
    $package->addFolder(($obj->getConfig ( 'lang' )?"lang":""));
    $package->addFolder(($obj->getConfig ( 'pages' )?"pages":""));
    $package->addFolder(($obj->getConfig ( 'lib' )?"lib":""));
    $package->addFolder(($obj->getConfig ( 'vendor' )?"vendor":""));
    $package->addFolder(($obj->getConfig ( 'functions' )?"functions":""));
    $package->addFolder(($obj->getConfig ( 'module' )?"module":""));
    $package->addFolder(($obj->getConfig ( 'templates' )?"templates":""));
    $package->addFolder(($obj->getConfig ( 'assets' )?"assets":""));
    $package->addFolder(($obj->getConfig ( 'data' )?"data":""));
    $package->addFolder(($obj->getConfig ( 'scss' )?"scss":""));
    $package->addFolder(($obj->getConfig ( 'install' )?"install":""));
    $package->addFolder(($obj->getConfig ( 'plugins' )?"plugins":""));
  return $package;
}
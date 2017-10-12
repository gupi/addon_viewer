<?php

class treeview
{

  private $files;

  private $folder;

  function __construct($path)
  {
    $files = array();
    
    if (file_exists($path)) {
      if ($path[strlen($path) - 1] == '/')
        $this->folder = $path;
      else
        $this->folder = $path . '/';
      
      $this->dir = opendir($path);
      while (($file = readdir($this->dir)) != false)
        $this->files[] = $file;
      closedir($this->dir);
    }
  }

  function create_tree()
  {
    if (count($this->files) > 2) { /* First 2 entries are . and .. -skip them */
      natcasesort($this->files);
      $list = '<ul class="filetree" >';
      // Group folders first
      foreach ($this->files as $file) {
        if (file_exists($this->folder . $file) && $file != '.' && $file != '..' &&
           is_dir($this->folder . $file)) {
          $list .= '<li class="folder collapsed"><a href="#" rel="' .
           htmlentities($this->folder . $file) . '/">' . htmlentities($file) . '</a></li>';
      }
    }
    // Group all files
    foreach ($this->files as $file) {
      if (file_exists($this->folder . $file) && $file != '.' && $file != '..' &&
         ! is_dir($this->folder . $file)) {
        $ext = preg_replace('/^.*\./', '', $file);
        $list .= '<li class="file ext_' . $ext . '"><a href="#" rel="' .
           htmlentities($this->folder . $file) . '">' . htmlentities($file) . '</a></li>';
      }
    }
    $list .= '</ul>';
    return $list;
  }
}
}

class rex_api_treeview extends rex_api_function
{

protected $published = true;

function execute()
{
  $request = rex_request('request', 'string');
  switch ($request) {
    case "folder_open":
      $path = rex_request('dir', 'string');
      $tree = new treeview($path);
      echo $tree->create_tree();
      break;
    case "file_read":
      $classes = explode(" ", rex_request('class', 'string'));
      $type = $classes[1];
      $file = rex_request('file', 'string');
      
      switch ($type) {
        case "ext_php":
        case "ext_html":
        case "ext_yml":
          $answer["request"] = "md";
          $answer["html"] = highlight_file($file, true);
          // $answer["html"]=$this->media_highlight_file($file);
          break;
        case "ext_js":
        case "ext_css":
        case "ext_scss":
          $answer["request"] = "editor";
          $answer["html"] = file_get_contents($file);
          break;
        case "ext_png":
        case "ext_jpg":
        case "ext_gif":
          // $file = str_replace("..", "", $file);
          $file_info = pathinfo($file);
          $basename = $file_info['basename'];
          $owner_path = rex_path::addon("addon_viewer");
          $dest = $owner_path . "assets/temp/" . $basename;
          if (copy($file, $dest)) {
            $answer["html"] = "<img src='" . rex_url::base(
              "index.php?rex_media_type=addon_viewer_detail&rex_media_file=$basename") . "'>";
          } else {
            $answer["html"] = "<h2>Fehler</h2>";
          }
          $answer["request"] = "md";
          break;
        default:
          $answer["request"] = "md";
          $answer["html"] = markitup::parseOutput('markdown', file_get_contents($file));
        // $answer["html"]= "<pre>".print_r($type,true)."</pre>";
      }
      echo json_encode($answer);
      
      break;
  }
  exit();
}

function media_highlight_file($file)
{
  $code = substr(highlight_file($file, true), 36, - 15);
  $lines = explode('<br />', $code);
  $lines = array_combine(range(1, count($lines)), $lines);
  
  $line_count = count($lines);
  $pad_length = strlen($line_count);
  
  $return = '<div style="width: 600px; display: inline-block; display: flex;"><code>';
  foreach ($lines as $i => $line) {
    $lineNumber = str_pad($i + 1, $pad_length, '0', STR_PAD_LEFT);
    if ($i % 2 == 0) {
      $numbgcolor = '#C8E1FA';
      $linebgcolor = '#F7F7F7';
      $fontcolor = '#3F85CA';
    } else {
      $numbgcolor = '#DFEFFF';
      $linebgcolor = '#FDFDFD';
      $fontcolor = '#5499DE';
    }
    
    if ($line == '')
      $line = '&nbsp;';
    $return .= '<div style="background-color: ' . $numbgcolor .
       '; white-space: nowrap; width: 23px; float: left; padding: 0px 2px 0px 2px; text-align: center; color: ' .
       $fontcolor . ';">' . $i . '</div><div style="background-color: ' . $linebgcolor .
       '; margin-left: 0; float: left; padding-left: 5px; width: calc(100% - 32px);">' . $line .
       '</div>';
  }
  $return .= '</code></div>';
  return $return;
}
}

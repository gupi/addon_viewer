<?php
$db = rex_sql::factory();
try {
  $answer = $db->getArray('SELECT `id` FROM `rex_media_manager_type` WHERE `name`="addon_viewer_detail"');
} catch (rex_sql_exception $e) {
  $msg[] = rex_view::warning('Der Media Manager "addon_viewer_detail" Typ wurde nicht gefunden.<br/>Wahrscheinlich existiert er gar nicht.');
}
if (count($answer)) {
  $id = $answer[0]['id'];
  try {
    $db->setQuery('DELETE FROM `rex_media_manager_type` WHERE `type_id`="'.$id.'";');
  } catch (rex_sql_exception $e) {
    $msg[] = rex_view::warning('Der Media Manager "addon_viewer_detail" Typ wurde nicht entfernt.<br/>Wahrscheinlich existiert er gar nicht.');
  }
  
  try {
    $db->setQuery('DELETE FROM `rex_media_manager_type_effect` WHERE `type_id`="'.$id.'";');
  } catch (rex_sql_exception $e) {
    $msg[] = rex_view::warning('Die Media Manager Effekte von "addon_viewer_detail" wurden nicht entfernt.<br/>Wahrscheinlich existieren sie gar nicht.');
  }
} 
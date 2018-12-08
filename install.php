<?php
$db = rex_sql::factory();
$db->setTable(rex::getTablePrefix() . 'media_manager_type');
$db->setValue('name','addon_viewer_detail');
$db->setValue('description','Zur Darstellung von Images im AddOn Viewer');
$msg = [];
try {
  $db->insert();
  $msg[] = rex_view::success('Der Media Manager "addon_viewer_detail" Typ wurde angelegt. ');
} catch (rex_sql_exception $e) {
  $msg[] = rex_view::warning('Der Media Manager "addon_viewer_detail" Typ wurde nicht angelegt.<br/>Wahrscheinlich existiert er schon.');
}

$id = (int) $db->getLastId();


$db = rex_sql::factory();
$db->setTable(rex::getTablePrefix() . 'media_manager_type_effect');
$db->setValue('type_id', $id);
$db->setValue('priority', '1');
$db->setValue('effect', 'resize');
$db->setValue('parameters', '{"rex_effect_crop":{"rex_effect_crop_width":"","rex_effect_crop_height":"","rex_effect_crop_offset_width":"","rex_effect_crop_offset_height":"","rex_effect_crop_hpos":"center","rex_effect_crop_vpos":"middle"},"rex_effect_filter_blur":{"rex_effect_filter_blur_repeats":"10","rex_effect_filter_blur_type":"gaussian","rex_effect_filter_blur_smoothit":""},"rex_effect_filter_sharpen":{"rex_effect_filter_sharpen_amount":"80","rex_effect_filter_sharpen_radius":"0.5","rex_effect_filter_sharpen_threshold":"3"},"rex_effect_flip":{"rex_effect_flip_flip":"X"},"rex_effect_header":{"rex_effect_header_download":"open_media","rex_effect_header_cache":"no_cache"},"rex_effect_insert_image":{"rex_effect_insert_image_brandimage":"","rex_effect_insert_image_hpos":"left","rex_effect_insert_image_vpos":"top","rex_effect_insert_image_padding_x":"-10","rex_effect_insert_image_padding_y":"-10"},"rex_effect_mediapath":{"rex_effect_mediapath_mediapath":""},"rex_effect_mirror":{"rex_effect_mirror_height":"","rex_effect_mirror_set_transparent":"colored","rex_effect_mirror_bg_r":"","rex_effect_mirror_bg_g":"","rex_effect_mirror_bg_b":""},"rex_effect_resize":{"rex_effect_resize_width":"300","rex_effect_resize_height":"300","rex_effect_resize_style":"maximum","rex_effect_resize_allow_enlarge":"not_enlarge"},"rex_effect_rounded_corners":{"rex_effect_rounded_corners_topleft":"","rex_effect_rounded_corners_topright":"","rex_effect_rounded_corners_bottomleft":"","rex_effect_rounded_corners_bottomright":""},"rex_effect_workspace":{"rex_effect_workspace_width":"","rex_effect_workspace_height":"","rex_effect_workspace_hpos":"left","rex_effect_workspace_vpos":"top","rex_effect_workspace_set_transparent":"colored","rex_effect_workspace_bg_r":"","rex_effect_workspace_bg_g":"","rex_effect_workspace_bg_b":""}}');

try {
  $db->insert();
  $msg[] = rex_view::success('Der Media Manager Effekt wurde angelegt und kann konfiguriert werden!');
} catch (rex_sql_exception $e) {
  $msg[] = rex_view::warning('Der Media Manager Effekt wurde nicht angelegt.<br/>Wahrscheinlich existiert er schon.');
}

$db->setTable(rex::getTablePrefix() . 'media_manager_type_effect');
$db->setValue('type_id', $id);
$db->setValue('priority', '2');
$db->setValue('effect', 'mediapath');
$db->setValue('parameters', '{"rex_effect_rounded_corners":{"rex_effect_rounded_corners_topleft":"","rex_effect_rounded_corners_topright":"","rex_effect_rounded_corners_bottomleft":"","rex_effect_rounded_corners_bottomright":""},"rex_effect_workspace":{"rex_effect_workspace_width":"","rex_effect_workspace_height":"","rex_effect_workspace_hpos":"left","rex_effect_workspace_vpos":"top","rex_effect_workspace_set_transparent":"colored","rex_effect_workspace_bg_r":"","rex_effect_workspace_bg_g":"","rex_effect_workspace_bg_b":""},"rex_effect_crop":{"rex_effect_crop_width":"","rex_effect_crop_height":"","rex_effect_crop_offset_width":"","rex_effect_crop_offset_height":"","rex_effect_crop_hpos":"center","rex_effect_crop_vpos":"middle"},"rex_effect_insert_image":{"rex_effect_insert_image_brandimage":"","rex_effect_insert_image_hpos":"left","rex_effect_insert_image_vpos":"top","rex_effect_insert_image_padding_x":"-10","rex_effect_insert_image_padding_y":"-10"},"rex_effect_rotate":{"rex_effect_rotate_rotate":"0"},"rex_effect_filter_colorize":{"rex_effect_filter_colorize_filter_r":"","rex_effect_filter_colorize_filter_g":"","rex_effect_filter_colorize_filter_b":""},"rex_effect_image_properties":{"rex_effect_image_properties_jpg_quality":"","rex_effect_image_properties_png_compression":"","rex_effect_image_properties_webp_quality":"","rex_effect_image_properties_interlace":null},"rex_effect_filter_brightness":{"rex_effect_filter_brightness_brightness":""},"rex_effect_flip":{"rex_effect_flip_flip":"X"},"rex_effect_filter_contrast":{"rex_effect_filter_contrast_contrast":""},"rex_effect_filter_sharpen":{"rex_effect_filter_sharpen_amount":"80","rex_effect_filter_sharpen_radius":"0.5","rex_effect_filter_sharpen_threshold":"3"},"rex_effect_resize":{"rex_effect_resize_width":"","rex_effect_resize_height":"","rex_effect_resize_style":"maximum","rex_effect_resize_allow_enlarge":"enlarge"},"rex_effect_filter_blur":{"rex_effect_filter_blur_repeats":"10","rex_effect_filter_blur_type":"gaussian","rex_effect_filter_blur_smoothit":""},"rex_effect_mirror":{"rex_effect_mirror_height":"","rex_effect_mirror_set_transparent":"colored","rex_effect_mirror_bg_r":"","rex_effect_mirror_bg_g":"","rex_effect_mirror_bg_b":""},"rex_effect_header":{"rex_effect_header_download":"open_media","rex_effect_header_cache":"no_cache"},"rex_effect_convert2img":{"rex_effect_convert2img_convert_to":"jpg","rex_effect_convert2img_density":"150"},"rex_effect_mediapath":{"rex_effect_mediapath_mediapath":"redaxo\/src\/addons\/addon_viewer\/assets\/temp\/"}}');

try {
  $db->insert();
  $msg[] = rex_view::success('Der Media Manager Effekt wurde angelegt und kann konfiguriert werden!');
} catch (rex_sql_exception $e) {
  $msg[] = rex_view::warning('Der Media Manager Effekt wurde nicht angelegt.<br/>Wahrscheinlich existiert er schon.');
}

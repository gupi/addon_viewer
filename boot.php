<?php


if (rex::isBackend()) {
  rex_view::addCssFile($this->getAssetsUrl('css/filetree.css'));
  rex_view::addJsFile($this->getAssetsUrl('filetree.js'));
}
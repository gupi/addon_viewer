<?php


if (rex::isBackend()) {
  rex_view::addCssFile($this->getAssetsUrl('css/filetree.css'));
}
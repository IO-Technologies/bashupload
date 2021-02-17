<?php

# TXT renderer

$txt_view = __DIR__ . "/../views/{$action}.txt.phtml";

if ( is_file($txt_view) ) {
  header('Content-type: text/plain;charset=utf8');
  include $txt_view;
}
else {
  include __DIR__ . '/html.php';
}
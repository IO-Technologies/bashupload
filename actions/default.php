<?php

# Default page handler



# redirect to SSL if necessary (only GET requests)
if ( FORCE_SSL && ($_SERVER['REQUEST_METHOD'] === 'GET') ) {
  if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
      header('HTTP/1.1 301 Moved Permanently');
      header('Location: ' . 'https://' . HOST . $_SERVER['REQUEST_URI']);
      exit;
  }
}
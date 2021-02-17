<?php

# Utilities



# Get system MIME types list
function system_extension_mime_types() {
    $out = array();
    $file = fopen('/etc/mime.types', 'r');
    
    while(($line = fgets($file)) !== false) {
        $line = trim(preg_replace('/#.*/', '', $line));
        if(!$line) continue;
        $parts = preg_split('/\s+/', $line);
        if(count($parts) == 1) continue;
        $type = array_shift($parts);
        foreach($parts as $part) $out[$part] = $type;
    }
    
    fclose($file);
    
    return $out;
}


# Get file MIME type by its extension
function system_extension_mime_type($file) {
    static $types;
    
    if(!isset($types)) $types = system_extension_mime_types();
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    
    if(!$ext) $ext = $file;
    $ext = strtolower($ext);
    
    return isset($types[$ext]) ? $types[$ext] : null;
}


# generate random file prefix-id
function gen_id()
{
    $id   = '';
    $abc  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $abc .= "abcdefghijklmnopqrstuvwxyz";
    $abc .= "0123456789";
    $abc .= '_-';

    $max  = strlen($abc);

    for ($i=0; $i < 5; $i++) $id .= $abc[mt_rand(0, $max-1)];

    return $id;
}


# get max file upload size from settings
function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $post_max_size = parse_ini_bytes_size(ini_get('post_max_size'));
    if ($post_max_size > 0) {
      $max_size = $post_max_size;
    }

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_ini_bytes_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  
  // Convert to GB
  $max_size = $max_size / (1024 * 1024 * 1024);
  if ( $max_size < 1 ) {
    $max_size = round($max_size, 3);
  }
  else {
    $max_size = round($max_size);
  }
  
  return $max_size . 'G';
}

# parse php.ini size value
function parse_ini_bytes_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}
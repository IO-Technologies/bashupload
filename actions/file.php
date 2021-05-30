<?php

# Download file handler



# file stats
$file = explode('/', trim($uri, '/'));
$file = ['id' => $file[0], 'name' => $file[1], 'path' => '/' . $file[0] . '-' . $file[1], 'extension' => strtolower(pathinfo($file[1], PATHINFO_EXTENSION))];

$file_path = STORAGE . '/' . md5($file['path']);
$file['size'] = is_file($file_path) ? filesize($file_path) : 0;


# download stats
$download_mark_file = $file_path . '.delete';
if ( is_file($download_mark_file) ) {
  $downloads = (int)file_get_contents($download_mark_file);
}
else {
  $downloads = 0;
}


# title for rendering info
$title = htmlspecialchars($file['name']) . ' / download from bashupload.com';


# render
if ( !$_GET['download'] && $renderer == 'html' ) {
  $sorry = !$file['size'];
}

# direct download
else if ( $file['size'] )
{
  file_put_contents($download_mark_file, $downloads + 1);
  
	header('Content-type: ' . system_extension_mime_type($file['name']));
	header('Content-Disposition: attachment; filename=' . $file['name']); 
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . $file['size']);
	header('X-Accel-Redirect: /files/' . md5($file['path']));
	readfile($file_path);
	exit;
}

# no file found
else
{
	header('HTTP/1.0 404 Not Found');
	exit;
}

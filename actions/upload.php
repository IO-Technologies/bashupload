<?php

# Upload file(s) handler



# First, let's check raw input data
if ( $f = fopen('php://input', 'r') )
{
	$name = trim($uri, '/');
	if ( !$name ) $name = uniqid();
	$tmp = tempnam('/var/files/tmp', 'upload');

	$ftmp = fopen($tmp, 'w');
	while ( !feof($f) ) fputs($ftmp, fgets($f));

	fclose($f);
	fclose($ftmp);

	if ( filesize($tmp) ) $_FILES[] = [
		'tmp_name' => $tmp,
		'name' => $name
	];
}



# Next, let's move uploaded files to the storage
$id = gen_id();
foreach ( $_FILES as $key_file => $file )
{
  # make file name safe
	$file['name'] = str_replace(['/', '-'], '_', trim($file['name'], '/'));
	
	# if the file name is too long, let's just replace it with random short ID
	if ( strpos($file['name'], ' ') || strlen($file['name']) > 15 ) {
		$file['name'] = gen_id() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
	}

  # move file to a final location
	$destination = STORAGE . '/' . md5('/' . $id . '-' . $file['name']);
	rename($file['tmp_name'], $destination);

  # register this uploaded file data
	$uploads[] = [
		'id' => ($rewrite_id ? : $id),
		'name' => $file['name'],
		'path' => $destination,
		'size' => filesize($destination),
		'upload_name' => $key_file,
		'is_rewritten' => $rewrite_id ? true : false
	];
}
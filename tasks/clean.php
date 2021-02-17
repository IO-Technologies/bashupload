<?php

# Clean expired and outdated files from storage

require __DIR__ . '/../config.php';



# delete all expired files
exec('find ' . realpath(STORAGE) . ' -type f -mtime +' . EXPIRE_DAYS . ' -delete');


# select all files with ".delete" metafile
# with at least one download was made (> 60 minutes ago to make sure downloading process is complete for big files)
exec('find ' . realpath(STORAGE) . ' -type f -name "*.delete" -mmin +60', $o);


# remove all files which were downloaded max times
foreach ( $o as $file ) {
  if ( max(intval(file_get_contents($file)), 1) >= MAX_DOWNLOADS ) {
    echo $file . "\n";
    
    exec('rm -rf ' . $file);
    exec('rm -rf ' . str_replace('.delete', '', $file));
  }
}
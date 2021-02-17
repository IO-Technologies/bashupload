PHP/JavaScript file upload web app to upload files from command line & browser, and download them elsewhere. Frequently used to upload/download files on servers. Hosted version is available at [**bashupload.com**](https://bashupload.com/).

# Features
1. Upload from command line with curl (or other CLI tools).
1. Download from command line with wget, curl or other CLI tools.
1. Download from browser.
1. Upload from browser or mobile with file browser.
1. Upload from browser with drag and drop.
1. Automatic filesize limit based on php.ini post_max_size and upload_max_filesize settings.
1. Files are automatically removed after specified number of days (configurable).
1. Files are automatically removed after specified number of downloads (also configurable).

# Installation
Make sure you have:
- php 7.2+
- Nginx 1.14+ (host config example located in the "setup" folder) or other webserver which can talk to PHP
- "/var/files" folder available for writing/reading to a web user ("www-data" usually)

1. Clone bashupload repo to a folder.
1. Setup webserver and point it to web/index.php (or just use Nginx virtual host config from "setup" folder).
1. Tune config.php to your requirements. You can optionally copy config.php to config.local.php and make changes there (the file is untracked in GIT).
1. Add "php tasks/clean.php" to be executed every hour using cron:
```
0 *     * * *     root   php /path/to/bashupload/tasks/clean.php
```

# Configuration
- You can change config.php (or untracked config.local.php file) to tune expiration and download limits to your needs.
- Change post_max_size and upload_max_filesize in php.ini to meet your needs on maximum upload file size.
- If you use Nginx, don't forget to update "client_max_body_size" param as well.

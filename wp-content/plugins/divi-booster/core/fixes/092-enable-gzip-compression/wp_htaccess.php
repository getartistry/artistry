<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
<ifModule mod_deflate.c>
Addtype font/truetype .ttf
AddOutputFilterByType DEFLATE text/text text/html text/plain text/xml text/css application/xhtml+xml application/rss+xml application/x-javascript application/javascript text/javascript font/truetype
</ifModule>

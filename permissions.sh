#!/bin/bash
#
# This script configures WordPress file permissions based on recommendations
# from http://codex.wordpress.org/Hardening_WordPress#File_permissions
#
# Author: Michael Conigliaro <mike [at] conigliaro [dot] org>
#
WP_OWNER=dev # <-- wordpress owner
WP_GROUP=dev # <-- wordpress group
WP_ROOT=/home/dev/public_html # <-- wordpress root directory
WS_GROUP=dev # <-- webserver group

# reset to safe defaults
find ${WP_ROOT} -exec chown ${WP_OWNER}:${WP_GROUP} {} \;
find ${WP_ROOT} -type d -exec chmod 755 {} \;
find ${WP_ROOT} -type f -exec chmod 644 {} \;

# allow wordpress to manage wp-config.php (but prevent world access)
chgrp ${WS_GROUP} ${WP_ROOT}/wp-config.php
chmod 660 ${WP_ROOT}/wp-config.php

# allow wordpress to manage wp-content
find ${WP_ROOT}/wp-content -exec chgrp ${WS_GROUP} {} \;
find ${WP_ROOT}/wp-content -type d -exec chmod 775 {} \;
find ${WP_ROOT}/wp-content -type f -exec chmod 664 {} \;

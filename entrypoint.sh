#!/bin/bash
# Set permissions for the uploads and files directories
chown -R www-data:www-data /server/uploads /server/files
chmod -R 757 /server/uploads /server/files
# Trap SIGTERM and SIGINT signals and pass them to apache
trap 'apachectl -k graceful-stop' SIGTERM SIGINT

# Start Apache in the foreground
apachectl -D FOREGROUND

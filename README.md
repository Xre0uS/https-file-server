## HTTPS File Server

Quick and dirty Apache HTTPS file server for your upload/download needs.

## Prerequisites

You'll need to have `docker` and `openssl` installed.

## Quick Start

```shell
git clone https://github.com/Xre0uS/https-file-server
cd https-file-server

# Use default names for the cert, just hit enter
sudo openssl req -x509 -nodes -days 1825 -newkey rsa:4096 -keyout apache-selfsigned.key -out apache-selfsigned.crt

# Use sudo here as the private key is protected
sudo docker build -t file-server:base .
docker run --rm -p 8443:443  -v /tmp/files:/server/files -v /tmp/uploads:/server/uploads file-server:base
```

This will open the file server on port 8443, files to serve is located at /tmp/files/, uploaded files are placed at /tmp/uploads/. You can customise the path in the `docker run` command.

Since this cert is self signed, we will need to skip certificate checks:

```shell
curl -k -X POST https://<ip>:8443/upload.php -F 'file=@test.txt'
curl -k https://<ip>:8443/files/test.txt
curl -k https://<ip>:8443/files/test.txt --output test.txt
wget --no-check-certificate https://<ip>:8443/files/test.txt
```

Once you're done with the container, use `docker stop` to stop. The container will be removed on exit.

## Why?

This is mainly to replace `python3 -m http.server` and `python3 -m uploadserver` which does not support SSL by default. It's also simpler to run and only require 1 port to be opened.

#!/usr/bin/env bash

# inspired here: https://www.vagrantup.com/docs/getting-started/provisioning.html

apt-get update
apt-get install -y php php-xdebug php-xml zip unzip composer doxygen graphviz

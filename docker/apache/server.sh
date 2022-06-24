#!/bin/bash

cd /server

composer install --optimize-autoloader && symfony server:start

tail -f /dev/null





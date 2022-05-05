#!/bin/bash

cd /server

composer install && symfony server:start

tail -f /dev/null





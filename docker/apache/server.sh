#!/bin/bash

cd /server

composer install && php bin/console d:s:u -f && symfony server:start





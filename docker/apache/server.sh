#!/bin/bash

cd /server

composer install && symfony console cache:clear && symfony server:start && symfony console d:f:l -n





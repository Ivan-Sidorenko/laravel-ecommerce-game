#!/bin/bash
npm install
composer install --no-scripts
gulp
./artisan clear-compiled
./artisan migrate:refresh
./artisan db:seed

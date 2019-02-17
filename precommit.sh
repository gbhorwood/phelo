#!/bin/bash

./vendor/bin/php-cs-fixer fix src/Phelo.php

./vendor/bin/phpstan analyse --level=7 src/*

./vendor/bin/phpunit

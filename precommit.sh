#!/bin/bash

./vendor/bin/php-cs-fixer fix src/Phelo.php

./vendor/bin/phpstan analyse --level=5 src/*

./vendor/bin/phpunit

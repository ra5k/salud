#!/bin/sh

DIR=$(dirname $0)
PHPUNIT="$DIR/../vendor/phpunit/phpunit/phpunit"
BOOTSTRAP="$DIR/bootstrap.php"

$PHPUNIT --bootstrap "$BOOTSTRAP" $@

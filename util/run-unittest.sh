#!/bin/sh

set -e

php vendor/bin/phpunit -c phpunit.xml.dist "$@"

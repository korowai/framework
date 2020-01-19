#!/bin/sh

set -e

php vendor/bin/phpunit -c phpunit-nocov.xml.dist "$@"

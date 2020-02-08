#!/bin/sh

set -e

# Set KOROWAI_HEAVY_TESTING=on for more thorough testing.

php vendor/bin/phpunit -c phpunit-nocov.xml.dist "$@"

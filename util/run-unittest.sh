#!/bin/sh

set -e

# Set KOROWAI_HEAVY_TESTING=on for more thorough testing.

php vendor/bin/phpunit \
  --coverage-clover build/logs/clover.xml \
  --coverage-html build/coverage/html \
  -c phpunit.xml.dist \
  "$@"

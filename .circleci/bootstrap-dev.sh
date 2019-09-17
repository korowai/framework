#!/usr/bin/env sh

here=`dirname $0`
top=`dirname $here`

sed -e "s:@UID@:`id -u`:g" -e "s:@GID@:`id -g`:g" $top/.env.dist > $top/.env

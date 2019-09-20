#!/bin/sh

set -e

here="`dirname $0`";
top="$here/..";

pushd $top > /dev/null

find src/ -type f -name '*.php' | while read f; do
    t=`head -10 "$f" | awk '/^ \* @file / {print $3}'`;
    l=`head -10 "$f" | awk '/^ \* @file / {print $0}'`;
    if [ "$f" != "$t" ] ; then
      echo "$f: $l";
    fi
done

popd > /dev/null

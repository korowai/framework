#!/bin/bash

set -e

here="`dirname $0`";
top="$here/..";
err=0;

pushd $top > /dev/null

while read f; do
  test ! -z "$f" || continue;
  t=`head -12 $f | grep -e '^ *\* *@test \+\S\+' | sed -e 's|^ *\* *@test \+\(\S\+\).*|\1|'`;
  if [ -z "$t" ]; then
   t=`echo "$f" | sed -e 's|^\(\./\)\?src/|\1tests/|' -e 's|\.php$|Test.php|'`
  fi
  test -f "$t" || echo $t
done <<<$( find src/ -type f -name '*.php' ) | sort | uniq

while read f; do
  test ! -z "$f" || continue;
  p=`echo $f | sed -e 's|^\(\(\./\)\?packages/\w\+\)/.*|\1|'`;
  t=`head -12 $f | grep -e '^ *\* *@test \+\S\+' | sed -e 's|^ *\* *@test \+\(\S\+\).*|'$p'/\1|'`;
  if [ -z "$t" ]; then
   t=`echo "$f" | sed -e 's|^\(\./\)\?packages/\(\w\+\)/src/|\1packages/\2/tests/|' -e 's|\.php$|Test.php|'`
  fi
  test -f "$t" || echo $t
done <<<$( find packages/*/src/ -type f -name '*.php' ) | sort | uniq

popd > /dev/null

exit $err;

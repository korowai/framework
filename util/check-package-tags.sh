#!/bin/bash

set -e

here="`dirname $0`";
top="$here/..";
err=0;

pushd $top > /dev/null

while read f; do
    p=`echo $f | sed -e 's#\(\./\)\?packages/\([^/]\+\)/.\+#\2#'`;
    e=`echo  ' * @package korowai/'"$p"`;
    l=`head -10 "$f" | awk '/^ \* @package / {print $0}'`;
    if [ ! -z "$l" ] && [ "$e" != "$l" ] ; then
      lq=`echo "$l" | sed -e 's#\\\\#\\\\\\\\#g' -e 's#\*#\\\\*#g'`
      eq=`echo "$e" | sed -e 's#\\\\#\\\\\\\\#g'`
      echo "sed -e ""'""s#^$lq#$eq#g""'"" -i $f";
      if (( 0 == $err )); then err=2; fi
    fi
done <<<$( find packages/ -type f -name '*.php' )

popd > /dev/null

exit $err;

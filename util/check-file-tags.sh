#!/bin/bash

set -e

here="`dirname $0`";
top="$here/..";

pushd $top > /dev/null

find src/ -type f -name '*.php' | while read f; do
    e=`echo " * @file $f"`;
    l=`head -10 "$f" | awk '/^ \* @file / {print $0}'`;
    if [ "$e" != "$l" ] ; then
      lq=`echo "$l" | sed -e 's#\*#\\\\*#g'`
      eq=`echo "$e" | sed -e 's#\*#\\*#g'`
      echo "sed -e ""'""s#^$lq#$eq#g""'"" -i $f";
    fi
done

find packages/ -type f -name '*.php' | while read f; do
##    e=`echo " \* @file $f" | `;
    e=`echo  " * @file $f"`
    e=`echo "$e" | sed -e 's#^ \* @file \.\?packages/[^/]\+/# * @file #'`;
    l=`head -10 "$f" | awk '/^ \* @file / {print $0}'`;
    if [ "$e" != "$l" ] ; then
      lq=`echo "$l" | sed -e 's#\*#\\\\*#g'`
      eq=`echo "$e" | sed -e 's#\*#\\*#g'`
      echo "sed -e ""'""s#^$lq#$eq#g""'"" -i $f";
    fi
done

popd > /dev/null

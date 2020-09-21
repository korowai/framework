#!/bin/bash

set -e

here="`dirname $0`";
top="$here/..";
err=0;

pushd $top > /dev/null

for dir in packages/*; do
  if [ -d "$dir"  ] && [ -f "$dir/composer.json" ]; then
    package=`jq -r '.name' "$dir/composer.json"`;
    repodir="build/monorepo-split/repositories/${package}.git";
    if [ -e "$repodir" ]; then
      echo "warning: $repodir exists, please remove it and run script again" >&2;
    else
      echo "info: creating bare repository for ${repo} in ${repodir}";
      mkdir -p "$repodir";
      git init --bare "$repodir" > /dev/null;
    fi
  fi
done

popd > /dev/null

exit $err;

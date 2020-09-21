#!/bin/bash

set -e

here="`dirname $0`";

top="$here/..";
if which readlink >/dev/null; then
  top=`readlink -f "$top"`
fi

usage() {
  cat >&2 <<!

usage:
        $0 [-f] [-d] [-a] [-v] [base-repo-dir]

Initialize bare git repositories under base-repo-dir
for split packages to be created with monorepo-builder.

options:

  -f    force reinitialization (deletes existing repositories)
  -d    dry run, print commands that would be executed instead or running them
  -a    ansii output (do not use colors)
  -v    verbose mode
  -h    print help and exit

parameters:

  base-repo-dir   base directory under which package repositories will be
                  created (default: $repobase)
!
}

warn() {
  echo -e "${brown}warning:${reset} $1" >&2;
}

error() {
  echo -e "${red}error${reset} $1" >&2;
}

info() {
  echo -e "${green}info:${reset} $1";
}

force=false;
dry=false;
ansii=false;
verbose=false;
repobase="${top}/build/monorepo-split/repositories";

while getopts "fdavh" option; do
  case $option in
    f)
      force=true;
      ;;
    d)
      dry=true;
      verbose=true;
      ;;
    a)
      ansii=true;
      ;;
    v)
      verbose=true;
      ;;
    h)
      usage;
      exit 0;
      ;;
    *)
      usage;
      exit 1;
      ;;
  esac
done

arg1="${@:$OPTIND:1}";
if [ ! -z "$arg1" ]; then
  repobase="$arg1";
fi

if $ansii; then
  red='';
  green='';
  brown='';
  reset='';
else
  red='\033[0;31m';
  green='\033[0;32m';
  brown='\033[0;33m';
  reset='\033[0m';
fi

pushd $top > /dev/null

for dir in packages/*; do
  if [ -d "$dir"  ] && [ -f "$dir/composer.json" ]; then
    package=`jq -r '.name' "$dir/composer.json"`;
    repodir="${repobase}/${package}.git";

    if [ -e "$repodir" ]; then
      if $force; then
        warn "${repodir} exists, deleting it";
        if $verbose; then
cat -- <<!
rm -rf "${repodir}";
!
        fi
        $dry || rm -rf "${repodir}";
      else
        warn "${repodir} exists, skipping for ${package}";
        continue;
      fi
    fi

    info "creating bare repository for ${package} in ${repodir}";

    if $verbose; then
cat -- <<!
mkdir -p "$repodir";
git init --bare "$repodir" > /dev/null;
!
    fi
    if ! $dry; then
      mkdir -p "$repodir";
      git init --bare "$repodir" > /dev/null;
    fi
  fi
done

popd > /dev/null

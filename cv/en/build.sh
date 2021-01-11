#!/bin/sh
export JEKYLL_VERSION=3.8
cd "$(dirname "$0")/../"
docker run --rm \
  --volume="$PWD:/srv/jekyll" \
  -it jekyll/jekyll:$JEKYLL_VERSION \
  /bin/bash -c \
    "cd /srv/jekyll/cv-src && 
    bundle install &&
    bundle update github-pages &&
    jekyll build -d ../cv"


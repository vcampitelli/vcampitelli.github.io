#!/bin/bash
set -e

SOURCE_FOLDER="cv-src/"
DESTINATION_FOLDER="cv/"
JEKYLL_VERSION=3.8
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
BUNDLE_BUILD__SASSC=--disable-march-tune-native
BRANCH="gh-pages"

cd "$(dirname "$0")/../"

# Building files
docker run --rm \
  --volume="${PWD}:/srv" \
  -it jekyll/jekyll:${JEKYLL_VERSION} \
  /bin/bash -c \
    "cd /srv/${SOURCE_FOLDER} && 
    bundle install --jobs 4 &&
    JEKYLL_ENV=production bundle exec jekyll build -d ../${DESTINATION_FOLDER}"

# Comitting them
git add ${DESTINATION_FOLDER}
git commit -m "Building static files"
git push --force ${BRANCH}

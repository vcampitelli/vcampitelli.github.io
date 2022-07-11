#!/bin/bash
set -e

SOURCE_FOLDER="cv-src/"
DESTINATION_FOLDER="cv/"
JEKYLL_VERSION=3.8
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
BUNDLE_BUILD__SASSC=--disable-march-tune-native
BRANCH="gh-pages"
IS_BUILD=false
CONTAINER_NAME="jekyll-cv"

cd "$(dirname "$0")/../"

case "$1" in
    "serve")
        jekyll_command="serve -w -H 0.0.0.0"
        ;;

    *)
        IS_BUILD=true
        jekyll_command="build -d ../${DESTINATION_FOLDER}"
        ;;
esac

# Building files
COMMAND="cd /srv/${SOURCE_FOLDER} && bundle install --jobs 4 && JEKYLL_ENV=production bundle exec jekyll ${jekyll_command}"
if [[ $(docker ps --filter "name=${CONTAINER_NAME}" --format ".") == "." ]]; then
  docker exec -it $CONTAINER_NAME /bin/bash -c $COMMAND
elif [[ $(docker ps -a --filter "name=${CONTAINER_NAME}" --format ".") == "." ]]; then
  docker start $CONTAINER_NAME
  docker exec -it $CONTAINER_NAME /bin/bash -c $COMMAND
else
  docker run \
    --name $CONTAINER_NAME \
    --volume="${PWD}:/srv" \
    -it jekyll/jekyll:${JEKYLL_VERSION} \
    $COMMAND

fi

# Comitting them
if $IS_BUILD; then
    git add ${DESTINATION_FOLDER}
    git commit -m "Build dos arquivos estáticos do CV"
    git push --force ${BRANCH}
    rm -rf ${DESTINATION_FOLDER}
fi

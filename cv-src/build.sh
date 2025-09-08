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

COMMAND="bundle exec jekyll"
case "$1" in
    "serve")
        COMMAND+=" serve -w -H 0.0.0.0 --trace"
        ;;

    *)
        IS_BUILD=true
        COMMAND="JEKYLL_ENV=production ${COMMAND} build -d ../${DESTINATION_FOLDER}"
        ;;
esac

# Building files
if [[ $(docker ps --filter "name=${CONTAINER_NAME}" --format ".") == "." ]]; then
  docker exec -it -w "/app/${SOURCE_FOLDER}" $CONTAINER_NAME /bin/bash -c "$COMMAND"
elif [[ $(docker ps -a --filter "name=${CONTAINER_NAME}" --format ".") == "." ]]; then
  docker start $CONTAINER_NAME
  docker exec -it -w "/app/${SOURCE_FOLDER}" $CONTAINER_NAME /bin/bash -c "$COMMAND"
else
  if [[ $(docker images --filter "reference=${CONTAINER_NAME}:latest" --format ".") != "." ]]; then
    docker build -t $CONTAINER_NAME $SOURCE_FOLDER
  fi
  docker run \
    --name $CONTAINER_NAME \
    --volume="${PWD}:/app" \
    -p "4000:4000" \
    -w "/app/${SOURCE_FOLDER}" \
    -it $CONTAINER_NAME \
    $COMMAND
fi

# Comitting them
if $IS_BUILD; then
    git add ${DESTINATION_FOLDER}
    git commit ${DESTINATION_FOLDER} -m ":construction_worker: Building $(git rev-parse HEAD)"
    git push --force origin ${BRANCH}
    rm -rf ${DESTINATION_FOLDER}
fi

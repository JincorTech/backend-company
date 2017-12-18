#!/bin/bash

set -ex
WORKSPACE="jincort/backend-company"
FPM="jincort/backend-fpm-company"
WORKER="jincort/backend-company-worker"
TAG="${1}"

docker build -t ${WORKSPACE}:${TAG} -f workspace.production .
docker push ${WORKSPACE}:${TAG}

# @TODO: remove, it's temporary
if [ "${TAG}" == "stage" ]; then
  cd ./devops/stageprod
  sh ./build.sh ${TAG}
  docker push ${FPM}:${TAG}
else
  docker build -t ${FPM}:${TAG} -f companies.production .
  docker push ${FPM}:${TAG}
fi

#!/bin/bash

set -ex
WORKSPACE="jincort/backend-company"
FPM="jincort/backend-fpm-company"
WORKER="jincort/backend-company-worker"
TAG="${1}"

docker build -t ${WORKSPACE}:${TAG} -f workspace.production .
docker push ${WORKSPACE}:${TAG}

docker build -t ${FPM}:${TAG} -f companies.production .
docker push ${FPM}:${TAG}
#!/bin/sh

TAG=${TAG:-stage}

echo 'Build '$TAG' version'
cp .dockerignore ../..
docker build -t jincort/backend-fpm-company:$TAG -f Dockerfile ../..
rm ../../.dockerignore

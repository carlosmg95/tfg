#!/bin/sh

#Reset docker machine if exists
docker stop ewemongo
docker rm ewemongo
docker stop ewetasker
docker rm ewetasker
docker network rm ewetaskernet
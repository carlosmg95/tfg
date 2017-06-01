#!/bin/sh

#Reset docker machine if exists
docker stop ewemongo
docker rm ewemongo
docker stop ewetasker
docker rm ewetasker
docker network rm ewetaskernet

#Create the image
docker build -t carlosmg95/ewetasker .

#Create network and ewemongo
docker network create ewetaskernet
docker run -d --name ewemongo -v $PWD/mongodb-data:/data/db --net ewetaskernet mongo

#Run carlosmg95/ewetasker localhost:8080
docker run --net ewetaskernet --rm -v $PWD/mongo-example:/mongo-example  mongo mongorestore --host ewemongo /mongo-example
docker run -d --name ewetasker -v $PWD/www/img:/var/www/html/img --net ewetaskernet -e MONGO_HOST=ewemongo -p 8080:80 carlosmg95/ewetasker
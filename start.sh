#!/bin/sh

#Reset docker machine if exists
docker stop ewemongo
docker rm ewemongo
docker stop ewetasker
docker rm ewetasker
docker network rm ewetaskernet

docker build -t carlosmg95/ewetasker .

#Create network and ewemongo
docker network create ewetaskernet
docker run -d --name ewemongo -v $PWD/mongodb-data:/data/db --net ewetaskernet mongo
sleep 3

#Config mongo database
#cat <<EOF | docker run --net ewetaskernet --rm mongo mongo applicationdb --host ewemongo applicationdb
#vdb.createUser({ user: 'client', pwd: 'gsimongodb2015' });
#exit
#EOF
#sleep 5

#Run carlosmg95/ewetasker localhost:8080
docker run --net ewetaskernet --rm -v $PWD/mongo-example:/mongo-example  mongo mongorestore --host ewemongo /mongo-example
docker run -d --name ewetasker --net ewetaskernet -e MONGO_HOST=ewemongo -p 8080:80 carlosmg95/ewetasker
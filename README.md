INSTALLATION GUIDE

Prerequisite:
Docker engine

STEPS TO RUN THE PROJECT
Clone this repo from this url
https://github.com/ChronoDevs/chronoknowledge_laravel

Open project folder on your vscode IDE
Inside of your vscode terminal,

Please run
- docker-compose up -d

Once you build your docker
EXEC
- docker ps

Now please find your container ID from the IMAGE name chronoknowledge_laravel_php, and copy

Once you got your container id
EXEC
- docker exec -it <your-container-id> //bin//sh
if error,
EXEC
- winpty docker exec -it <container-id> //bin//sh

This will lead you inside docker terminal

INSIDE, please run
- composer install

------------------------
FRONTEND PART / Outside of your docker terminal

Please run
- docker-compose run npm install
- docker-compose run npm run watch


# ranking-api
A simple API for game ranking made with Laravel 5.6. Back-end for [RankHome](https://github.com/tiagopaes/rankhome)

## Project setup

Make sure you have installed [Docker](https://docs.docker.com/install/) and [Docker Compose](https://docs.docker.com/compose/install/) to setup the project.

### Run up the docker containers
```
docker-compose up -d
```

### Enter into web server container to finish the setup
```
docker exec -it ranking-app /bin/bash
```

### Create `.env` file to store the project environment variables
```
cp .env.example .env
```

### Install the project dependencies
```
composer install
```

### Generate an app key
```
php artisan key:generate
```

### Run the tests
```
./vendor/bin/phpunit tests
```

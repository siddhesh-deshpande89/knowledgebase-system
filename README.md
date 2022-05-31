
## Objective
In this example we will setup a knowledgebase system with Laravel 9.x.
- Setup a knowledgebase system that is capable running on cluster of web servers.
- REST API endpoints to create, view, rate articles.
- Integration tests using sqlite, database factory and seeders.
- Job Dispatching for POST queries such as view count update.
- Fuzzy search and tests using laravel scout

## Setup
- Go to the project root folder and start docker container with the following command:

```
cd knowledgebase-system && ./vendor/bin/sail up -d
```
- Navigate to http://localhost in your web browser.



## Executing commands in docker container
If you are not using php 8.x you will need to run tests in docker container.
```
docker exec -it knowledge-system-laravel.test-1  /bin/bash
```

## Running Migrations and Seeders
```
php artisan migrate --seed
```

## Testing
If you are not using php 8.x you will need to run tests in docker container.
```
docker exec -it knowledge-system-laravel.test-1 composer test
```
Unit tests only
```
docker exec -it knowledge-system-laravel.test-1 composer test:unit
```

Feature tests only
```
docker exec -it knowledge-system-laravel.test-1 composer test:feature
```

###Test Coverage 
Test coverage reports can be generated using following commands. The output of the report
will be generated in `knowledgebase-system/reports/coverage/` folder in index.html and dashboard.html.
```
docker exec -it knowledge-system-laravel.test-1 composer test:coverage
```

Test Coverage with unit tests only
```
docker exec -it knowledge-system-laravel.test-1 composer test:coverage_unit
```

## API Documentation
The api documentation will be available at: http://localhost/api/documentation
To generate the swagger api json file give the following command 
```
docker exec -it knowledge-system-laravel.test-1 php artisan l5-swagger:generate 
```

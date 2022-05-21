
## Objective
In this example we will setup a knowledgebase system with Laravel 9.x.
- Setup a knowledgebase system that is capable running on cluster of web servers.
- REST API endpoints to create, view, rate articles.
- 100% Unit test coverage
- Integration tests using sqlite, database factory and seeders.
- Redis caching to cache the data
- Job Dispatching for POST queries such as view count update.

## Setup
- Go to the project root folder and start docker container with the following command:

```
cd knowledgebase-system && ./vendor/bin/sail up -d
```
- Navigate to http://localhost in your web browser.

## Running Tests
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

##Database Schema
categories Table

| Field      | DataType  | Constraints                 | Comments                 |
|------------|-----------|-----------------------------|--------------------------|
| id         | bigint    | PrimaryKey                  |                          |
| title      | varchar   | Not Null, Unique            |                          |
| active     | tinyint   | Not Null, Default(1)        | 0 = inactive, 1 = active |
| created_at | timestamp | CURRENT_TIMESTAMP           |                          |
| updated_at | timestamp | On Update CURRENT_TIMESTAMP |                          |

articles Table

| Field      | DataType  | Constraints                 | Comments                 |
|------------|-----------|-----------------------------|--------------------------|
| id         | bigint    | PrimaryKey                  |                          |
| title      | varchar   | Not Null                    |                          |
| body       | long_text | Not Null                    |                          |
| active     | tinyint   | Not Null, Default(1)        | 0 = inactive, 1 = active |
| created_at | timestamp | CURRENT_TIMESTAMP           |                          |
| updated_at | timestamp | On Update CURRENT_TIMESTAMP |                          |                      |                          |

article_categories Table

| Field       | DataType  | Constraints                 | Comments |
|-------------|-----------|-----------------------------|----------|
| id          | bigint    | PrimaryKey                  |          |
| article_id  | bigint    | References articles.id      |          |
| category_id | bigint    | References categories.id    |          |
| created_at  | timestamp | CURRENT_TIMESTAMP           |          |
| updated_at  | timestamp | On Update CURRENT_TIMESTAMP |          |                      |                          |
